<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CIAdmin extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('CIModUser', 'MUser');
        $this->load->model('CIModSession', 'MSession');
        $this->load->helper('pdf');
        $this->load->helper('import_database');
    }

	public function index(){
		$this->load->view('404');
	}

    public function formula_delete(){
        //echo "welcome";
        $id = $_GET['id'];
        //echo $id;
        $where_cond=array('sr_no'=> $id);
        $data = $this->MUser->deleteformula("formula",$where_cond);
        echo '<script> alert("Formula Deleted!"); window.location.href = "'.base_url("admin/manage/users").'";</script>'; 
    }

     public function formula_update()
     {
        $_GET['algorithm']= str_replace("/*/","+",$_GET['algorithm']);
        $data =  array(
                'field' => $_GET['field'],
                'algorithm' => $_GET['algorithm'],
                    );
        
        //echo $id;
        $where_cond=array('sr_no'=> $_GET['id']);
        $this->MUser->updateformula("formula", $where_cond, $data);
        echo '<script> alert("Formula Updated!"); window.location.href = "'.base_url("admin/manage/users").'";</script>'; 
    }

    public function Access($action = NULL, $operation = NULL) {
        $this->MSession->setSessionDetails();
        //echo $this->input->post('manage');exit;
        if(!empty($this->input->post('manage'))) {
            $selection = $this->input->post('manage');
            $selection = ucwords(strtolower($selection));
            $selected = str_replace( array(' ',"/","."), array('',"",""), $selection );
            $this->session->set_userdata('selection', $selected);
            $this->create($action, $selected);
            //echo $action;exit;
        } else {
            $callToFunction = $action;
            foreach (CLASS_ACTION_MAPPING as $index => $firstLevel) {
                foreach ($firstLevel as $key => $secondLevel) {
                    if ($callToFunction == $secondLevel) {
                        $funcName = "Action" . $secondLevel;
                        $this->$funcName($operation); 
                    }
                }
            }
        }
    }

public function Create($action=NULL, $operation=NULL) {
    $callToFunction = $action;

    foreach (CLASS_ACTION_MAPPING as $index => $firstLevel) {
        //print_r(CLASS_ACTION_MAPPING);exit;
        foreach ($firstLevel as $key => $secondLevel) {
            //print_r($secondLevel);exit;
            if($callToFunction == $secondLevel) {
                $funcName = "Action".$secondLevel;
                $this->$funcName($operation);
            }
        }
    }
}

    public function ActionDirectorySetup($param = NULL) {
        $data['param']      = strtolower($param);
        $data['selected']   = $this->session->userdata('selection');
        $data['full_name']  = $this->session->userdata('user_details')[$this->MUser->user_id]['first_name']. " ". $this->session->userdata('user_details')[$this->MUser->user_id]['last_name'];

        if (strtolower($param) == "dirreq") {
            $all_post_data          = $this->input->post();
            $data['level1']         = $all_post_data['level1'];
            $data['level2']         = $all_post_data['level2'];
            $data['level3']         = $all_post_data['level3'];
            $data['level3desc']      = $all_post_data['level3desc'];
            $data['level5']         = $all_post_data['level5'];
            $data['description']    = $all_post_data['description'];
            $data['requester']      = $all_post_data['requester'];
            $data['access']         = $all_post_data['access'];
            $data['group']          = $all_post_data['group'];
            $data['users']          = $all_post_data['users'];

            if((empty($all_post_data)) || (empty($data['level1'])) || (empty($data['level2'])) || (empty($data['level3'])) || (empty($data['level3desc'])) || (empty($data['level5'])) || (empty($data['level5'])) || (empty($data['description'] )) || (empty($data['full_name']))) {
                echo "<script>
                alert('Your session got expired.');
                window.location.href='".base_url()."';
                </script>";
                exit();
            }

            if(!is_dir("/tmp/".$this->session->userdata('user_id')."/pdf")) {
                mkdir("/tmp/".$this->session->userdata('user_id')."/pdf", 0775, TRUE);
            }

            $file_path                      = "/tmp/".$this->session->userdata('user_id')."/pdf/PKMS-Directory-Structure-Request-Form.pdf";
            $all_post_data['file_path']     = $file_path;
            $all_post_data['user_email']    = $this->session->userdata('user_details')[$this->MUser->user_id]['email_address'];
            initiateDirectorySetup($all_post_data);
            $data['msg'] = $this->MUser->notifyViaEmail($all_post_data);
        } else {
            $data['msg'] = NULL;
        }

        $this->load->view('inc/h1.inc.php');
        $this->load->view('directory_setup', $data);
    }

    public function ActionManageUsers($param=NULL) {
        $data['param'] = strtolower($param);
        //print_r($data['param']);exit;
        $data['selected'] = $this->session->userdata('selection');
		$user_name = $this->session->user_details;
        //print_r($user_name);exit;
		$created_by_session = $user_name[0]['user_id'];
        //print_r($created_by_session);exit;
		if($created_by_session == NULL)
		{
			$created_by_session = "God";
		}
        if(strtolower($param) == "viewmodifytemplate") {
            $act_type = $this->input->post('choose_temp');
            $data['action_type'] = strtolower(str_replace(" ", "", $act_type));
        }
        if(strtolower($param) == "userdate") {
            //echo "Hureee";
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $where_cond=('created_date BETWEEN  "'.$start_date.'" AND "'.$end_date.'" ');
           // $data = $this->MUser->deleteData("dsstruct",$where_cond);
            $data = $this->MUser->GetUserData("users",$where_cond);

            if($data==null) {
                echo "No records found"; exit;
            } 
            $i=1;
            foreach ($data as $value) {
               echo "$i - ".$value;echo "<br/>";
               $i++;
            }
            //echo "Total - " .$i;
            exit();
           // print_r($data);exit;

                
            
           //print_r($data);
          // return $data;
        }

        if(strtolower($param) == "useradd") {

			//echo $user = $this->input->post('userid');exit;
			$user_info = $this->MUser->getUserIds('users', $this->input->post('userid'));
			//print_r($user_info);exit;

			if(!isset($user_info)) {
				//date_default_timezone_set('US/Eastern');
				$created_date = date("Y-m-d H:i:s");
				$data = [
						'user_id' => $this->input->post('userid'),
						'first_name' => $this->input->post('fname'),
						'last_name' => $this->input->post('lname'),
						'email_address' => $this->input->post('email'),
						'created_by' => $created_by_session,//$_SESSION["username"];
						'created_date' => $created_date,
						'last_updated_date' => $created_date,
						'last_updated_by' => $created_by_session,
				];
				$data['msg'] = $this->MUser->saveData('users', $data);
			}
            else
            {
                $created_date = date("Y-m-d H:i:s");
                $data = array(
                'isactive' => '1',
                'last_updated_date' => $created_date,
                );

                $condition_arr=array('user_id'=> $this->input->post('userid'));
                $this->MUser->updateData("users",$condition_arr,$data);
            }
            
			//date_default_timezone_set('US/Eastern');
			$created_date = date("Y-m-d H:i:s");
				$data = [
				'user_id' => $this->input->post('userid'),
				'role_name' => $this->input->post('role'),
				'created_by' => $created_by_session,
				'created_date' => $created_date,
				'last_modified_date' => $created_date,
				'last_modified_by' => $created_by_session,
				];
				$this->MUser->saveData('user_role_mapping',$data);
            ?><script>alert ("New User Is Added")</script><?php
        } else if(strtolower($param) == "userupdate") {
            $data = [];
            $data['userid'] = $_POST['userid'];
            $data['fname'] = $_POST['fname'];
            $data['lname'] = $_POST['lname'];
            $data['email'] = $_POST['email'];
            $data['existing_role'] = $_POST['existing_role'];
            $data['new_role'] = $_POST['new_role'];
            //print_r($date);exit;
            $data['msg'] = $this->MUser->saveUpdatedUserData($data);
            ?><script>alert ("User Updated!");</script><?php

        }else if(strtolower($param)=="removevariable") {
			$type = $_POST['temptomodify2'];
			$remove_var = $_POST['removevariable'];
			//print_r($data_info);exit;

			$where_cond=array('SpecType'=> $type, 'var_name'=> $remove_var);
			$data = $this->MUser->deleteData("dsstruct",$where_cond);

			if($data == 1) {
				echo '<script>alert("Removed Variable!")</script>';
			}
			//$this->load->view('admin/manage/view/remove/user', $data);
		}
		else if(strtolower($param)=="removeflag") {
			$type = $_POST['temptomodify2'];
			$remove_var = $_POST['removeflag'];
			//print_r($data_info);exit;

			$where_cond=array('SpecType'=> $type, 'FlagNum'=> $remove_var);
			$data = $this->MUser->deleteData("dsflag",$where_cond);

			if($data == 1) {
				echo '<script>alert("Removed Flag!")</script>';
			}
			//$this->load->view('admin/manage/view/remove/user', $data);
		}

        else if(strtolower($param)=="removeuserandrole") {
         $data_info['user_id'] = $_POST['useridrm'];
         $data_info['role'] = $_POST['existing_role'];
           //print_r($data_info);exit;
         $data = $this->MUser->removeUseAndRole($data_info);
         if($data == 1) {
            echo '<script>alert("User Removed!")</script>';
        }
             //$this->load->view('admin/manage/view/remove/user', $data);
    } else if(strtolower($param)=="rolemanage") {
        if($_POST['submitRole']=="Create New") {
			//date_default_timezone_set('US/Eastern');
            $modifieddate = date("Y-m-d H:i:s");
        $modifiedby= $created_by_session;//$_SESSION["username"];
        $newrole=$_POST["rolename"];
        $data = array(
            'role_name' => $newrole,
            'role_description' => $_POST["roledescribe"],
            'created_by' => $modifiedby,
            'created_date' => $modifieddate,
            'last_modified_by' =>  $modifiedby,
            'last_modified_date' => $modifieddate,
        );
        $this->MUser->saveData("roles",$data);
        $screens = $_POST["rolescreens"];
        //insert screens
		$modifiedby = $created_by_session;
        $screenData=[];
        $i=0;
        foreach($screens as $value) {
         $screenData[$i]["role_name"]= $newrole;
         $screenData[$i]["screen_name"]=$value;
         $screenData[$i][ "created_by"]=$modifiedby;
         $screenData[$i]["created_date"]=$modifieddate;
         $screenData[$i]["last_modified_by"]=$modifiedby;
         $screenData[$i]["last_modified_date"]=$modifieddate;

         $i++;
     }
         $result = $this->MUser->saveMultipleData("role_screen_mapping",$screenData);
         echo '<script> alert("Role-screen has been added!"); window.location.href = "'.base_url("admin/manage/users").'";</script>'; 
     } else if($_POST['submitRole']=="Update"){
        $updaterole = $_POST["rolename"];
        $updateroledesc = $_POST["roledescribe"];
        $modifiedby = $_SESSION["username"];
        //date_default_timezone_set('US/Eastern');
        $modifieddate = date("Y-m-d H:i:s");
        $screens = $_POST["rolescreens"];
        //update roles
        $data = array(
            'role_description' => $_POST["roledescribe"],
            'last_modified_date' => $modifieddate,
            'last_modified_by' =>  $modifiedby,
        );
        $condition_arr=array('role_name'=> $updaterole);
        $this->MUser->updateData("roles",$condition_arr,$data);

        //update screens
        //first remove existing screens
        $where_cond=array('role_name'=> $updaterole);
        $this->MUser->deleteData("role_screen_mapping",$where_cond);
        //add updated screens
			$modifiedby = $created_by_session;//$_SESSION["username"];
        $screenData=[];
        $i=0;
        foreach($screens as $value) {
         $screenData[$i]["role_name"]= $updaterole;
         $screenData[$i]["screen_name"]=$value;
         $screenData[$i][ "created_by"]=$modifiedby;
         $screenData[$i]["created_date"]=$modifieddate;
         $screenData[$i]["last_modified_by"]=$modifiedby;
         $screenData[$i]["last_modified_date"]=$modifieddate;

         $i++;
     }
//print_r($screenData);exit;
             $result = $this->MUser->saveMultipleData("role_screen_mapping", $screenData);
             echo '<script> alert("Role-screen has been updated!"); window.location.href = "'.base_url("admin/manage/users").'";</script>';
         }            
     } else if(strtolower($param)=="rolemanage") {
			//date_default_timezone_set('US/Eastern');
        $modifieddate=date("Y-m-d H:i:s");
            $modifiedby= $created_by_session;//$_SESSION["username"];
            $newrole=$_POST["rolename"];
            $data = array(
                'role_name' => $newrole,
                'role_description' => $_POST["roledescribe"],
                'created_by' => $modifiedby,
                'created_date' => $modifieddate,
                'last_modified_by' =>  $modifiedby,
            );
        $this->MUser->saveData("roles",$data);
        $screens = $_POST["rolescreens"];
			$modifiedby = $created_by_session;//$_SESSION["username"];
        //insert screens
        $screenData=[];
        $i=0;
        foreach($screens as $value) {
           $screenData[$i]["role_name"]= $newrole;
           $screenData[$i]["screen_name"]=$value;
           $screenData[$i][ "created_by"]=$modifiedby;
           $screenData[$i]["created_date"]=$modifieddate;
           $screenData[$i]["last_modified_by"]=$modifiedby;
           $screenData[$i]["last_modified_date"]=$modifieddate;

           $i++;
        }
                   $result = $this->MUser->saveMultipleData("role_screen_mapping",$screenData);

                   echo '<script> alert("Role-screen has been added!"); window.location.href = "'.base_url("admin/manage/users").'";</script>';             
               } else if(strtolower($param)=="updateremovespec") {
                $specid = $_POST["specid2"];
            $modifiedby = $created_by_session;//$_SESSION["username"];
            $modifieddate =  date("Y-m-d H:i:s");
            $condition=array("spec_id" => $specid);
            $update=array(
                "removed" => 1, 
                "removed_by" => $modifiedby,
                "removed_date" => $modifieddate
            );
            $this->MUser->updateAWSData("user_spec",$condition,$update);



            echo '<script> alert("Specification Is Removed!"); window.location.href = "'.base_url("admin/manage/users").'";</script>';
        } else if(strtolower($param)=="updateunapprovespec") {
            $specid = $_POST["specid2"];
            $modifiedby = $created_by_session;//$_SESSION["username"];
            $modifieddate =  date("Y-m-d H:i:s");
            $condition=array("spec_id" => $specid);
            $update=array(
                "approved" => 0, 
                "approved_by" => ''
            );
            $this->MUser->updateAWSData("user_spec",$condition,$update);
            echo '<script> alert("Specification Is Unapproved!"); window.location.href = "'.base_url("admin/manage/users").'";</script>';
        } else if(strtolower($param)=="addformula") {
                
                 $data = array(
                    'field' =>  $_POST["field"],
                    'algorithm' => $_POST["algorithm"],
                );
 
            $this->MUser->saveAWSData("formula",$data);
            echo '<script> alert("Algorithm Added!"); window.location.href = "'.base_url("admin/manage/users").'";</script>';
        } else if(strtolower($param)=="addtemplate") {
           $data = array(
            'hostname' =>  $_POST["hostname"],
            'username' => $_POST["username"],
            'pwd' => $_POST["pwd"],
            'db' => $_POST["db"],
            'file' => $_POST["file"],
        );
           import_database($data);
        }

         else if (strtolower($param)=="approvespec") {
            $spec_id = $this->input->post('spec_id');
            $img = $this->input->post('digital');
            $check = $this->MUser->checkApprove($spec_id);
            
            if($check != '1') {
                $condition = array(
                    //'approved' => '0',
                    'spec_id' => $spec_id,
                );
                $approved_by = $user_name[0]['first_name']. " ". $user_name[0]['last_name'];
                $data =array(
                    'approved' => '1',
                    'approved_by' => $approved_by,
                );
                $this->MUser->updateAWSData('user_spec',$condition,$data);

                $data = array(
                    'spec_id' => $spec_id,
                    'image' => $img,
                );
                $this->MUser->saveAWSData('signature', $data);
                echo '<script> alert("The specification is approved!"); window.location.href = "'.base_url("home").'";</script>';
            } else {
                echo '<script> alert("Spec already approved!"); window.location.href = "'.base_url("home").'";</script>';
            }
            

        } else if(strtolower($param) == 'getsignature') {
             $spec_id = $this->input->post('spec_id');
             $data = $this->MUser->getSSignature($spec_id);
             //print_r($data);exit;
             echo '<img src="'.$data['image'].'" alt="The Spec Approved"/>';
             exit;

        } 

        $this->load->view('inc/h1.inc.php');
        $this->load->view('manage_users', $data);
	}

    public function getusers()
    {
        if($_POST["status"] == '1')
        {
            $status = 'Active';
            if (!empty($_POST["fromdate"])) 
            {
                $_POST["todate"] = date('Y-m-d H:i:s', strtotime($_POST["todate"] . ' +1 day'));
                $where_cond=('created_date BETWEEN  "'.$_POST["fromdate"].'" AND "'.$_POST["todate"].'" AND isactive="'.$_POST["status"].'"'); 
            }
            else{
                $where_cond=('isactive="'.$_POST["status"].'"');
            }
        }
        else
        {
            $status = 'De-active';
            if (!empty($_POST["fromdate"])) 
            {
                $_POST["todate"] = date('Y-m-d H:i:s', strtotime($_POST["todate"] . ' +1 day'));
                $where_cond=('last_updated_date BETWEEN  "'.$_POST["fromdate"].'" AND "'.$_POST["todate"].'" AND isactive="'.$_POST["status"].'"');
            }
            else{
                $where_cond=('isactive="'.$_POST["status"].'"');
            }
        }

        $data = $this->MUser->GetUserData("users",$where_cond);

        if(!empty($data))
        {
            if($_POST["status"] == '1')
            {
                $status = 'Active';
            }
            else
            {
                $status = 'De-active';
            }
            $html = '';
            foreach ($data as $key => $value) 
            {
                $html .='<tr>
                            <td>'.$value['user_id'].'</td>
                            <td>'.$value['first_name'].' '.$value['last_name'].'</td>
                            <td>'.$value['created_date'].'</td>
                            <td>'.$value['last_updated_by'].'</td>
                            <td>'.$status.'</td>
                        </tr>';
            }
        }
        else
        {
            $html ='<tr>
                        <td>Data Not Found</td>
                    </tr>';
        }
        echo $html;
    }

    public function getuserscount()
    {
        $userdata = $this->MUser->getusercount();

        if (!empty($_POST["fromdate"])) 
        {
            $_POST["todate"] = date('Y-m-d H:i:s', strtotime($_POST["todate"] . ' +1 day'));
            $where_cond=('created_date BETWEEN  "'.$_POST["fromdate"].'" AND "'.$_POST["todate"].'" AND isactive="1"'); 
        }
        else{
            $where_cond=('isactive="1"');
        }
        
        if (!empty($_POST["fromdate"])) 
        {
            $_POST["todate"] = date('Y-m-d H:i:s', strtotime($_POST["todate"] . ' +1 day'));
            $where_cond1=('last_updated_date BETWEEN  "'.$_POST["fromdate"].'" AND "'.$_POST["todate"].'" AND isactive="0"');
        }
        else{
            $where_cond1=('isactive="0"');
        }

        $dataactive = $this->MUser->GetUserData("users",$where_cond);
        $datadeactive = $this->MUser->GetUserData("users",$where_cond1);

        $activeusercount = count($dataactive);
        $deactiveusercount = count($datadeactive);
        $all = $activeusercount + $deactiveusercount;

        $html = '';
        $html .='<tr>
                        <td>'.$all.'</td>
                        <td>'.$activeusercount.'</td>
                        <td>'.$deactiveusercount.'</td>
                    </tr>';
        echo $html;
    }

    public function update_formula()
    {
        $data =  array(
                'field' => $_POST['fieldupdate'],
                'algorithm' => $_POST['algorithmupdate'],
                    );
        $where_cond=array('sr_no'=> $_POST['srnoupdate']);
        $this->MUser->updateformula("formula", $where_cond, $data);
        echo '<script> alert("Formula Updated!"); window.location.href = "'.base_url("admin/manage/users").'";</script>'; 
    }
}