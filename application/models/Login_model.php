<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{
     public $username;
    public $password;
    public $rememberMe = false;
    private $_identity = null;
    public $errors = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function login()
    {
        if ($this->_identity === null) {
            // CI replacement for UserIdentity
            // $this->_identity = $this->authenticate(
            //     $this->username,
            //     $this->password
            // );
        }

        if ($this->_identity['errorCode'] === 0) { // ERROR_NONE = 0

            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days

            // CI equivalent of Yii::app()->user->login()
            $this->session->set_userdata([
                'user_id'   => $this->_identity['id'],
                'username'  => $this->_identity['username'],
                'logged_in' => true
            ]);

            // Remember me
            if ($duration > 0) {
                set_cookie([
                    'name'   => 'remember_me',
                    'value'  => $this->_identity['id'],
                    'expire' => $duration,
                    'httponly' => true
                ]);
            }

            return true;

        } else {
            return false;
        }
    }

    
    // public function authenticate()
    // {
    //     if (empty($this->errors)) {

    //         $this->_identity = $this->checkIdentity(
    //             $this->username,
    //             $this->password
    //         );

    //         if (!$this->_identity) {
    //             $this->errors['password'] = 'Incorrect username or password.';
    //             return false;
    //         }

    //         return true;
    //     }

    //     return false;
    // }
}
