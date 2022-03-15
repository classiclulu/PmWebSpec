<?php
    session_start();

    if (! isset($_SESSION['username'])) {
        die("Please login");
    }

    if (! in_array('Download', $_SESSION['functions'])) {
        echo '<a href="home.php">';
        die("You don't have the rights, return to home page");
    }

    if (! isset($_POST['download1'])) {
        echo '<a href="home.php">';
        die("Please select the specification id and version before proceed!");
    }

    // get queries from database
    /*
    * $specid = $_SESSION["specid"];
    * $selected_version = $_SESSION["versionid"];
    */

    $specid = $_POST["spec_id"];
    $selected_version = $_POST["version_id"];

    // run query to get information from spec_general table
    include 'web_based_spec_connect.php';
    include 'mysqli_queries.php';

    list ($title, $project_name, $pk_scientist, $pm_scientist, $statistician, $ds_programmer, $versionid, $moddate) = spec_query($db, $specid, $selected_version);
    list ($dataset_name, $dataset_label, $dataset_descriptor, $dataset_multiple_record, $dataset_criteria, $dataset_sort, $dataset_path, $dataset_due_date, $dataset_dev_path, $dataset_qc_path) = dataset_query($db, $specid, $selected_version);
    
    $histarray = hist_query($db, $specid);
    $pkarray = pk_query($db, $specid, $selected_version);
    $clinarray = clinical_query($db, $specid, $selected_version);
    $pkmsarray = pkms_query($db, $specid, $selected_version);
    $structarray = struct_query2($db, $specid, $selected_version);
    $derivearray = derive_query($db, $specid, $selected_version);
    $missings = missing_query($db, $specid, $selected_version);
    $flagarray = flag_query($db, $specid, $selected_version);
    $confarray = conf_query($db, $specid, $selected_version);

    // prepare PDF file
    require_once ('./tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

    // set header and footer fonts
    $pdf->setHeaderFont( Array(
                            PDF_FONT_NAME_MAIN,
                            '',
                            PDF_FONT_SIZE_MAIN
                       ));
    
    $pdf->setFooterFont( Array(
                            PDF_FONT_NAME_DATA,
                            '',
                            PDF_FONT_SIZE_DATA
                        ));


    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->SetCreator(PDF_CREATOR);

    // Front page
    $pdf->AddPage('L');
    $pdf->Write(0, 'Pharmacometric Analysis Dataset Specification', '', 0, 'C', true, 0, false, false, 0);

    $html = '
    	<br><br><br><br><br>
    	<h1 align="center">' . $title . '</h1>
    	<h2 align="center">Project: ' . $project_name . '</h2>
    	<h3 align="center">Version: ' . $versionid . '</h3>
    	<h3 align="center">Modification Date: ' . $moddate . '</h3>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Accountable team members
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

    $html = '
            <h4><b>Accountable Team Members:</b></h4>
            <table id=team_table border="1" cellspacing="0" cellpadding="2">
        		<tr>
        			<th style="width: 30%;">Role/Department</th>
        			<th style="width: 20%;">Designee</th>
        			<th style="width: 20%;">Accountable for Section</th>
        		</tr>
    
        		<tr>
        			<td>PK Scientist (CP&P)</td>
        			<td>' . $pk_scientist . '</td>
        			<td>2.1</td>
        		</tr>
    
        		<tr>
        			<td>Pharmacometric Scientist (CP&P)</td>
        			<td>' . $pm_scientist . '</td>
        			<td>1, 2, 3, 4</td>
        		</tr>
    
        		<tr>
        			<td>Statistician and/or Programmer (GBS)</td>
        			<td>' . $statistician . '</td>
        			<td>2.2</td>
        		</tr>
    
        		<tr>
        			<td>Programmer (GBS or CP&P)</td>
        			<td>' . $ds_programmer . '</td>
        			<td>2.4, 2.5, 4.4</td>
        		</tr>
        	</table>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Revision history
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

    $html_header = '
            <h4><b>Request Revision History:</b></h4>
            <table id=history_table border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <th style="width: 10%;">Version </th>
                    <th style="width: 15%;">Date </th>
                    <th style="width: 15%;">Revised by </th>
                    <th style="width: 60%;">Changes Made </th>
                </tr>';

    $html = '';

    foreach ($histarray as $arr) {
        $html .= '<tr>
                    <td>' . $arr[0] . '</td>
                    <td>' . $arr[1] . '</td>
                    <td>' . $arr[2] . '</td>
                    <td>' . htmlspecialchars($arr[3]) . '</td>
                </tr>';
    }

    $html_footer = '</table>';

    $pdf->writeHTML($html_header . $html . $html_footer, true, false, false, false, '');

    // Dataset specification
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

    $html_header = '
        <h2>1. Purpose</h2>
    	<p>The purpose of this document is to specify the scope and content of the following Pharmacometric analysis dataset(s): </p>
    
    	<table id=dataset_info border="1" cellspacing="0" cellpadding="2">
    		<tr>
    			<th style="width: 15%;">Dataset Name </th>
    			<th style="width: 25%;">Dataset Descriptor </th>
    			<th style="width: 48%;">Location (path) </th>
    			<th style="width: 12%;">Delivery Date </th>
    		</tr>

    		<tr>
    			<td>' . $dataset_name . '</td>
    			<td>' . htmlspecialchars($dataset_descriptor) . '</td>
    			<td>' . $dataset_path . '</td>
    			<td>' . $dataset_due_date . '</td>
    		</tr>
    	</table>
    		
    	<br /><br />
    	
    	<p>Specification of the scope and content of the above datasets includes specification of:</p>
    	<p>Studies from which data are to be obtained, and the location of the source data (Section 2)</p>
    	<p>Dataset structure and variables (Section 3)</p>
    	<p>Derivations and handling of missing data (Section 4)</p>
    
    	<br /><br />';

    $pdf->writeHTML($html_header, true, false, false, false, '');

    // PK data source
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

    $html1 = '
    	<h2>2. Study Description</h2>
    	<h3>2.1 PK data sources</h3>
    	<table border="1" cellspacing="0" cellpadding="2">
    		<tr>
    			<th style="width: 20%;">Study</th>
    			<th style="width: 35%;">Study Type</th>
    			<th style="width: 35%;">Lock Type</th>
    		</tr>';

    $html2 = '';

    foreach ($pkarray as $arr) {
        $html2 .=
        '<tr>
			<td>' . $arr[0] . '</td>
			<td>' . $arr[1] . '</td>
			<td>' . $arr[2] . '</td>
		</tr>';
    }

    $html3 = '</table>

	<br /><br />

	<h3>2.2 Source data path for clinical, safety, efficacy and biomarker data</h3>
	<table border="1" cellspacing="0" cellpadding="2">
		<tr>
			<th style="width: 10%;">Study</th>
			<th style="width: 10%;">Statistician</th>
			<th style="width: 20%;">level0</th>
			<th style="width: 20%;">level1/SDTM</th>
			<th style="width: 20%;">level2</th>
			<th style="width: 20%;">Format</th>
		</tr>';

    $html4 = '';

    foreach ($clinarray as $arr) {
        $html4 .= '<tr>
			<td>' . $arr[0] . '</td>
			<td>' . $arr[1] . '</td>
			<td>' . $arr[2] . '</td>
			<td>' . $arr[3] . '</td>
			<td>' . $arr[4] . '</td>
			<td>' . $arr[5] . '</td>
			</tr>';
    }

    $html5 = '</table>
    	<br /><br />
    
    	<h3>2.3 Clinical data sources</h3>
    	<table border="1" cellspacing="0" cellpadding="2">
    		<tr>
    			<th style="width: 25%;">Raw Datasets Path Name</th>
    			<th style="width: 75%;">Dataset Location</th>
    		</tr>';

    $html6 = '';

    foreach ($pkmsarray as $arr) {
        $html6 .= 
            '<tr>
                <td>' . $arr[0] . '</td>
                <td>' . $arr[1] . '</td>
			</tr>';
    }

    $html7 = '</table>
	    <br /><br />
    	<h3>2.4 Location of the analysis dataset development directory</h3>
        <p>Program development path:</p>
        <p>' . $dataset_dev_path . '</p>
        
        <p>Program qc path:</p>
        <p>' . $dataset_qc_path . '</p>
        <br /><br />
        <h3>2.5 Dataset requirements and Specification</h3>
        <p>This section specifies overall requirements for datasets: names, dataset labels, and other requirements for a data set as a whole. The next section contains variable-by-variable specifications.</p>
        <p>Together, these sections detail all requirements, and supersede any previous discussions and agreements concerning these datasets.</p>

        <table border="1" cellspacing="0" cellpadding="2">
        	<tr>
        		<th style="width: 12%;">Requirement Number</th>
        		<th style="width: 88%;">Requirement/Description</th>
        	</tr>
        
        	<tr>
        		<td>1.01</td>
        		<td>The dataset will be named ' . $dataset_name . '</td>
        	</tr>
        
        	<tr>
        		<td>1.02</td>
        		<td>The dataset label will be: ' . $dataset_label . '</td>
        	</tr>
        
        	<tr>
        		<td>1.03</td>
        		<td>The dataset will contain ' . $dataset_multiple_record . '</td>
        	</tr>
        
        	<tr>
        		<td>1.04</td>
        		<td>The dataset will only contain records that meet the following criteria: ' . htmlspecialchars($dataset_criteria) . '</td>
        	</tr>
        
        	<tr>
        		<td>1.05</td>
        		<td>The dataset will conform to the structure and content as defined in section 3, Dataset Structure. </td>
        	</tr>
        
        	<tr>
        		<td>1.06</td>
        		<td>Coded data within this dataset, if any, will conform to the format specifications provided in section 3, Controlled Terms or Format Descriptions. For the datasets that need to be imported back into eToolbox the labels will be made same as variable names. Please refer to section 3 for variable labels </td>
        	</tr>
        
        	<tr>
        		<td>1.07</td>
        		<td>The dataset will be a SAS dataset. Please provide in both sas7dat and xpt formats. Note: use extension .sas7bdat for SAS datasets, .rdata for R datasets, and .sdata for S-Plus datasets. </td>
        	</tr>
        
        	<tr>
        		<td>1.08</td>
        		<td>The dataset will be sorted by the following fields: ' . $dataset_sort . '</td>
        	</tr>
        </table>';

    $pdf->writeHTML($html1 . $html2 . $html3 . $html4 . $html5 . $html6 . $html7, true, false, false, false, '');

    // dataset structure table
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

    $html1 = '
    	<h2>3. Dataset structure</h2>
    	<table border="1" cellspacing="0" cellpadding="2">
    		<tr>
    			<th style="width: 10%;">Variable Name</th>
    			<th style="width: 20%;">Variable Label</th>
    			<th style="width: 10%;">Units</th>
    			<th style="width: 6%;">Type</th>
    			<th style="width: 8%;">Rounding</th>
    			<th style="width: 8%;">Missing Value</th>
    			<th style="width: 20%;">Notes</th>
    			<th style="width: 20%;">Source</th>
    		</tr>';

    $html2 = '';

    foreach ($structarray as $arr) {
        $html2 .= '<tr>
    			<td>' . htmlspecialchars($arr[0]) . '</td>
    			<td>' . htmlspecialchars($arr[1]) . '</td>
    			<td>' . htmlspecialchars($arr[2]) . '</td>
    			<td>' . htmlspecialchars($arr[3]) . '</td>
    			<td>' . htmlspecialchars($arr[4]) . '</td>
    			<td>' . htmlspecialchars($arr[5]) . '</td>
    			<td>' . htmlspecialchars($arr[6]) . '</td>
    			<td>' . htmlspecialchars($arr[7]) . '</td>
    		</tr>';
    }

    $html3 = '</table>';
    $pdf->writeHTML($html1 . $html2 . $html3, true, false, false, false, '');
    
    // Derivations
    $pdf->AddPage('L');
    $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
    
    $html1 = '
        	<h2>4. Derivations/Outliers/Missing data</h2>
        	<h3>4.1 Derivations</h3>
        	<p>This section provides a list of all derivations and algorithms required for the creation of datasets.</p>
        	<br />
        
        	<table border="1" cellspacing="0" cellpadding="2">
        		<tr>
        			<th style="width: 10%;">Field</th>
        			<th style="width: 90%;">Algorithm</th>
        		</tr>';

    $html2 = '';
    
    foreach ($derivearray as $arr) {
        $html2 .= '<tr>
    			<td>' . htmlspecialchars($arr[0]) . '</td>
    			<td>' . htmlspecialchars($arr[1]) . '</td>
    		</tr>';
    }

    $html3 = '</table>
        	<br />
        	<h3>4.2 Handling of missing data</h3>
        	<p>This section describes the handling of missing data and any imputation of missing data to be performed.</p>
        
            <p>' . htmlspecialchars($missings) . '</p>
        
            <br />
        
        	<h3>4.3 Programming Algorithms and Imputations</h3>
        	<p>This section provides the algorithms and imputation rules for the creation of analysis datasets, such as dosing or concomitant medications.</p>
        
        	<table border="1" cellspacing="0" cellpadding="2">
        		<tr>
        			<th style="width: 8%;">Flag Number</th>
        			<th style="width: 40%;">FLAGCOM</th>
        			<th style="width: 52%;">Notes</th>
        		</tr> ';

    $html4 = '';
    
    foreach ($flagarray as $arr) {
        $html4 .= '<tr>
    			<td>' . htmlspecialchars($arr[0]) . '</td>
    			<td>' . htmlspecialchars($arr[1]) . '</td>
    			<td>' . htmlspecialchars($arr[2]) . '</td>
    		</tr>';
    }
    
    $html5 = '</table>';

    $pdf->writeHTML($html1 . $html2 . $html3 . $html4 . $html5, true, false, false, false, '');

    $filename = $dataset_path;
    $filename = str_replace('prd', 'dev', $filename);
    $filename = str_replace($pkms_path, '', $filename);
    $filename = str_replace($pkms_path2, '', $filename);
    $filename = str_replace('final/derived', 'doc', $filename);
    $filename = str_replace('/', '_', $filename);

    ob_end_clean();

    if (! is_dir($pdfspecpath)) {
        mkdir($pdfspecpath, 0755, true);
    }

//  $pdf->Output($pdfspecpath . '_' . $dataset_name. '_v'. $selected_version . $filename . '.pdf', 'F');
//  $pdf->Output('PM_analysis_dataset_spec.pdf', 'I');
    
    $file_name   = 'PM_dataset_spec_' . $dataset_name . '_v' . $selected_version . $filename . '.pdf';
    $target_path = str_replace("final/derived", 'doc', $dataset_path);
    $target_path = str_replace("prd", 'dev', $target_path);
    
    $source_path = $s3_bucket_path;
    $created_date = date("Y-m-d H:i:s");
    $created_by = $_SESSION["username"];
    $status = "Pending";
    
//  Write record to file_transfer table
//  insert_file($db, $specid, $file_name, $source_path, $target_path, $status, $created_date, $created_by);
    
    mysqli_close($db);
    $dbh = null;

    // Write file to global/pkms/ drive
    include 'file_transfer.php';
 
    $file_path = $pdfspecpath . '_' . $dataset_name . '_v' . $selected_version . $filename . '.pdf';

    $data = array(
                    'file_path'     => $file_path, 
                    'file_name'     => $file_name,
                    'target_path'  => $target_path,
                    'server_domain' => $server_domain
            );
    
    mount_drive_transfer($data);
?>
