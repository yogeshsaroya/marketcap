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

use Cake\I18n\FrozenTime;
use Cake\Utility\Text;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Http\Client;


/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // methods name we can pass here which we want to allow without login
        parent::beforeFilter($event);

        /* https://book.cakephp.org/4/en/controllers/components/authentication.html#AuthComponent::allow */
        $this->Auth->allow(['register', 'resetPassword', 'login', 'backend', 'logout', 'setPassword']);
        //$this->Auth->allow();
        // Form helper https://codethepixel.com/tutorial/cakephp/cakephp-4-common-helpers
        /* https://codethepixel.com/tutorial/cakephp/cakephp-4-find-sort-count */
    }

    public function index()
    {
        $arr = $data = [];
        $data = $this->fetchTable('Portfolios')->find()->where(['user_id' => $this->Auth->User('id')])->contain(['Stocks'])->limit(5000)->all();
        $query = $this->fetchTable('Watchlists')->find('list', ['conditions' => ['user_id' => $this->Auth->User('id')], 'keyField' => 'stock_id', 'valueField' => 'stock_id']);
        $arr = $query->toArray();
        /*
        $query = $this->fetchTable('Portfolios')->find('list', ['conditions' => ['user_id' => $this->Auth->User('id')], 'keyField' => 'stock_id', 'valueField' => 'stock_id']);
        $arr = $query->toArray();
        if (!empty($arr)) {
            try {
                $this->paginate = ['conditions' => ['id IN' => $arr, 'type' => 'stock', 'name !=' => ''], 'limit' => 100, 'order' => ['market_cap' => 'desc']];
                $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
            } catch (\Throwable $th) {
            }
        }
        */
        $this->set(compact('data', 'arr'));
    }


    public function watchlist()
    {
        $data = null;
        $query = $this->fetchTable('Watchlists')->find('list', ['conditions' => ['user_id' => $this->Auth->User('id')], 'keyField' => 'stock_id', 'valueField' => 'stock_id']);
        $arr = $query->toArray();
        if (!empty($arr)) {
            try {
                $this->paginate = ['conditions' => ['id IN' => $arr, 'type' => 'stock', 'name !=' => ''], 'limit' => 100, 'order' => ['market_cap' => 'desc']];
                $data = $this->paginate($this->fetchTable('Stocks')->find('all'));
            } catch (\Throwable $th) {
            }
        }
        $this->set(compact('data', 'arr'));
    }

    public function profile()
    {
        if ($this->request->is('ajax')) {
            if (!empty($this->request->getData())) {
                $postData = $this->request->getData();

                $validate = ['validate' => true];
                if (!empty($postData['password1'])) {
                    $postData['password'] = $postData['password1'];
                } else {
                    $validate = ['validate' => 'OnlyCheck'];
                }


                $getData = $this->fetchTable('Users')->get($postData['id']);
                $chkData = $this->fetchTable('Users')->patchEntity($getData, $postData, $validate);


                if ($chkData->getErrors()) {
                    $st = null;
                    foreach ($chkData->getErrors() as $elist) {
                        foreach ($elist as $k => $v); {
                            $st .= "<div class='alert alert-danger'>" . $v . "</div>";
                        }
                    }
                    echo $st;
                    exit;
                } else {
                    if ($this->fetchTable('Users')->save($chkData)) {
                        echo "<div class='alert alert-success'>Saved</div>";
                        echo "<script> setTimeout(function(){ location.reload(); }, 1000);</script>";
                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Not saved.</div>';
                    }
                }
            }
            exit;
        }

        $profile = $this->fetchTable('Users')->find('all')->where(['Users.id' => $this->Auth->User('id')])->first();
        $this->set(compact('profile'));
    }

    public function addToPortfolio($id = null)
    {
        if (!empty($id)) {
            $data = $this->fetchTable('Stocks')->find('all')->where(['id' => $id])->first();
            $this->set(compact('data'));
        }
    }
    

    public function editPortfolio($id = null )
    {
        if ($this->request->is('ajax')) {

            $postData = $this->request->getData();

        }

        if (!empty($id)) {
            $data = $this->fetchTable('Portfolios')->find('all')->where(['id' => $id])->first();
            ec($data);die;
            $this->set(compact('data'));
        }
    }

    public function updatePortfolio()
    {
        if ($this->request->is('ajax')) {
            if (!empty($this->request->getData())) {
                $postData = $this->request->getData();

                $is_data = $this->fetchTable('Portfolios')->find('all')->where(['user_id' =>$this->Auth->User('id'),'stock_id'=>$postData['stock_id']])->first();
                if(!empty($is_data)){
                    echo '<div class="alert alert-danger" role="alert"> This stock already in your portfolio. Please check at Portfolio section.</div>';
                    exit; 
                }

                if (empty($postData['buy_date'])) {
                    echo '<div class="alert alert-danger" role="alert"> Please entere buy date.</div>';
                    exit;
                } elseif (empty($postData['qty']) && (int)$postData['qty'] <= 0) {
                    echo '<div class="alert alert-danger" role="alert"> Please entere quantity.</div>';
                    exit;
                } elseif (empty($postData['rate']) && floatval($postData['rate']) <= 0) {
                    echo '<div class="alert alert-danger" role="alert"> Please entere but price.</div>';
                    exit;
                } else {
                    $postData['total'] = $postData['qty'] * $postData['rate'];
                    $postData['user_id'] = $this->Auth->User('id');
                    $postData['buy_date'] = date('Y-m-d', strtotime($postData['buy_date']));

                    $getData = $this->fetchTable('Portfolios')->newEmptyEntity();
                    $chkData = $this->fetchTable('Portfolios')->patchEntity($getData, $postData, ['validate' => false]);


                    if ($chkData->getErrors()) {
                        $st = null;
                        foreach ($chkData->getErrors() as $elist) {
                            foreach ($elist as $k => $v); {
                                $st .= "<div class='alert alert-danger'>" . $v . "</div>";
                            }
                        }
                        echo $st;
                        exit;
                    } else {
                        if ($this->fetchTable('Portfolios')->save($chkData)) {
                            echo "<div class='alert alert-success'>Saved</div>";
                            echo "<script> setTimeout(function(){ location.reload(); }, 1000);</script>";
                        } else {
                            echo '<div class="alert alert-danger" role="alert"> Not saved.</div>';
                        }
                    }
                }
            }
            
        }
        exit;
    }



    public function resetPassword($type = null, $id = null)
    {
        if ($this->Auth->User('id') != "") {
            if ($this->request->is('ajax')) {
                $u = SITEURL . "dashboard";
                echo "<script>window.location.href ='" . $u . "'; </script>";
            } else {
                return $this->redirect('/dashboard');
            }
            exit;
        }
        if ($type == 'reset' && !empty($id)) {
            $user = $this->Users->find()->where(['role' => 2, 'reset_password_key' => $id])->first();
            if (!empty($user)) {
                $this->set(compact('user'));
                $this->render('setPassword');
            } else {
                return $this->redirect('/login');
            }
        }

        if ($this->request->is('ajax') && !empty($this->request->getData())) {
            $post_data = $this->request->getData();
            if (empty($post_data['email'])) {
                echo '<div class="alert bg-danger">Please enter email id.</div>';
            } else {
                $user = $this->Users->find()->where(['role' => 2, 'email' => trim(strtolower($post_data['email']))])->first();
                if (!empty($user)) {
                    $expiry = date("Y-m-d H:i:s", strtotime("+1 hours", strtotime(DATE)));
                    $reset_password_key = Security::hash(Text::uuid(), 'sha1', true);
                    $user->reset_password_key = $reset_password_key;
                    $user->reset_password_key_expiry = $expiry;
                    $this->Users->save($user);
                    $link = SITEURL . "reset-password/reset/" . $reset_password_key;
                    $body = '<!doctype html><html><head><meta name="viewport" content="width=device-width, initial-scale=1.0" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Reset your password</title><h1>Hi!</h1><p>To reset your password, please visit the following link</p> <p><a href="' . $link . '">' . $link . '</a></p><p>This link will expire in 1 hour.</p><p>Cheers!</p></head></html>';
                    $msg = $this->_sendEmail($user->email, 'Your password reset request', $body);
                    if (isset($msg['status']) && $msg['status'] == 1) {
                        echo "<script>$('#rst').html('<div class=\"alert bg-light-success\">If an account matching your email exists, then an email was just sent that contains a link that you can use to reset your password. This link will expire in 1 hour. <br><br>If you don\'t receive an email please check your spam folder or try again.</div>');</script>";
                    } else {
                        echo '<div class="alert bg-danger">Please try again after some time</div>';
                    }
                } else {
                    echo '<div class="alert bg-danger">Account Not Found</div>';
                }
            }
            exit;
        }
    }

    public function setPassword()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax') && !empty($this->request->getData())) {
            $post_data = $this->request->getData();

            if (empty($post_data['reset_password_key'])) {
                echo '<div class="alert bg-danger">Error. Please check reset password link.</div>';
            }
            if (empty($post_data['new_password'])) {
                echo '<div class="alert bg-danger">Please enter new password.</div>';
            } elseif (empty($post_data['repeat_password'])) {
                echo '<div class="alert bg-danger">Please repeat password.</div>';
            } elseif ($post_data['new_password'] != $post_data['repeat_password']) {
                echo '<div class="alert bg-danger">New password and confirm password does not match.</div>';
            } else {
                $user = $this->Users->find()->where(['role' => 2, 'reset_password_key' => $post_data['reset_password_key']])->first();
                if (!empty($user)) {
                    $user->reset_password_key = null;
                    $user->reset_password_key_expiry = null;
                    $user->password = $post_data['new_password'];
                    $this->Users->save($user);
                    $str = '<div class="bg-light-success"><span>Password changed successfully! Click here to <a href="' . SITEURL . 'login">Login</a></span></div>';
                    echo "<script>$('#rst').html('$str');</script>";
                } else {
                    echo '<div class="alert bg-danger">Account Not Found</div>';
                }
            }
            exit;
        }
        exit;
    }


    public function register()
    {
        if ($this->Auth->User('id') != "") {
            if ($this->request->is('ajax')) {
                $u = SITEURL . "dashboard";
                echo "<script>window.location.href ='" . $u . "'; </script>";
            } else {
                return $this->redirect('/dashboard');
            }
            exit;
        }

        if ($this->request->is('ajax') && !empty($this->request->getData())) {
            $post_data = $this->request->getData();
            $post_data['role'] = 2;
            $getData = $this->Users->newEmptyEntity();
            $chkData = $this->Users->patchEntity($getData, $post_data, ['validate' => true]);
            if ($chkData->getErrors()) {
                $st = null;
                foreach ($chkData->getErrors() as $elist) {
                    foreach ($elist as $k => $v); {
                        $st .= "<div class='alert bg-danger'>" . $v . "</div>";
                    }
                }
                echo $st;
                exit;
            } else {
                $verify = $this->Users->save($chkData);
                $this->Auth->setUser($verify);
                $q_url = SITEURL . "dashboard";
                echo '<script>$("#login_sbtn").remove(); window.location.href = "' . $q_url . '"</script>';
            }
            exit;
        }
    }

    public function _sendEmail($to = null, $subject = null, $body = null)
    {
        if (!empty($to) && !empty($subject) && !empty($body)) {
            $data = $this->fetchTable('Settings')->findById('1')->firstOrFail();
            if (!empty($data)) {
                if (!empty($data->email_address) && !empty($data->email_password) && !empty($data->email_host) && !empty($data->email_port)) {
                    TransportFactory::setConfig('Manual', [
                        /*'className' => 'Debug', 'auth' => true, */
                        'className' => 'Smtp', 'tls' => true,
                        'port' => $data->email_port, 'host' => $data->email_host, 'username' => $data->email_address, 'password' => $data->email_password
                    ]);
                    $mailer = new Mailer('default');
                    $mailer->setTransport('Manual');
                    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                        try {
                            $res = $mailer
                                ->setEmailFormat('both')
                                ->setFrom([$data->email_address => 'CosmoRecovery Password Reset'])
                                ->setTo($to)
                                ->setSubject($subject)
                                ->deliver($body);
                            $msg = ['status' => 1, 'msg' => 'Email has been sent.'];
                        } catch (\Throwable $th) {
                            $msg = ['status' => 2, 'msg' => 'Email not sent.'];
                        }
                    } else {
                        $msg = ['status' => 2, 'msg' => 'Email address is not valid.'];
                    }
                } else {
                    $msg = ['status' => 2, 'msg' => 'Error'];
                }
            } else {
                $msg = ['status' => 2, 'msg' => 'Error'];
            }
        } else {
            $msg = ['status' => 2, 'msg' => 'Error'];
        }
        return $msg;
    }

    /**
     * REF : https://book.cakephp.org/4/en/controllers/components/authentication.html#manually-logging-users-in
     */
    public function login()
    {

        if ($this->Auth->User('id') != "") {
            if ($this->request->is('ajax')) {
                $u = SITEURL . "dashboard";
                echo "<script>window.location.href ='" . $u . "'; </script>";
                exit;
            } else {
                $this->redirect('/dashboard');
            }
        }

        if ($this->request->is('ajax') && !empty($this->request->getData())) {
            $post_data = $this->request->getData();
            if (empty($post_data['email'])) {
                echo '<div class="alert alert-danger">Please enter email id.</div>';
            } elseif (empty($post_data['password'])) {
                echo '<div class="alert alert-danger">Please enter password.</div>';
            } else {
                $pwd = trim($post_data['password']);
                $verify = $this->fetchTable('Users')->find('all')
                    ->where(['Users.role' => 2, 'Users.email' => trim(strtolower($post_data['email']))])
                    ->first();
                if (!empty($verify)) {
                    if (password_verify($pwd, $verify->password)) {
                        $this->Auth->setUser($verify);
                        $q_url = SITEURL . "dashboard";
                        echo '<script>window.location.href = "' . $q_url . '"</script>';
                        exit;
                    } else {
                        echo '<div class="alert alert-danger">Password is invalid</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">User id or password is incorrect</div>';
                }
            }
            exit;
        }
    }

    /**
     * Admin login page
     */
    public function backend()
    {
        $this->viewBuilder()->setLayout('backend_login');
        $user_data = null;
        $this->set(compact('user_data'));

        if ($this->Auth->User('id') != "") {
            if ($this->request->is('ajax')) {
                $u = SITEURL . "pages";
                echo "<script>window.location.href ='" . $u . "'; </script>";
                exit;
            } else {
                $this->redirect('/pages');
            }
        }

        if ($this->request->is('ajax') && !empty($this->request->getData())) {

            $post_data = $this->request->getData();

            if (empty($post_data['email'])) {
                echo $s;
                echo '<div class="alert alert-danger">Please enter email id.</div>';
            } elseif (empty($post_data['password'])) {
                echo $s;
                echo '<div class="alert alert-danger">Please enter password.</div>';
            } else {
                $pwd = trim($post_data['password']);

                $verify = $this->fetchTable('Users')->find('all')
                    ->where(['Users.role' => 1, 'Users.email' => trim(strtolower($post_data['email']))])
                    ->first();
                if (!empty($verify)) {
                    if (password_verify($pwd, $verify->password)) {
                        $this->Auth->setUser($verify);
                        $q_url = SITEURL . "pages";
                        echo '<script>$("#login_sbtn").remove();window.location.href = "' . $q_url . '"</script>';
                        exit;
                    } else {

                        echo '<div class="alert alert-danger">Password is invalid</div>';
                    }
                } else {

                    echo '<div class="alert alert-danger">User id or password is incorrect</div>';
                }
            }
            exit;
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->redirect('/');
    }
}
