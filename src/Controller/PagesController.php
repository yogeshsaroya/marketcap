<?php

declare(strict_types=1);

namespace App\Controller;

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

        $this->paginate = ['conditions'=>['type'=>'stock'], 'limit' => 100, 'order' => ['market_cap' => 'desc']];
        $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
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
