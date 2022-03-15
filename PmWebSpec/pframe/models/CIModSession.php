<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CIModSession extends CI_Model {
    protected $session_user;

    public function __construct() {
        parent::__construct();

        $this->load->model('CIModUser', 'MUser');

        $this->setCookieDetails();
        $this->setBrowserCompatibility();
    }

    public function setCookieDetails()
    {
        $url = base_url();
        if(strpos($url, 'localhost') != NULL) {
            $this->session->set_userdata('user_id', 'chenl125');
        }
        else
        {
            $cookie_params = $_COOKIE;
            $this->session->set_userdata('user_id', $cookie_params['sm_user']);
        }

        foreach(getallheaders() as $header_name => $header_value) {
            if('SMUSER' == $header_name) {
                $this->session->set_userdata('user_id', $header_value);
                $this->session_user = $header_value;

            }
        }
    }

    public function setBrowserCompatibility() {
        $browser = $_SERVER['HTTP_USER_AGENT'];

        if( strpos($browser,'Chrome') === false && strpos($browser,'Edge') === false ) {
            $this->session->unset_userdata('username');
            die('Your browser is not supported. Please use Chrome or Edge!');
        }
    }

    public function checkIsSessionExist() {
        if(empty($this->session->userdata('user_id'))) {
            return 0;
        }
        return 1;
    }

    public function setSessionDetails() {
        // Functions
        // session_reset() ;
        $user_access = $this->MUser->getUserAccessDataByUserName();
        // Full Name
        $user_details = $this->MUser->getUserDetailsByUserId();
        // Super Users
        $programmers = $this->MUser->getAllSuperUserDetails();
        
        $this->session->set_userdata('user_access', $user_access);
        $this->session->set_userdata('user_details', $user_details);
        $this->session->set_userdata('programmers', $programmers);
        $_SESSION['LAST_ACTIVITY'] = time();
        ini_set('session.gc_maxlifetime',14400);
    }
}
?>