<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Text;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{


    public function theme($type = null ){
        $this->disableAutoRender();
        $session = $this->request->getSession();
        if($type == 2){
            $session->write('theme','dark');
        }
        elseif($type == 1){
            $session->write('theme','white');
        }

        exit;

    }


    public function star($type = null, $id = null ){
        $this->disableAutoRender();
        $session = $this->request->getSession();
        $arr = $session->read('star');
        if(!empty($type) && !empty($id)){
            if($type == 'add'){
                $arr[$id] = $id;
                $session->write('star',$arr);
                ec($arr);
            }
            elseif($type == 'rm'){
                unset($arr[$id]);
                $session->write('star',$arr);
                ec($arr);
            }
        }
        

        exit;

    }


    public function index()
    {
        $session = $this->request->getSession();
        $star = $session->read('star');
        $data = [];
        try {
            $this->paginate = ['conditions' => ['type' => 'stock', 'name !=' => ''], 'limit' => 100, 'order' => ['market_cap' => 'desc']];
            $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        $this->set(compact('data','star'));
    }

    public function search()
    {
        $this->disableAutoRender();
        $arr = [];
        $q = $this->request->getQuery('query');
        if(!empty($q)){
            $data = $this->fetchTable('Stocks')->find()
            ->select(['id','name','slug','symbol','logo'])
            ->where(['type' => 'stock', 'name !=' => '','name LIKE' =>"%" . trim($q) . "%"])
            ->all();
            if (!$data->isEmpty()) {
                foreach ($data as $list) {
                    $logo = $list->logo;
                    if (!empty($list->logo_dark)) {
                        $logo = SITEURL."logo/".$list->logo_dark;
                    }
                    elseif (!empty($list->logo_bright)) {
                        $logo = SITEURL."logo/".$list->logo_bright;
                    }

                    $arr[]= ['name'=>$list->name,'symbol'=>$list->symbol,'logo'=>$logo,'url'=>$list->slug];
                }
            }
        }
        echo json_encode($arr);
    }
    public function profile($type = null)
    {
        $session = $this->request->getSession();
        $setTime = date("Y-m-d H:i:s", strtotime('+3 hours', strtotime(DATE)));
        if (!empty($this->request->getParam('slug'))) {
            $data = $this->fetchTable('Stocks')->find()->where(['slug' => $this->request->getParam('slug')])->first();
            $d1 = date("Y-m-d", strtotime("-30 days", strtotime(TODAYDATE)));
            if (!empty($data)) {
                $ticker = strtolower(Text::slug($data->symbol));
                $s_time = $ticker . 'getTime';
                $s_name = $ticker . 'apiData';

                $getTime = $session->read($s_time);
                $apiData = $session->read($s_name);

                $cache = false;
                if (!empty($getTime) && $getTime > strtotime(DATE)) {
                    $cache = true;
                } else {
                    $getTime = $setTime;
                    $session->write($s_time, $getTime);

                    $cache = false;
                }
                $url = "https://financialmodelingprep.com/api/v4/company-outlook?symbol=" . $data->symbol . "&apikey=" . env('financialmodelingprep_api');

                $url3 = "https://financialmodelingprep.com/api/v3/historical-price-full/" . $data->symbol . "?from=$d1&to=" . TODAYDATE . "&apikey=" . env('financialmodelingprep_api');

                if ($cache === false) {
                    $apiData['outlook'] = $outlook = callApi($url);
                    $apiData['price'] =  $price = callApi($url3);
                    $session->write($s_name, $apiData);
                } else {
                    $outlook = $apiData['outlook'];
                    $price = $apiData['price'];
                }
                $data->price_history = json_encode($price);
                $data->company_outlook = json_encode($outlook);
                $data->price = $outlook['profile']['price'];
                $data->mkt_cap = $outlook['profile']['mktCap'];
                $this->fetchTable('Stocks')->save($data);

                if (empty($type) || $type == 'marketcap') {
                    $url2 = "https://financialmodelingprep.com/api/v4/stock_peers?symbol=" . $data->symbol . "&apikey=" . env('financialmodelingprep_api');
                    $url4 = "https://financialmodelingprep.com/api/v3/historical-market-capitalization/" . $data->symbol . "?limit=100&apikey=" . env('financialmodelingprep_api');

                    if ($cache === false ||  empty($apiData['stock_peers'])) {
                        $apiData['stock_peers'] = $stock_peers = callApi($url2);
                    } else {
                        $stock_peers = $apiData['stock_peers'];
                    }
                    if ($cache === false ||  empty($apiData['market_cap'])) {
                        $apiData['market_cap'] = $market_cap = callApi($url4);
                        $session->write($s_name, $apiData);
                    } else {
                        $market_cap = $apiData['market_cap'];
                    }
                    $data->stock_peers = json_encode($stock_peers);
                    $data->market_cap_list = json_encode($market_cap);


                    $this->fetchTable('Stocks')->save($data);

                    $peers = null;
                    if (isset($stock_peers[0]['peersList']) && !empty($stock_peers[0]['peersList'])) {
                        $peers = $this->fetchTable('Stocks')->find('all')->where(['slug IS NOT NULL', 'symbol IN' => $stock_peers[0]['peersList']])->all();
                    }

                    $this->set(compact('market_cap', 'peers'));
                } else {
                    if ($type == 'revenue') {
                        $revenue_url = "https://financialmodelingprep.com/api/v3/income-statement/" . $data->symbol . "?limit=120&apikey=" . env('financialmodelingprep_api');
                        if ($cache === false ||  empty($apiData['revenue'])) {
                            $apiData['revenue'] = $revenue = callApi($revenue_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $revenue = $apiData['revenue'];
                        }
                        $this->set(compact('revenue'));
                    } elseif ($type == 'earnings') {
                        $earnings_url = "https://financialmodelingprep.com/api/v3/income-statement/" . $data->symbol . "?limit=120&apikey=" . env('financialmodelingprep_api');
                        if ($cache === false ||  empty($apiData['earnings'])) {
                            $apiData['earnings'] = $earnings = callApi($earnings_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $earnings = $apiData['earnings'];
                        }
                        $this->set(compact('earnings'));
                    } elseif ($type == 'stock-price-history') {
                        $price_url = "https://financialmodelingprep.com/api/v3/historical-price-full/" . $data->symbol . "?serietype=line&apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['price'])) {
                            $apiData['price'] = $price = callApi($price_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $price = $apiData['price'];
                        }
                        $this->set(compact('price'));
                    } elseif ($type == 'pe-ratio') {
                        $ot_url = "https://financialmodelingprep.com/api/v3/ratios/" . $data->symbol . "?limit=120&apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['pe'])) {
                            $apiData['pe'] = $pe = callApi($ot_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $pe = $apiData['pe'];
                        }
                        $this->set(compact('pe'));
                    } elseif ($type == 'ps-ratio') {
                        $ot_url = "https://financialmodelingprep.com/api/v3/ratios/" . $data->symbol . "?limit=120&apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['ps'])) {
                            $apiData['ps'] = $ps = callApi($ot_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $ps = $apiData['ps'];
                        }
                        $this->set(compact('ps'));
                    } elseif ($type == 'pb-ratio') {
                        $ot_url = "https://financialmodelingprep.com/api/v3/ratios/" . $data->symbol . "?limit=120&apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['pb'])) {
                            $apiData['pb'] = $pb = callApi($ot_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $pb = $apiData['pb'];
                        }
                        $this->set(compact('pb'));
                    } elseif ($type == 'dividends') {
                        $ot_url = "https://financialmodelingprep.com/api/v3/historical-price-full/stock_dividend/" . $data->symbol . "?apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['dividends'])) {
                            $apiData['dividends'] = $dividends = callApi($ot_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $dividends = $apiData['dividends'];
                        }
                        $this->set(compact('dividends'));
                    } elseif ($type == 'stock-splits') {
                        $ot_url = "https://financialmodelingprep.com/api/v3/historical-price-full/stock_split/" . $data->symbol . "?apikey=" . env('financialmodelingprep_api');

                        if ($cache === false ||  empty($apiData['splits'])) {
                            $apiData['splits'] = $splits = callApi($ot_url);
                            $session->write($s_name, $apiData);
                        } else {
                            $splits = $apiData['splits'];
                        }
                        $this->set(compact('splits'));
                    } elseif ($type == 'outlook') {
                        $outlook = json_decode($data->company_outlook, true);
                        $this->set(compact('outlook'));
                    }
                }
                $this->set(compact('data', 'type'));
            } else {
                $this->viewBuilder()->setLayout('error_404');
            }
        } else {
        }
    }
}
