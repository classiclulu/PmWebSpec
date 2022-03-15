<?php
    error_reporting(0);
    include_once('tcpdf/config/lang/eng.php');
    include_once('tcpdf/tcpdf.php');

    function initiateDirectorySetup($post_data) {
        ob_end_flush();
        ob_start();

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetHeaderData("", 0, "", "Data Sciences, Clinical Pharmacology and Pharmacometrics");

        // Front page
        $pdf->AddPage('L');

        $html = '<style type="text/css">
                	table {
                		border:none;
                		border: 1px solid black;
                       	border-collapse: collapse;
                       	padding: 5px;
                       	border-spacing: 5px;
                       	width: 100%;
                	}
                    th, td {
                       	border: 1px solid black;
                       	border-collapse: collapse;
                       	padding: 5px;
                       	border-spacing: 5px;
                       	width: 100%;
                    }
                    td.hide_all{
                        border: none;
                    }
                </style>

            	<h3 align="center"> PKMS Directory Structure Request From</h3>
            	<h4 align="center">' . date("Y-m-d") .'</h4>

                <table>
                	<tr>
                		<td class="hide_all" width="60%"> (Level 1): Therapeutic Area:</td>
                		<td width="40%"> '. $post_data['level1'] .'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> (Level 2): Compound # (e.g. 185) or BMS/BMT# or Pearl drug name or &#34;nds&#34;:</td>
                		<td width="40%"> '. $post_data['level2'] .'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> (Level 3): Study Level:</td>
                		<td width="40%"> '. $post_data['level3'] .'</td>
                	</tr>
                    <tr>
                        <td class="hide_all" width="60%"> (Level 3): Study level directory description:</td>
                        <td width="40%"> '. $post_data['level3desc'] .'</td>
                    </tr>
                	<tr>
                		<td class="hide_all" width="60%"> (Level 5): Analysis Directory Name (lowercase preferred):</td>
                		<td width="40%"> '. $post_data['level5'] .'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> (Level 5): Analysis Directory Description (One description per analysis directory. Programmer copies this into 0readme.txt):</td>
                		<td width="40%"> '. $post_data['description'] .'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> Requester&#39;s Name:</td>
                		<td width="40%"> '. $post_data['requester'] .'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> Does read access need to be restricted to only certain users?  For example, this would be true when doing an interim analysis of unblinded data from a blinded study.  If not, then you are done with this form.</td>
                		<td width="40%"> '. $post_data['access'].'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> If an existing security group should be used, enter its name:</td>
                		<td width="40%"> '. $post_data['group'].'</td>
                	</tr>
                	<tr>
                		<td class="hide_all" width="60%"> Otherwise, Data Sciences will have a security group set up for this study; Informatics may require one to two weeks to complete the task. Please enter desired group member information below (BMS username separated by colon)</td>
                		<td width="40%"> ' . $post_data['users'] . '</td>
                	</tr>
                </table>';

        $pdf->writeHTML($html, true, false, false, false, '');
        
        //Instructions
        $pdf->AddPage('L');
        $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

        $html = '<b>There are two major steps to take to have a directory structure set up for an analysis:</b>
                <ol>
                	<li><b>Fill out the form: </b>The requester fills out the form on page 1 and emails the result to Data Sciences (MG-DS-PM).
                		<ul>
                			<li>A DS programmer will set up the data/&lt;TA&gt;/&lt;Compound&gt;/&lt;Study&gt;/ structure and its subdirectories.</li>
                			<li>The DS programmer sends the requester an email with detailed instructions on how the requester and other analysts must run the &#34;cds3&#34; command to create the additional directories needed for the requester and each analyst.</li>
                			<li>If the data/&lt;TA&gt;/&lt;Compound&gt;/&lt;Study&gt;/ structure exists already, then there is no need to fill out this form.  Analysts may jump to the next step (the &#34;cds3&#34; step) directly.</li>
                		</ul>
                	</li>
                
                	<li><b>Run cds3: </b>The requester and each analyst must then cd to the correct directory and run a &#34;cds3&#34; command.
                		<ul>
                			<li>The Data Sciences programmer provides instructions in email to the requester, and the requester forwards the email to every analyst who needs directories in the analysis structure.</li>
                			<li>The analysts must take this step because &#34;cds3&#34; creates directories owned by the analyst, and this can succeed only if the analyst runs &#34;cds3&#34;.  The DS programmer is unable to take this step for the analyst.</li>
                			<li>Example: Suppose the analysis is a &#34;ppk&#34; analysis for AI-123-010, and S+ and NONMEM software directories are needed. The requester, or other analyst, enters:
                				<p>cd /global/pkms/data/AI/123/010</p>
                				<p>cds3 ppk sp nm</p>
                			</li>
                			<li>Each analyst specifies only the software directories that he or she needs. The analyst may run the &#34;cds3&#34; command as many times as is needed at later times to create new software directories.</li>
                			<li>The analyst must use an abbreviation for the software, and must choose from this list:</li>
                		</ul>
                	</li>
                </ol>';

        $pdf->writeHTML($html, true, false, false, false, '');

        // Instructions
        $pdf->AddPage('L');
        $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);

		$html = '
    	<table border="1" cellspacing="0" >
    		<tr>
    			<th style="width: 20%;"> Software</th>
    			<th style="width: 20%;"> Abbreviation</th>
    		</tr>
    		<tr>
    			<td> eSub data sets</td>
    			<td> esub</td>
    		</tr>
    		<tr>
    			<td> Export data sets</td>
    			<td> export</td>
    		</tr>
    		<tr>
    			<td> MATLAB</td>
    			<td> matlab</td>
    		</tr>
            <tr>
                <td> Monolix</td>
                <td> mnx</td>
            </tr>
    		<tr>
    			<td> NONMEM</td>
    			<td> nm</td>
    		</tr>
            <tr>
                <td> Perl-speaks-NONMEM</td>
                <td> psn</td>
            </tr>
    		<tr>
    			<td> R</td>
    			<td> R</td>
    		</tr>
    		<tr>
    			<td> Reports</td>
    			<td> reports</td>
    		</tr>
    		<tr>
    			<td> SAS</td>
    			<td> sas</td>
    		</tr>
    		<tr>
    			<td> SimCYP</td>
    			<td> simcyp</td>
    		</tr>
            <tr>
                <td> SimulX</td>
                <td> smlx</td>
            </tr>
    		<tr>
    			<td> Stan</td>
    			<td> stan</td>
    		</tr>
    		<tr>
    			<td> WinBUGS / OPENBUGS</td>
    			<td> bugs</td>
    		</tr>
    		<tr>
    			<td> WinNonLin</td>
    			<td> wnl</td>
    		</tr>
    	</table>';

        $pdf->writeHTML($html, true, false, false, false, '');
        ob_end_clean();

        ## send the PDF by email
        $pdf->Output($post_data['file_path'], 'F');
    }
?>
