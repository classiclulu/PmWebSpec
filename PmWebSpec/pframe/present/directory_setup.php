<?php
$user_id_session =& get_instance();
$user_id_session->load->model('CIModSession');
$users_id = $user_id_session->CIModSession->checkIsSessionExist();

if ($users_id == 0) {
	echo "Please Login";
	die();
}
$user_name = $this->session->user_details;
$url = base_url();
if ($user_name[0]['user_id'] == NULL) {
	if (!(strpos($url, 'localhost') != 0)) {
		redirect(base_url('error/unauthorized'));
	}
}

$user_name = $this->session->user_details;
//print_r(expression)
$full_name = $user_name[0]['first_name']." ".$user_name[0]['last_name'];

?>
	<style type="text/css">
        .col-60 {
            float: left;
            width: 50%;
            margin-top: 6px;
            clear: both;
        }

        .col-40 {
            float: right;
            width: 50%;
            margin-top: 6px;
        }

        .title-info {
            width: 85%;
            margin: 0 auto;
            padding-left: 25px;
            padding-top: 20px;
            padding-bottom: 20px;
            padding-right: 25px;
            overflow-x: auto;
        }

        input[type=text], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }
        
        .container12 {
        	border-radius: 5px;
        	background-color: rgba(254,220,202,0.8);
        	padding: 20px;
        }
    </style>

	<script type="text/javascript" src=<?php echo base_url('assets/js/disable_field.js') ?>></script>
    <script type="text/javascript">
        $(function () {
            $("#yesModels").change(function () {
                if ($(this).val() == "Yes") {
                    $("#group").removeAttr("disabled");
                    $("#group").focus();
                    $("#users").removeAttr("disabled");
                } else {
                    $("#group").attr("disabled", "disabled");
                    $("#users").attr("disabled", "disabled");
                }
            });
        });
    </script>

	<div class="title-info">
		<?php 
    		if(isset($msg) && !empty($msg)) {
                echo "<div class='container12'><p>".$msg['msg']."</p></div><br/>";
    		}
		?>
		<form method="post" action="<?php echo base_url('admin/directory/setup/request'); ?>" enctype="multipart/form-data">
			<fieldset>
				<legend>PKMS Directory Structure Request Form</legend>

				<div class="col-60 required"><label>(Level 1) Therapeutic Area</label></div>

				<div class="col-40 ">
    				<select name="level1">
    					<option> CA </option>
    					<option> AI </option>
    					<option> CN </option>
    					<option> CV </option>
    					<option> IM </option>
    					<option> MB </option>
    					<option> misc </option>
    				</select>
				</div>

				<div class="col-60 required">
					<label>(Level 2) Compound# or BMS/BMT# or Pearl drug name or "nds"</label>
               		<div class="helptip">?
               			<span class="helptiptext">If a combination of compounds is being studied, choose one of the compounds for the directory structure, and ask the Data Science programmer to create a structure for the other compound(s), but to enter into that structure only 0readme.txt files saying that the work involves multiple compounds, and also include the name of the directory structure where the work is being done on the combination.</span>
               		</div>
				</div>

               	<div class="col-40"><input id="level2" type="text" name="level2" placeholder="e.g. 209" required="required" value="<?php if (isset($level2) && $msg['level2'] != '') {echo $level2;} ?>" /></div>

               	<div class="col-60 required">
               		<label>(Level 3) Study level</label><div class="helptip">?<span class="helptiptext">If single protocol, please enter the protocol number (example: 001); If it's a project, please enter the project description (example: flat-dosing); If it's non-study-specific, please enter nss (Non-study-specific is intended for work that precedes specific studies, or is not relevant to specific studies); If you are not sure what to enter, please reach out to Data Science team.</span></div>
               	</div>

               	<div class="col-40">
                  <input type="text" id="level3" name="level3" pattern="[A-Za-z0-9-]+" title="Directory name can only contain letters, numbers and hyphens" placeholder="e.g. 001, flat-dosing" required="required" value="<?php if (isset($level3) && $msg['level3'] != '') {echo $level3;} ?>" />
                </div>

                  <div class="col-60 required">
                  <label>(Level 3) Study level directory description</label><div class="helptip">?<span class="helptiptext">This is only a description, and not an analysis plan. It will be copied into a 0readme.txt file that is at the same level as level 3 directories. It should provide enough information so that readers have an idea of the purpose for this directory. If level 3 directory already exists, please type level 3 directory already exists.</span></div>
                </div>

                <div class="col-40">
                  <input type="text" id="level3desc" name="level3desc" required="required" value="<?php if (isset($level3desc) && $msg['level3desc'] != '') {echo $level3desc;} ?>" />
                </div>

               	<div class="col-60 required">
               		<label>(Level 5) Analysis Directory Name (Lowercase preferred)</label>
               		<div class="helptip">?
               			<span class="helptiptext">Only letter, number and hyphen are allowed in the directory name. Examples: er-os, er-efficacy, ppk.</span>
               		</div>
               	</div>

               	<div class="col-40">
               		<input id="level5" type="text" name="level5" pattern="[A-Za-z0-9- ,]+" title="Directory name can only contain letters, numbers and hyphens. If multiple directories, seprated by comma" placeholder="e.g. ppk, er-efficacy, er-safety" required="required" value="<?php if (isset($level5) && $msg['level5'] != '') {echo $level5;} ?>" />
               	</div>

               	<div class="col-60 required">
               		<label>(Level 5) Analysis Directory Description (One description per analysis directory) </label>
               		<div class="helptip">?
               			<span class="helptiptext">This is only a description, and not an analysis plan. It will be copied into a 0readme.txt file that is at the same level as all the analysis directories. It should provide enough information so that readers have an idea of the purpose for each analysis.</span>
               		</div>
               	</div>

               	<div class="col-40"><input id="description" type="text" name="description" required="required" value="<?php if (isset($description) && $msg['description'] != '') {echo $description;} ?>" /></div>

               	<div class="col-60 required"><label>Requester's Name</label></div>

               	<div class="col-40"><input type="text" id="requester" name="requester" value="<?php echo $full_name; ?>" required="required" /></div>

                <input type="hidden" id="email" name="user_email" value="<?php echo $email; ?>" />

               	<div class="col-60 required">
               		<label>Does read access need to be restricted to only certain users?</label>
               		<div class="helptip">?
               			<span class="helptiptext">For example, this would be true when doing an interim analysis of unblinded data from a blinded study. If not, please enter No and ignore the rest of the form.</span>
               		</div>
               	</div>

				<div class="col-40">
    				<select name="access" id="yesModels">
    					<option value = "No"> No </option>
    					<option value = "Yes"> Yes </option>					
    				</select>
				</div>

				<div class="col-100">
					<hr/><label>Optional, only fill the section below if the answer to the question above is Yes</label>
				</div>

				<div class="col-60">
					<label>If an existing security group should be used, enter its name: </label>
				</div>

               	<div class="col-40">
               		<input type="text" id="group" disabled="disabled" name="group" value="<?php if(isset($group)) echo $group; ?>" />
               	</div>

               	<div class="col-60">
               		<label>Otherwise, Data Sciences will have a security group set up for this study;
               		Informatics may require one to two weeks to complete the task. Please enter desired group member BMS username separated by colon</label>
               	</div>

               	<div class="col-40">
               		<input type="text" id="users" name="users" disabled="disabled" pattern="[A-Za-z :0-9]+" title="Please separate BMS ID by colon" value="<?php if(isset($users)) echo $users; ?>"/>
               	</div>
         	</fieldset>
			
			<br/>
			
			<input class="button" type="submit" value="Create Form" name="submitform" />
			<input class="button" type="reset" value="Clear Form" name="submitform" />
		</form>
	</div>



