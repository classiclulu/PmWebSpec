<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CIModHome extends CI_Model {

    public $pkms_path;
    public $ppk;
    public $er;
    public $other;
    public $ppkother;
    public $erother;

    public function __construct() {
        parent::__construct();

        $this->pkms_path = '/global/pkms/data';
        $this->ppk = array('PPK-NIVO', 'PPK-NIVO-MODEL', 'PPK-standard','PPK-ISOP-IPI','PPK-ISOP-NIVO','PPK-ISOP-RELA');
        $this->er = array('ER-safety', 'ER-efficacy', 'ER-safety-efficacy','ER-ISOP-efficacy','ER-ISOP-safety','ER-ISOP-safety-efficacy');
        $this->other = array('TGD', 'PKPD', 'Blank Template', 'Other','PKPD-ISOP');
        $this->ppkother = array('PPK-NIVO', 'PPK-NIVO-MODEL', 'PPK-standard', 'Blank Template', 'TGD', 'PKPD','PKPD-ISOP','PPK-ISOP-IPI','PPK-ISOP-NIVO','PPK-ISOP-RELA');
        $this->erother = array('ER-safety', 'ER-efficacy', 'ER-safety-efficacy', 'TGD', 'PKPD', 'Blank Template','Other','ER-ISOP-efficacy','ER-ISOP-safety','ER-ISOP-safety-efficacy','PKPD-ISOP');
    }

    public function getUserDashboardView() {
        // print "<h1><center>Hello</center></h1>";
    }
}
?>
