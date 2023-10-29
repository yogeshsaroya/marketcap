<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Text;
//https://stockanalysis.com/stocks/bac/dividend/

class CronsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->disableAutoRender();
    }

    /* 1: run every 6hrs */
    public function updateCurrency()
    {
        $url = 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/usd.json';
        $data = $this->fetchTable('Settings')->find()->where(['id' => 1])->first();
        if (!empty($data)) {
            $res = callApi($url);
            if (isset($res['usd']) && !empty($res['usd'])) {
                $data->currency =  json_encode($res['usd']);
                $this->fetchTable('Settings')->save($data);
                ec('saved');
            } else {
                ec('Not Saved');
            }
        } else {
            ec('Empty');
        }
        exit;
    }

    /* 3: run every 1hr */
    public function updateUsdRate()
    {
        $data = $this->fetchTable('Settings')->find()->where(['id' => 1])->first();
        $up_arr = $app_ids = null;
        if (isset($data['currency']) && !empty($data['currency'])) {
            $usd = json_decode($data['currency'], true);
            if (!empty($usd)) {
                $stocks = $this->fetchTable('Stocks')->find('all')->where(['currency IS NOT NULL'])->select(['id', 'currency', 'usd_rate', 'mkt_cap', 'price'])->all();
                if (!empty($stocks)) {
                    foreach ($stocks as $li) {
                        if (isset($usd[strtolower($li->currency)])) {
                            $usd_rate = null;
                            $usd_rate = 1 / $usd[strtolower($li->currency)];
                            $app_ids[] = $li->id;
                            $stock_price = $li->price * $usd_rate;
                            $market_cap = $li->mkt_cap * $usd_rate;
                            $up_arr[] = ['id' => $li->id, 'usd_rate' => $usd_rate, 'stock_price' => $stock_price, 'market_cap' => $market_cap];
                        }
                    }
                }
            }
        }

        if (!empty($up_arr)) {
            $chkApp = $this->fetchTable('Stocks')->find()->where(['id IN' => $app_ids])->all();
            $appEnt = $this->fetchTable('Stocks')->patchEntities($chkApp, $up_arr, ['validate' => false]);
            $res = $this->fetchTable('Stocks')->saveMany($appEnt);
            echo "Saved";
        } else {
            echo "Empty";
        }
        exit;
    }

    public function updateStocks()
    {
        $date = date("Y-m-d H:i:s", strtotime("-2 hrs", strtotime(DATE)));
        $data = $this->fetchTable('Stocks')->find('all')
            ->limit(1000)
            ->where([
                'error' => 1,
                'OR' => ['updated IS NULL', 'updated <' => $date]
            ])->all();

        $symbol = $tickers = null;
        if (!$data->isEmpty()) {
            foreach ($data as $list) {
                $symbol[] = $list->symbol;
            }
            $tickers = implode(',', $symbol);
            $url = "https://financialmodelingprep.com/api/v3/profile/$tickers?apikey=" . env('financialmodelingprep_api');

            $getData = callApi($url);
            $app_ids = $up_arr = [];
            if (isset($getData['Error Message'])) {
                ec($getData['Error Message']);
                die;
            } else {
                foreach ($data as $li) {
                    $arr = search($getData, 'symbol', strtoupper($li->symbol));
                    if (!empty($arr[0]['symbol'])) {
                        $slug = strtolower(Text::slug($arr[0]['companyName']));
                        $st = 'stock';
                        if ($arr[0]['isEtf'] == false && $arr[0]['isFund'] == false) {
                            $st = 'stock';
                        } else {
                            $st = 'other';
                        }
                        $app_ids[] = $li->id;
                        $up_arr[] = [
                            'id' => $li->id,
                            'type' => $st,
                            'name' => $arr[0]['companyName'],
                            'price' => $arr[0]['price'],
                            'vol_avg' => $arr[0]['volAvg'],
                            'mkt_cap' => $arr[0]['mktCap'],
                            'currency' => $arr[0]['currency'],

                            'exchange' => $arr[0]['exchange'],
                            'exchange_symbol' => $arr[0]['exchangeShortName'],

                            'industry' => $arr[0]['industry'],
                            'website' => $arr[0]['website'],
                            'description' => $arr[0]['description'],
                            'ceo' => $arr[0]['ceo'],
                            'sector' => $arr[0]['sector'],
                            'country' => $arr[0]['country'],
                            'full_time_employees' => $arr[0]['fullTimeEmployees'],
                            'phone' => $arr[0]['phone'],
                            'address' => $arr[0]['address'],
                            'city' => $arr[0]['city'],
                            'state' => $arr[0]['state'],
                            'zip' => $arr[0]['zip'],
                            'logo' => $arr[0]['image'],
                            'ipo_date' => (!empty($arr[0]['ipoDate']) ? date('Y-m-d', strtotime($arr[0]['ipoDate'])) : null),
                            'updated' => DATE,
                            'slug' => $slug
                        ];
                    } else {
                        $app_ids[] = $li->id;
                        $up_arr[] = [
                            'id' => $li->id,
                            'type' => 'other',
                            'updated' => DATE,
                            'error' => 2
                        ];
                    }
                }
            }
            if (!empty($up_arr)) {
                $chkApp = $this->fetchTable('Stocks')->find()->where(['id IN' => $app_ids])->all();
                $appEnt = $this->fetchTable('Stocks')->patchEntities($chkApp, $up_arr, ['validate' => false]);
                try {
                    $res = $this->fetchTable('Stocks')->saveMany($appEnt);
                } catch (\Throwable $th) {
                    throw $th;
                }
                echo "Saved";
            } else {
                ec('empty.');
            }
        } else {
            ec('empty');
        }
        exit;
    }

    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }

    public function updateLogo()
    {

        $uploadPath = "logo";
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $data = $this->fetchTable('Stocks')->find('all')
            ->limit(20)
            ->where(['logo_bright IS NULL', 'type' => 'stock', 'symbol IS NOT NULL'])->all();
        if (!$data->isEmpty()) {
            foreach ($data as $li) {

                $u = 'https://companieslogo.com/api/1.0/?symbol='.strtoupper($li->symbol).'&api_key=1c6923454b9e996ea78572';
                $res = $this->curl_get_file_contents($u);
                $logos = json_decode($res, true);
                $slug = strtolower(Text::slug($li->symbol));
                if (isset($arr['png']['icon']['for_bright_background']['64'])) {
                    $logo1 = 'https://companieslogo.com' . $arr['png']['icon']['for_bright_background']['64'];
                    file_put_contents($uploadPath . "/$slug.png", $this->curl_get_file_contents($logo1));
                    $li->logo_bright =  $slug . ".png";
                }

                if (isset($arr['png']['icon']['for_dark_background']['64'])) {
                    $logo2 = 'https://companieslogo.com' . $arr['png']['icon']['for_dark_background']['64'];
                    file_put_contents($uploadPath . "/" . $slug . "-dark.png", $this->curl_get_file_contents($logo2));
                    $li->logo_dark =  $slug . "-dark.png";
                }
                $this->fetchTable('Stocks')->save($li);
                ec("Logo Saved for " . $li->name);
            }
        } else {
            ec('Empty');
        }




        exit;
    }

    public function getStocks()
    {
        die;
        $url = "https://financialmodelingprep.com/api/v3/available-traded/list?apikey=" . env('financialmodelingprep_api');
        $apiResult = callApi($url);
        if (!empty($apiResult)) {
            foreach ($apiResult as $list) {
                if ($list['type'] == 'stock' && $list['exchange'] != 'Other OTC' && !empty($list['exchange']) && !empty($list['name'])) {
                    $slug = strtolower(Text::slug($list['name']));
                    $arr[] = [
                        'id' => null,
                        'name' => $list['name'],
                        'symbol' => $list['symbol'],
                        'exchange' => $list['exchange'],
                        'exchange_symbol' => $list['exchangeShortName'],
                        'price' => $list['price'],
                        'type' => $list['type'],
                        //'slug'=>$slug
                    ];
                }
            }
        }
        if (!empty($arr)) {
            $Ent = $this->fetchTable('Stocks')->newEntities($arr);
            $result = $this->fetchTable('Stocks')->saveMany($Ent);
            ec('Saved');
        }
    }

    public function index()
    {

        die;
        //https://8marketcap.com/api.php?action=tickers
        $rs = $arr = [];
        $apiResult = callApi('https://8marketcap.com/api.php?action=tickers');
        foreach ($apiResult as $list) {
            $rs[] = strtolower($list['identifier']);
        }
        $new = array_unique($rs);
        foreach ($new as $k => $v) {
            $arr[] = [
                'id' => null,
                'symbol' => $v,
            ];
        }
        $Ent = $this->fetchTable('Stocks')->newEntities($arr);
        $result = $this->fetchTable('Stocks')->saveMany($Ent);
        echo "Saved";
        die;

        $data = $this->fetchTable('Apis')->find()->where(['id' => 1])->first();
        if (!empty($data)) {
            $queryString = http_build_query([
                'access_key' => env('marketstack_api'),
                'limit' => $data->limits,
                'offset' => $data->offset
            ]);

            $query = $this->fetchTable('Stocks')->find('all', []);
            $allStocks = $query->count();
            if ($allStocks <  $data->total) {
                $ch = curl_init(sprintf('%s?%s', 'http://api.marketstack.com/v1/tickers', $queryString));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $json = curl_exec($ch);
                curl_close($ch);
                $apiResult = json_decode($json, true);
                $arr = [];
                ec($apiResult['pagination']);
                //ec($apiResult['data']);die;
                if (!empty($apiResult['data'])) {
                    foreach ($apiResult['data'] as $list) {
                        $arr[] = [
                            'id' => null,
                            'name' => $list['name'],
                            'symbol' => $list['symbol'],
                            'exchange' => $list['stock_exchange']['name'],
                            'exchange_symbol' => $list['stock_exchange']['acronym'],
                            'exchange_mic' => $list['stock_exchange']['mic'],
                            'exchange_country' => $list['stock_exchange']['country'],
                            'exchange_country_code' => $list['stock_exchange']['country_code'],
                            'exchange_city' => $list['stock_exchange']['city'],
                            'exchange_website' => $list['stock_exchange']['website'],

                        ];
                    }
                }

                if (!empty($arr)) {
                    $Ent = $this->fetchTable('Stocks')->newEntities($arr);
                    $result = $this->fetchTable('Stocks')->saveMany($Ent);

                    $data->offset = $data->offset + 10000;
                    $data->count = $apiResult['pagination']['count'];
                    $data->total = $apiResult['pagination']['total'];
                    $this->fetchTable('Apis')->save($data);
                    ec('Saved');
                }
            } else {
                ec("All Stocks added : " . $allStocks);
            }
            die;
        }
    }
}
