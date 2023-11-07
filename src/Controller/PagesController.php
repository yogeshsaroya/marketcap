<?php

declare(strict_types=1);

namespace App\Controller;
use Cake\Utility\Text;

class PagesController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // methods name we can pass here which we want to allow without login
        parent::beforeFilter($event);
        /* https://book.cakephp.org/4/en/controllers/components/authentication.html#AuthComponent::allow */
        $this->viewBuilder()->setLayout('backend');
        if (!in_array($this->Auth->user('role'), [1])) {
            $this->redirect('/users/logout');
        }
    }
    public function initialize(): void
    {
        parent::initialize();
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

    public function index()
    {
        $menu_act = 'index';
        $cnd = ['type' => 'stock'];
        $search = null;
        if ($this->request->getQuery('search') && !empty($this->request->getQuery('search'))) {
            $search = $this->request->getQuery('search');
            $cnd['OR']['name LIKE'] = "%" . $search . "%";
            $cnd['OR']['symbol LIKE'] = "%" . $search . "%";
        }

        $this->paginate = ['conditions' => $cnd, 'limit' => 200, 'order' => ['market_cap' => 'desc']];
        $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
        $this->set(compact('data', 'search'));
    }

    public function addStock()
    {
        $post_data = null;
        if ($this->request->is('ajax')) {
            if (!empty($this->request->getData())) {
                $postData = $this->request->getData();

                $url = "https://financialmodelingprep.com/api/v3/profile/" . $postData['symbol'] . "?apikey=" . env('financialmodelingprep_api');
                $getData = callApi($url);
                $slug = strtolower(Text::slug($getData[0]['companyName']));

                $postData['name'] = $getData[0]['companyName'];
                $postData['price'] = $getData[0]['price'];
                $postData['vol_avg'] = $getData[0]['volAvg'];
                $postData['mkt_cap'] = $getData[0]['mktCap'];
                $postData['currency'] = $getData[0]['currency'];
                $postData['exchange'] = $getData[0]['exchange'];
                $postData['exchange_symbol'] = $getData[0]['exchangeShortName'];
                $postData['industry'] = $getData[0]['industry'];
                $postData['website'] = $getData[0]['website'];
                $postData['description'] = $getData[0]['description'];
                $postData['ceo'] = $getData[0]['ceo'];
                $postData['sector'] = $getData[0]['sector'];
                $postData['country'] = $getData[0]['country'];
                $postData['full_time_employees'] = $getData[0]['fullTimeEmployees'];
                $postData['phone'] = $getData[0]['phone'];
                $postData['address'] = $getData[0]['address'];
                $postData['city'] = $getData[0]['city'];
                $postData['state'] = $getData[0]['state'];
                $postData['zip'] = $getData[0]['zip'];
                $postData['logo'] = $getData[0]['image'];
                $postData['ipo_date'] = (!empty($getData[0]['ipoDate']) ? date('Y-m-d', strtotime($getData[0]['ipoDate'])) : null);
                $postData['updated'] = DATE;
                $postData['slug'] = $slug;


                $getData = $this->fetchTable('Stocks')->newEmptyEntity();
                $chkData = $this->fetchTable('Stocks')->patchEntity($getData, $postData, ['validate' => 'OnlyCheck']);
                if ($chkData->getErrors()) {
                    $st = null;
                    foreach ($chkData->getErrors() as $elist) {
                        foreach ($elist as $k => $v); {
                            $st .= "<div class='alert alert-danger'>" . ucwords($v) . "</div>";
                        }
                    }
                    echo $st;
                    exit;
                } else {

                    if ($this->fetchTable('Stocks')->save($chkData)) {
                        echo "<script>$('#save_frm').remove();</script>";
                        echo "<div class='alert alert-success'>Saved</div>";
                        echo "<script> setTimeout(function(){ location.reload(); }, 1000);</script>";
                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Not saved.</div>';
                    }
                }
                exit;
            } else {
                $this->set(compact('post_data'));
            }
        }
    }

    public function users()
    {
        $menu_act = 'users';
        $this->paginate = ['conditions' => ['role' => 2], 'limit' => 200, 'order' => ['created' => 'desc']];
        $data = $this->paginate($this->fetchTable('Users')->find('all'));
        $this->set(compact('data'));
    }


    public function settings()
    {

        $this->set('menu_act', 'settings');
        $postData = $this->request->getData();
        $tbl_data = null;
        if ($this->request->is('ajax') && !empty($this->request->getData())) {

            if (isset($postData['id']) && !empty($postData['id'])) {
                $getBlog = $this->fetchTable('Settings')->get($postData['id']);
                $chkBlog = $this->fetchTable('Settings')->patchEntity($getBlog, $postData, ['validate' => true]);
            } else {
                echo '<div class="alert alert-danger" role="alert"> Not saved.</div>';
                exit;
            }
            if ($chkBlog->getErrors()) {
                $st = null;
                foreach ($chkBlog->getErrors() as $elist) {
                    foreach ($elist as $k => $v); {
                        $st .= "<div class='alert alert-danger'>" . ucwords($v) . "</div>";
                    }
                }
                echo $st;
                exit;
            } else {
                if ($this->fetchTable('Settings')->save($chkBlog)) {
                    $u = SITEURL . "pages/settings";
                    echo '<div class="alert alert-success" role="alert"> Saved.</div>';
                    echo "<script>window.location.href ='" . $u . "'; </script>";
                } else {
                    echo '<div class="alert alert-danger" role="alert"> Not saved.</div>';
                }
            }
            exit;
        }
        $tbl_data = $this->fetchTable('Settings')->findById('1')->firstOrFail();
        $this->set(compact('tbl_data'));
    }
}
