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
class HomesController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // methods name we can pass here which we want to allow without login
        parent::beforeFilter($event);
        $this->Auth->allow();
    }


    public function theme($type = null)
    {
        $this->disableAutoRender();
        $session = $this->request->getSession();
        if ($type == 2) {
            $session->write('theme', 'dark');
        } elseif ($type == 1) {
            $session->write('theme', 'white');
        }

        exit;
    }


    public function star($type = null, $id = null)
    {
        $this->disableAutoRender();
        if ($this->Auth->User('id') != "") {
            if (!empty($type) && !empty($id)) {
                $query = $this->fetchTable('Watchlists')->find('all', ['conditions' => ['user_id' => $this->Auth->User('id'), 'stock_id' => $id]]);
                $row = $query->first();
                if ($type == 'add') {
                    if (empty($row)) {
                        $watchlist = $this->fetchTable('Watchlists')->newEmptyEntity();
                        $watchlist->user_id = $this->Auth->User('id');
                        $watchlist->stock_id = $id;
                        $this->fetchTable('Watchlists')->save($watchlist);
                        echo '<script>$("#sel_' . $id . '").removeClass("add_star").addClass("rm_star");$("#sel_' . $id . '").attr("src", "' . SITEURL . 'img/star_dark.svg");</script>';
                    }
                } elseif ($type == 'rm') {
                    if (!empty($row)) {
                        $this->fetchTable('Watchlists')->delete($row);
                        echo '<script>$("#sel_' . $id . '").removeClass("rm_star").addClass("add_star"); $("#sel_' . $id . '").attr("src", "' . SITEURL . 'img/star.svg");</script>';
                    }
                }
            }
        } else {
            if ($this->request->is('ajax')) {

                echo "<script>doLogin();</script>";
                exit;
            } else {
                $this->redirect('/login');
            }
        }
        exit;
    }


    public function index()
    {
        $star = $data = [];
        if ($this->Auth->User('id') != "") {
            $query = $this->fetchTable('Watchlists')->find('list', ['conditions' => ['user_id' => $this->Auth->User('id')], 'keyField' => 'stock_id', 'valueField' => 'stock_id']);
            $star = $query->toArray();
        }
        try {
            $this->paginate = ['conditions' => ['type' => 'stock', 'name !=' => ''], 'limit' => 100, 'order' => ['market_cap' => 'desc']];
            $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
        } catch (\Throwable $th) {
            //throw $th;
        }

        $seo = $this->fetchTable('Settings')->findById('1')->firstOrFail();
        $this->set(compact('data', 'star', 'seo'));
    }

    public function country($id = null, $st = null ){
        if(empty($id)){
            return $this->redirect('/');
            exit;
        }
        $star = $data = [];
        if ($this->Auth->User('id') != "") {
            $query = $this->fetchTable('Watchlists')->find('list', ['conditions' => ['user_id' => $this->Auth->User('id')], 'keyField' => 'stock_id', 'valueField' => 'stock_id']);
            $star = $query->toArray();
        }
        try {
            $this->paginate = ['conditions' => ['type' => 'stock', 'name !=' => '','country'=>$id], 'limit' => 500, 'order' => ['market_cap' => 'desc']];
            $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
        } catch (\Throwable $th) {
            //throw $th;
        }

        $st = str_replace("-"," ",$st);
        $st = str_replace("market cap","market capitalization",$st);
        $st = ucwords($st);
        $this->set(compact('data', 'star', 'st','id'));
    }

    public function search()
    {
        $this->disableAutoRender();
        $arr = [];
        $q = $this->request->getQuery('query');
        $session = $this->request->getSession();
        $theme = $session->read('theme');

        if (!empty($q)) {
            $data = $this->fetchTable('Stocks')->find()
                ->select(['id', 'name', 'slug', 'symbol', 'logo', 'logo_bright', 'logo_dark'])
                ->where(['type' => 'stock', 'name !=' => '', 'name LIKE' => "%" . trim($q) . "%"])
                ->all();
            if (!$data->isEmpty()) {
                foreach ($data as $list) {

                    $logo = $logo_dark = $logo_nrm =  $list->logo;
                    if (!empty($list->logo_bright)) {
                        $logo_dark = $logo_nrm = SITEURL . "logo/" . $list->logo_bright;
                    }
                    if (!empty($list->logo_dark)) {
                        $logo_dark = SITEURL . "logo/" . $list->logo_dark;
                    }
                    $logo = ($theme == 'dark' ? $logo_dark : $logo_nrm);

                    $arr[] = ['name' => $list->name, 'symbol' => $list->symbol, 'logo' => $logo, 'url' => $list->slug];
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

                $star = null;
                if ($this->Auth->User('id') != "") {
                    $star = $this->fetchTable('Watchlists')->find()->where(['user_id' => $this->Auth->User('id'), 'stock_id' => $data->id])->first();
                }



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
                    $url4 = "https://financialmodelingprep.com/api/v3/historical-market-capitalization/" . strtoupper($data->symbol) . "?limit=500&apikey=" . env('financialmodelingprep_api');
                    if ($cache === false ||  empty($apiData['stock_peers'])) {
                        $apiData['stock_peers'] = $stock_peers = callApi($url2);
                    } else {
                        $stock_peers = $apiData['stock_peers'];
                    }

                    if ($cache === false || empty($apiData['market_cap'])) {
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
                $this->set(compact('data', 'type', 'star'));
            } else {
                $this->viewBuilder()->setLayout('error_404');
            }
        } else {
        }
    }

    /* open new popup on ajax request */
    public function openPop($id = null)
    {
        $this->autoRender = false;
        $getData = $this->request->getData();
        if (isset($getData['url']) && !empty($getData['url'])) {
            if ($id == 1) {
                echo "<script> $.magnificPopup.open({items: { src: '" . urldecode($getData['url']) . "',type: 'ajax'}, closeOnContentClick: false, closeOnBgClick: false, showCloseBtn: false, enableEscapeKey: false, }); </script>";
            } else {
                echo "<script> $.magnificPopup.open({items: { src: '" . urldecode($getData['url']) . "',type: 'ajax'}, closeMarkup: '<button class=\"mfp-close mfp-new-close\" type=\"button\" title=\"Close\">x</button>', closeOnContentClick: false, closeOnBgClick: false, showCloseBtn: true, enableEscapeKey: false}); </script>";
            }
        }
        exit;
    }

    public function verified($id = null)
    {
        if (!empty($id)) {
            $user_id = base64_decode($id);
            $user = $this->fetchTable('Users')->find()->where(['id' => $user_id])->first();
            if (!empty($user)) {
                $data = $this->fetchTable('Portfolios')->find()->where(['user_id' => $user_id])->contain(['Stocks'])->limit(5000)->all();
                $this->set(compact('data', 'user'));
            } else {
                $this->viewBuilder()->setLayout('error_404');
            }
        } else {
            $this->viewBuilder()->setLayout('error_404');
        }
    }
}
