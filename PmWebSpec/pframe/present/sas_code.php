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
//print_r($dataset_structure);exit;
//dataset information
     // if($dataset_general !== FALSE) {
  if($dataset_structure !== FALSE) {
           foreach ($dataset_structure as  $sts) {

                  $varnames[] = $sts['var_name'];
                  if ($sts['var_units']=="NA") {
                        $varlabels[] = $sts['var_name'] . ' = "' . $sts['var_label'] . '"';
                  } else {
                        $varlabels[] = $sts['var_name'] . ' = "' . $sts['var_label'] . '[' . $sts['var_units'] . ']"';
                  }

                  if ($sts['var_missing_value']=='-99') {
                        $miss99[] = $sts['var_name'];
                  }

                  if ($sts['var_rounding']=='0.1') {
                        $round1[] = $sts['var_name'];
                  } elseif ($sts['var_rounding']=='0.01') {
                        $round2[] = $sts['var_name'];
                  } elseif ($sts['var_rounding']=='0.001') {
                        $round3[] = $sts['var_name'];
                  } elseif ($sts['var_rounding']=='4 significant digits') {
                        $round4[] = $sts['var_name'];
                  } elseif ($sts['var_rounding']=='3 significant digits') {
                        $round5[] = $sts['var_name'];
                  } elseif ($sts['var_rounding']=='1') {
                        $round6[] = $sts['var_name'];
                  }                    
            }

            $vars = implode(' ', $varnames);
            $dataset_sort = str_replace(",", " ", $dataset_sort);
      }      
?>

	<body>
		<div class="small">
		<?php 
            $dataset_type = $user_spec[0]['type'];
            $parts = explode('-', $dataset_type);

            if (in_array('ISOP', $parts)) 
            {
                  echo '
                              <pre><font size="4" color="red">* --------------------- hepatic function ------------------ *; </font></pre>
                              <pre>length hepa $ 20; </pre>
                              <pre>if ulntbili ne . or ulnast ne . then do; </pre>
                              <pre>    if ulntbili ne . then do; </pre>
                              <pre>        ulntbili15=1.5*ulntbili; </pre>
                              <pre>        ulntbili3=3*ulntbili; </pre>
                              <pre>    end; </pre>
                  
                              <pre>    select; </pre>
                              <pre>        when (nmiss(tbilb)=0 and tbilb > ulntbili3) do; </pre>
                              <pre>            hepa="GROUP D: Severe"; </pre>
                              <pre>            hepan=4; </pre>
                              <pre>        end; </pre>
                              <pre>        when (nmiss(tbilb)=0 and (ulntbili15 < tbilb <= ulntbili3)) do; </pre>
                              <pre>            hepa="GROUP C: Moderate"; </pre>
                              <pre>            hepan=3; </pre>
                              <pre>        end; </pre>            
                              <pre>        ((nmiss(tbilb)=0 and (ulntbili < tbilb <= ulntbili15)) or ((nmiss(astb)=0) and (astb > ulnast))) do; 
                  hepa="GROUP B: Mild"; </pre>
                              <pre>            hepan=2; </pre>
                              <pre>        end; </pre>            
                              <pre>        when ((nmiss(tbilb)=0 and (tbilb <= ulntbili)) and (nmiss(astb)=0 and (astb <= ulnast))) do; </pre>
                              <pre>            hepa="GROUP A: Normal"; </pre>
                              <pre>            hepan=1; </pre>
                              <pre>        end; </pre>
                              <pre>        otherwise; </pre>
                              <pre>    end; </pre>
                              <pre>end; </pre>
                  
                              <br />

                              <pre><font size="4" color="red">* ---------------- EGFRB, CRCLB and IBWB -------------- *; </font></pre>
                              <pre>if age>0 then do; </pre>
                              <pre>    if sexn=1 then do; * male ;</pre>
                              <pre>        k=.9; </pre>
                              <pre>        a=-.411; </pre>
                              <pre>    end; </pre>
                              <pre>    else if sexn=2 then do; * female ;</pre>
                              <pre>        k=.7; </pre>
                              <pre>        a=-.329; </pre>
                              <pre>    end; </pre>
                              <pre>    ****** egfrb ******; </pre>
                              <pre>    if n(creatb, sexn, racen)=3 then do; </pre>
                              <pre>        egfrb=141*(min((creatb/k),1)**a)*(max(creatb/k,1)**-1.209)*(0.993**age); </pre>
                              <pre>        if sexn=2 then egfrb=egfrb*1.018; </pre>
                              <pre>        if racen=2 then egfrb=egfrb*1.159; </pre>
                              <pre>    end; </pre>
                              <pre>    ****** crclb ******; </pre>
                              <pre>    if n(wtb, creatb, sexn)=3 then crclb=(((140-age)*wtb)/(72*creatb)); </pre>
                              <pre>    if sexn=2 and crclb>. then crclb=crclb * 0.85; </pre>
                              <pre>end; </pre>
                              <pre> </pre>
                              <pre>****** IBWB ******; </pre>
                              <pre>if n(htb, sexn)=2 then do; </pre>
                              <pre>    ht_in = htb*0.3937; * 1 cm = 0.3937 in ; </pre>
                              <pre>    if sexn=1 then do; </pre>
                              <pre>        if ht_in > 60 then ibwb=50+2.3*(ht_in-60); </pre>
                              <pre>        else ibwb=50; </pre>
                              <pre>    end; </pre>
                              <pre>    else if sexn =2 then do; </pre>
                              <pre>        if ht_in > 60 then ibwb=45.5+2.3*(ht_in-60); </pre>
                              <pre>        else ibwb=45.5; </pre>
                              <pre>    end; </pre>
                              <pre>end; </pre>
                              <pre>drop k a ; </pre>
                  
                              <br />';
                  if (strpos($dataset_type, 'PPK') !== false) {
                              echo '<pre><font size="4" color="red">* --------------------- impute dose time ------------------ *; </font></pre>
                              <pre>****** please use the following codes as reference and update when necessary ******; </pre>
                              <pre>*** 1. Impute with trough or pre-dose samples ***; </pre>
                              <pre>proc sql noprint; </pre>
                              <pre>    select distinct("\'"||usubjid||"\'") into :timemiss separated by "," from dose where dttm=.; </pre>
                              <pre>quit; </pre>
                  
                              <br/>
                  
                              <pre>proc sort data=pk; by usubjid date evid; run; </pre>
                  
                              <br />
                  
                              <pre>data pk; </pre>
                              <pre>    set pk; </pre>
                              <pre>    by usubjid date evid; </pre>
                              <pre>    length usubjid2 usubjid3 $ 30; </pre>
                              <pre>    retain usubjid2 usubjid3 date2 time2 npreltm2 time0 date0; </pre>
                              <pre>    if first.usubjid then do; </pre>
                              <pre>        usubjid2=" "; usubjid3=" "; date2=.; time2=.; npreltm2=.; time0=.; date0=.; </pre>
                              <pre>    end; </pre>
                              <pre> </pre>
                              <pre>    if evid=0 then do; </pre>
                              <pre>        *retain trough date/time and id if there is a trough; </pre>
                              <pre>        if trough=1 then do; </pre>
                              <pre>            usubjid2=usubjid; date2=date; time2=time;  </pre>
                              <pre>        end; </pre>
                              <pre>        *retain 0 hr non trough sample if there is no trough; </pre>
                              <pre>        trough=. and npreltm=0 then do; </pre>
                              <pre>            usubjid3=usubjid; npreltm2=npreltm; date0=date; time0=time; </pre>
                              <pre>        end; </pre>
                              <pre>    end; </pre>
                              <pre> </pre>
                              <pre>    else if evid=1 and dttm=. then do; </pre>
                              <pre>        if usubjid2=usubjid and date=date2 then do; </pre>
                              <pre>            time=time2; f=1; </pre>
                              <pre>        end; </pre>
                              <pre>        else if npreltm2=0 and usubjid3=usubjid and date=date0 then do; </pre>
                              <pre>            time=time0; f=2; </pre>
                              <pre>        end; </pre>
                              <pre>    end; </pre>
                              <pre> </pre>
                              <pre>    if dttm=. and time ne . then do; </pre>
                              <pre>        flag=13; dttm=dhms(date,0,0,time); </pre>
                              <pre>    end; </pre>
                  
                              <pre>    drop usubjid2 usubjid3 date2 time2 npreltm2 time0 date0; </pre>
                              <pre>    *format date2 date0 date9. time2 time0 time5.; </pre>
                              <pre>run; </pre>
                  
                              <br />
                  
                              <pre>*** 2. Impute with preivous dose time ***; </pre>
                              <pre>proc sort data=pk; by usubjid date time evid; run; </pre>
                  
                              <br />
                  
                              <pre>data pk; </pre>
                              <pre>    set pk; </pre>
                              <pre>    retain time2 usubjid2;</pre>
                  
                              <pre>    if evid=1 then do; </pre>
                              <pre>        if time not in (0, .) then do; </pre>
                              <pre>            time2=time; usubjid2=usubjid; </pre>
                              <pre>        end; </pre>
                              <pre>        else if (time=. or flgtm0=1) and usubjid=usubjid2 then do; </pre>
                              <pre>            time=time2;  dttm=dhms(date,0,0,time); flag=13; f=3; </pre>
                              <pre>        end; </pre>
                              <pre>    end; </pre>
                  
                              <pre>    drop time2 usubjid2; </pre>
                              <pre>run; </pre>
                  
                              <br />
                  
                              <pre>*** 3. Impute with next dose time ***; </pre>
                              <pre>proc sort data=pk; by usubjid descending date descending time descending evid; run; </pre>
                  
                              <br />
                  
                              <pre>data pk; </pre>
                              <pre>    set pk; </pre>
                              <pre>    retain time2 usubjid2;</pre>
                  
                              <pre>    if evid=1 then do; </pre>
                              <pre>        if time not in (0, .) then do; </pre>
                              <pre>            time2=time; usubjid2=usubjid; </pre>
                              <pre>        end; </pre>
                              <pre>        else if (time=. or flgtm0=1) and usubjid=usubjid2 then do; </pre>
                              <pre>            time=time2;  dttm=dhms(date,0,0,time); flag=13; f=3; </pre>
                              <pre>        end; </pre>
                              <pre>    end; </pre>
                  
                              <pre>    drop time2 usubjid2; </pre>
                              <pre>run; </pre>
                  
                              <br />
                  
                  
                              <pre>*** print result ***; </pre>
                              <pre>proc print data=pk; </pre>
                              <pre>    where usubjid in (&timemiss); </pre>
                              <pre>    var usubjid evid date time dttm npreltm trough flag f; </pre>
                              <pre>run; </pre>
                  
                              <br />';    
                  }  
                  if (strpos($dataset_type, 'PPK') !== false) {
                  
                              echo '<pre><font size="4" color="red">* ----------------- derive afreltm and apreltm -------------- *; </font></pre>
                              <pre>proc sort data=pk; by usubjid dttm evid; run; </pre>
                              <br />
                              <pre>*** first dose time ***; </pre>
                              <pre>data dose1st; </pre>
                              <pre>    set pk (where=(evid=1)); </pre>
                              <pre>    by usubjid; </pre>
                              <pre>    retain dttm1st2; </pre>
                              <pre>    if first.usubjid then dttm1st2 = dttm ; </pre>
                              <pre>    if last.usubjid; </pre>
                              <pre>    keep usubjid dttm1st2; </pre>
                              <pre>    format dttm1st2 datetime19.; </pre>
                              <pre>run; </pre>
                              <br/>
                              <pre>data pk;</pre>
                              <pre>    merge pk(in=a) dose1st(in=b); </pre>
                              <pre>    by usubjid; </pre>
                              <pre>    if a; </pre>
                              <pre> </pre>
                              <pre>    if nmiss(dttm,dttm1st2)=0 then afreltm=(dttm-dttm1st2)/3600; </pre>
                              <pre>    *** flag 1-3, add other flags if applicable ***; </pre>
                              <pre>    if evid=0 then do; </pre>
                              <pre>        if dttm=. or (dv=. and concstat ne 1) or (day > 1 and afreltm < 0) then flag=1;    *missing conc, date, time, day>0 and afreltm<0; </pre>
                              <pre>        if week in (999) and afreltm < 0 and flag=. then flag=1; *incorrect date, time; </pre>
                              <pre>        if day=1 and afreltm <= 0 and flag=. then flag=3; *day-1 predose; </pre>
                              <pre>        if day=1 and npreltm > 0 and flag=3 then flag=1; *incorrect date, time; </pre>
                              <pre>        if concstat = 1 and flag=. then flag=2; *post first dose BLQ; </pre>
                              <pre>        if npreltm=0 and afreltm>0 and flag=2 then flag=1; *this should be predose sample with incorrect date, time; </pre>
                              <pre>    end; </pre>
                              <pre>run;</pre>
                  
                              <br />
                              <pre>data pk; </pre>
                              <pre>    set pk;</pre>
                              <pre>    retain dttm1 usubjid2;</pre>
                              <pre>    drop dttm1 usubjid2;</pre>
                              <pre> </pre>
                              <pre>    if evid=1 and amt>0 and dttm ne . then do; dttm1=dttm; usubjid2=usubjid; end; </pre>
                              <pre>    if evid=0 and usubjid=usubjid2 then apreltm=(dttm-dttm1)/3600; </pre>
                              <pre> </pre>
                              <pre>    format dttm1 datetime19.; </pre>
                              <pre>run; </pre>
                  
                              <br />
                  
                              <pre><font size="4" color="red">* -------------- carry dose information to pk ------------- *; </font></pre>
                              <pre>****** flag pk-related dosing records ******; </pre>
                              <pre>proc sort data=pk; by usubjid descending dttm descending evid; run; </pre>
                              <br />
                              <pre>data pk (drop=sampprv); </pre>
                              <pre>    set pk; </pre>
                              <pre>    by usubjid descending dttm; </pre>
                              <pre>    retain sampprv;</pre>
                              <pre> </pre>
                              <pre>    if first.usubjid then sampprv=.; </pre>
                              <pre>    if evid=0 then sampprv=1; </pre>
                              <pre>    else if evid=1 and sampprv=1 then do; </pre>
                              <pre>        flgpkrelat=1;</pre>
                              <pre>        sampprv=.; *reset to missing; </pre>
                              <pre>    end; </pre>
                              <pre>run;</pre>
                              <br />
                              <pre>****** Carry dose information to pk records ******; </pre>
                              <pre>proc sort; by usubjid dttm evid; run; </pre>
                              <br />
                              <pre>data pk; </pre>
                              <pre>    set pk; </pre>
                              <pre>    by usubjid; </pre>
                              <pre>    retain amt1 amt2 occ dosenumc flg2 dn; </pre>
                              <pre> </pre>
                              <pre>    if first.usubjid then do;</pre>
                              <pre>        amt1=.; amt2=.; occ=.; dosenumc=.; dn=.; flg2=.; </pre>
                              <pre>    end; </pre>
                              <pre> </pre>
                              <pre>    select (evid); </pre>
                              <pre>        when (1) do; </pre>
                              <pre>            if flgpkrelat=1 then do; </pre>
                              <pre>                if nmiss(occ)=1 then occ=1; *start occ numbering for first pk-related dose; </pre>
                              <pre>                else occ=occ+1; *increment for this pk-related dose; </pre>
                              <pre>            end;</pre>
                              <pre> </pre>
                              <pre>            if nmiss(dosenumc)=1 then dosenumc=1; </pre>
                              <pre>            else dosenumc=dosenumc+1;</pre>
                              <pre>            dosenum=dosenumc; </pre>
                              <pre>            flg2=flag;</pre>
                              <pre>            amt1=amt; </pre>
                              <pre>            bdose=amt; </pre>
                              <pre>            amt2=ndose; </pre>
                              <pre>            dn=dosenum; </pre>
                              <pre>        end; </pre>
                              <pre> </pre>
                              <pre>        when (0) do; </pre>
                              <pre>            dosenum=dn; </pre>   
                              <pre>            bdose=amt1; </pre>
                              <pre>            ndose=amt2; </pre>
                              <pre>            if flg2 = 14 and flag=. then flag=flg2; *update this if other flags are retained to pk records;</pre>
                              <pre>        end; </pre>
                              <pre>        otherwise do; </pre>
                              <pre>            put "unexpected value of evid " evid= usubjid=; </pre>
                              <pre>            abort; </pre>
                              <pre>        end; </pre>
                              <pre>    end; </pre>
                              <pre>run; </pre>
                  
                              <br />

                              <pre><font size="4" color="red">* ------------ flag dose record if all pk records are flagged as 1-5 ----------- *; </font></pre>
                              <pre>proc sort; by usubjid evid; run; </pre>
                              <br />
                              <pre>data pk;</pre>
                              <pre>    set pk; </pre>
                              <pre>    by usubjid evid; </pre>
                              <pre>    retain allflagged; </pre>
                              <pre> </pre>
                              <pre>    if first.usubjid then allflagged=1; *initialize assuming all are flagged; </pre>
                              <pre> </pre>
                              <pre>    *** set allflagged to 0 if not flagged with flag=1,2,3,4,5; </pre>
                              <pre>    if evid=0 and allflagged=1 and (flag < 1 or flag > 5) then allflagged=0; </pre>
                              <pre> </pre>
                              <pre>    if evid=1 and allflagged=1 then do; </pre>
                              <pre>        put "this record is flagged due to no unflagged pk " usubjid= afreltm=; </pre>
                              <pre>        flag=1; </pre>
                              <pre>    end; </pre>
                              <pre>run;</pre>
                              <br />
                              <pre>proc sort; by usubjid date; run; </pre>

                              <br />';
                  }
            }
            else
            {
                  if (strpos($dataset_type, 'PPK') !== false) {
                        echo '
                                    <pre><font size="4" color="red">* --------------------- hepatic function ------------------ *; </font></pre>
                                    <pre>length hepa $ 20; </pre>
                                    <pre>if ulntbili ne . or ulnast ne . then do; </pre>
                                    <pre>    if ulntbili ne . then do; </pre>
                                    <pre>        ulntbili15=1.5*ulntbili; </pre>
                                    <pre>        ulntbili3=3*ulntbili; </pre>
                                    <pre>    end; </pre>
                        
                                    <pre>    select; </pre>
                                    <pre>        when (nmiss(btbili)=0 and btbili > ulntbili3) do; </pre>
                                    <pre>            hepa="GROUP D: Severe"; </pre>
                                    <pre>            hepan=4; </pre>
                                    <pre>        end; </pre>
                                    <pre>        when (nmiss(btbili)=0 and (ulntbili15 < btbili <= ulntbili3)) do; </pre>
                                    <pre>            hepa="GROUP C: Moderate"; </pre>
                                    <pre>            hepan=3; </pre>
                                    <pre>        end; </pre>            
                                    <pre>        when ((nmiss(btbili)=0 and (ulntbili < btbili <= ulntbili15)) or ((nmiss(bast)=0) and (bast > ulnast))) do; </pre>
                                    <pre>            hepa="GROUP B: Mild"; </pre>
                                    <pre>            hepan=2; </pre>
                                    <pre>        end; </pre>            
                                    <pre>        when ((nmiss(btbili)=0 and (btbili <= ulntbili)) and (nmiss(bast)=0 and (bast <= ulnast))) do; </pre>
                                    <pre>            hepa="GROUP A: Normal"; </pre>
                                    <pre>            hepan=1; </pre>
                                    <pre>        end; </pre>
                                    <pre>        otherwise; </pre>
                                    <pre>    end; </pre>
                                    <pre>end; </pre>
                        
                                    <br />

                                    <pre><font size="4" color="red">* ---------------- BGFR, BCRCL and BIBW -------------- *; </font></pre>
                                    <pre>if age>0 then do; </pre>
                                    <pre>    if sexn=1 then do; * male ;</pre>
                                    <pre>        k=.9; </pre>
                                    <pre>        a=-.411; </pre>
                                    <pre>    end; </pre>
                                    <pre>    else if sexn=2 then do; * female ;</pre>
                                    <pre>        k=.7; </pre>
                                    <pre>        a=-.329; </pre>
                                    <pre>    end; </pre>
                                    <pre>    ****** BGFR ******; </pre>
                                    <pre>    if n(bscr, sexn, racen)=3 then do; </pre>
                                    <pre>        bgfr=141*(min((bscr/k),1)**a)*(max(bscr/k,1)**-1.209)*(0.993**age); </pre>
                                    <pre>        if sexn=2 then bgfr=bgfr*1.018; </pre>
                                    <pre>        if racen=2 then bgfr=bgfr*1.159; </pre>
                                    <pre>    end; </pre>
                                    <pre>    ****** BCRCL ******; </pre>
                                    <pre>    if n(bbwt, bscr, sexn)=3 then bcrcl=(((140-age)*bbwt)/(72*bscr)); </pre>
                                    <pre>    if sexn=2 and bcrcl>. then bcrcl=bcrcl * 0.85; </pre>
                                    <pre>end; </pre>
                                    <pre> </pre>
                                    <pre>****** BIBW ******; </pre>
                                    <pre>if n(bht, sexn)=2 then do; </pre>
                                    <pre>    ht_in = bht*0.3937; * 1 cm = 0.3937 in ; </pre>
                                    <pre>    if sexn=1 then do; </pre>
                                    <pre>        if ht_in > 60 then bibw=50+2.3*(ht_in-60); </pre>
                                    <pre>        else bibw=50; </pre>
                                    <pre>    end; </pre>
                                    <pre>    else if sexn =2 then do; </pre>
                                    <pre>        if ht_in > 60 then bibw=45.5+2.3*(ht_in-60); </pre>
                                    <pre>        else bibw=45.5; </pre>
                                    <pre>    end; </pre>
                                    <pre>end; </pre>
                                    <pre>drop k a ; </pre>
                        
                                    <br />
                        
                                    <pre><font size="4" color="red">* --------------------- impute dose time ------------------ *; </font></pre>
                                    <pre>****** please use the following codes as reference and update when necessary ******; </pre>
                                    <pre>*** 1. Impute with trough or pre-dose samples ***; </pre>
                                    <pre>proc sql noprint; </pre>
                                    <pre>    select distinct("\'"||usubjid||"\'") into :timemiss separated by "," from dose where dttm=.; </pre>
                                    <pre>quit; </pre>
                        
                                    <br/>
                        
                                    <pre>proc sort data=pk; by usubjid date evid; run; </pre>
                        
                                    <br />
                        
                                    <pre>data pk; </pre>
                                    <pre>    set pk; </pre>
                                    <pre>    by usubjid date evid; </pre>
                                    <pre>    length usubjid2 usubjid3 $ 30; </pre>
                                    <pre>    retain usubjid2 usubjid3 date2 time2 ntapd2 time0 date0; </pre>
                                    <pre>    if first.usubjid then do; </pre>
                                    <pre>        usubjid2=" "; usubjid3=" "; date2=.; time2=.; ntapd2=.; time0=.; date0=.; </pre>
                                    <pre>    end; </pre>
                                    <pre> </pre>
                                    <pre>    if evid=0 then do; </pre>
                                    <pre>        *retain trough date/time and id if there is a trough; </pre>
                                    <pre>        if trough=1 then do; </pre>
                                    <pre>            usubjid2=usubjid; date2=date; time2=time;  </pre>
                                    <pre>        end; </pre>
                                    <pre>        *retain 0 hr non trough sample if there is no trough; </pre>
                                    <pre>        if trough=. and ntapd=0 then do; </pre>
                                    <pre>            usubjid3=usubjid; ntapd2=ntapd; date0=date; time0=time; </pre>
                                    <pre>        end; </pre>
                                    <pre>    end; </pre>
                                    <pre> </pre>
                                    <pre>    else if evid=1 and dttm=. then do; </pre>
                                    <pre>        if usubjid2=usubjid and date=date2 then do; </pre>
                                    <pre>            time=time2; f=1; </pre>
                                    <pre>        end; </pre>
                                    <pre>        else if ntapd2=0 and usubjid3=usubjid and date=date0 then do; </pre>
                                    <pre>            time=time0; f=2; </pre>
                                    <pre>        end; </pre>
                                    <pre>    end; </pre>
                                    <pre> </pre>
                                    <pre>    if dttm=. and time ne . then do; </pre>
                                    <pre>        flag=13; dttm=dhms(date,0,0,time); </pre>
                                    <pre>    end; </pre>
                        
                                    <pre>    drop usubjid2 usubjid3 date2 time2 ntapd2 time0 date0; </pre>
                                    <pre>    *format date2 date0 date9. time2 time0 time5.; </pre>
                                    <pre>run; </pre>
                        
                                    <br />
                        
                                    <pre>*** 2. Impute with preivous dose time ***; </pre>
                                    <pre>proc sort data=pk; by usubjid date time evid; run; </pre>
                        
                                    <br />
                        
                                    <pre>data pk; </pre>
                                    <pre>    set pk; </pre>
                                    <pre>    retain time2 usubjid2;</pre>
                        
                                    <pre>    if evid=1 then do; </pre>
                                    <pre>        if time not in (0, .) then do; </pre>
                                    <pre>            time2=time; usubjid2=usubjid; </pre>
                                    <pre>        end; </pre>
                                    <pre>        else if (time=. or flgtm0=1) and usubjid=usubjid2 then do; </pre>
                                    <pre>            time=time2;  dttm=dhms(date,0,0,time); flag=13; f=3; </pre>
                                    <pre>        end; </pre>
                                    <pre>    end; </pre>
                        
                                    <pre>    drop time2 usubjid2; </pre>
                                    <pre>run; </pre>
                        
                                    <br />
                        
                                    <pre>*** 3. Impute with next dose time ***; </pre>
                                    <pre>proc sort data=pk; by usubjid descending date descending time descending evid; run; </pre>
                        
                                    <br />
                        
                                    <pre>data pk; </pre>
                                    <pre>    set pk; </pre>
                                    <pre>    retain time2 usubjid2;</pre>
                        
                                    <pre>    if evid=1 then do; </pre>
                                    <pre>        if time not in (0, .) then do; </pre>
                                    <pre>            time2=time; usubjid2=usubjid; </pre>
                                    <pre>        end; </pre>
                                    <pre>        else if (time=. or flgtm0=1) and usubjid=usubjid2 then do; </pre>
                                    <pre>            time=time2;  dttm=dhms(date,0,0,time); flag=13; f=3; </pre>
                                    <pre>        end; </pre>
                                    <pre>    end; </pre>
                        
                                    <pre>    drop time2 usubjid2; </pre>
                                    <pre>run; </pre>
                        
                                    <br />
                        
                        
                                    <pre>*** print result ***; </pre>
                                    <pre>proc print data=pk; </pre>
                                    <pre>    where usubjid in (&timemiss); </pre>
                                    <pre>    var usubjid evid date time dttm ntapd trough flag f; </pre>
                                    <pre>run; </pre>
                        
                                    <br />
                        
                                    <pre><font size="4" color="red">* ----------------- derive ATAFD and ATAPD -------------- *; </font></pre>
                                    <pre>proc sort data=pk; by usubjid dttm evid; run; </pre>
                                    <br />
                                    <pre>*** first dose time ***; </pre>
                                    <pre>data dose1st; </pre>
                                    <pre>    set pk (where=(evid=1)); </pre>
                                    <pre>    by usubjid; </pre>
                                    <pre>    retain dttm1st2; </pre>
                                    <pre>    if first.usubjid then dttm1st2 = dttm ; </pre>
                                    <pre>    if last.usubjid; </pre>
                                    <pre>    keep usubjid dttm1st2; </pre>
                                    <pre>    format dttm1st2 datetime19.; </pre>
                                    <pre>run; </pre>
                                    <br/>
                                    <pre>data pk;</pre>
                                    <pre>    merge pk(in=a) dose1st(in=b); </pre>
                                    <pre>    by usubjid; </pre>
                                    <pre>    if a; </pre>
                                    <pre> </pre>
                                    <pre>    if nmiss(dttm,dttm1st2)=0 then atafd=(dttm-dttm1st2)/3600; </pre>
                                    <pre>    *** flag 1-3, add other flags if applicable ***; </pre>
                                    <pre>    if evid=0 then do; </pre>
                                    <pre>        if dttm=. or (dv=. and concstat ne 1) or (day &gt; 1 and atafd &lt; 0) then flag=1;    *missing conc, date, time, day&gt;0 and atafd&lt;0; </pre>
                                    <pre>        if week in (999) and atafd &lt; 0 and flag=. then flag=1; *incorrect date, time; </pre>
                                    <pre>        if day=1 and atafd &lt;= 0 and flag=. then flag=3; *day-1 predose; </pre>
                                    <pre>        if day=1 and ntapd &gt; 0 and flag=3 then flag=1; *incorrect date, time; </pre>
                                    <pre>        if concstat = 1 and flag=. then flag=2; *post first dose BLQ; </pre>
                                    <pre>        if ntapd=0 and atafd>0 and flag=2 then flag=1; *this should be predose sample with incorrect date, time; </pre>
                                    <pre>    end; </pre>
                                    <pre>run;</pre>
                        
                                    <br />
                                    <pre>data pk; </pre>
                                    <pre>    set pk;</pre>
                                    <pre>    retain dttm1 usubjid2;</pre>
                                    <pre>    drop dttm1 usubjid2;</pre>
                                    <pre> </pre>
                                    <pre>    if evid=1 and amt>0 and dttm ne . then do; dttm1=dttm; usubjid2=usubjid; end; </pre>
                                    <pre>    if evid=0 and usubjid=usubjid2 then atapd=(dttm-dttm1)/3600; </pre>
                                    <pre> </pre>
                                    <pre>    format dttm1 datetime19.; </pre>
                                    <pre>run; </pre>
                        
                                    <br />

                                    <pre><font size="4" color="red">* ----------------- derive afreltm and apreltm -------------- *; </font></pre>
                              <pre>proc sort data=pk; by usubjid dttm evid; run; </pre>
                              <br />
                              <pre>*** first dose time ***; </pre>
                              <pre>data dose1st; </pre>
                              <pre>    set pk (where=(evid=1)); </pre>
                              <pre>    by usubjid; </pre>
                              <pre>    retain dttm1st2; </pre>
                              <pre>    if first.usubjid then dttm1st2 = dttm ; </pre>
                              <pre>    if last.usubjid; </pre>
                              <pre>    keep usubjid dttm1st2; </pre>
                              <pre>    format dttm1st2 datetime19.; </pre>
                              <pre>run; </pre>
                              <br/>
                              <pre>data pk;</pre>
                              <pre>    merge pk(in=a) dose1st(in=b); </pre>
                              <pre>    by usubjid; </pre>
                              <pre>    if a; </pre>
                              <pre> </pre>
                              <pre>    if nmiss(dttm,dttm1st2)=0 then afreltm=(dttm-dttm1st2)/3600; </pre>
                              <pre>    *** flag 1-3, add other flags if applicable ***; </pre>
                              <pre>    if evid=0 then do; </pre>
                              <pre>        if dttm=. or (dv=. and concstat ne 1) or (day > 1 and afreltm < 0) then flag=1;    *missing conc, date, time, day>0 and afreltm<0; </pre>
                              <pre>        if week in (999) and afreltm < 0 and flag=. then flag=1; *incorrect date, time; </pre>
                              <pre>        if day=1 and afreltm <= 0 and flag=. then flag=3; *day-1 predose; </pre>
                              <pre>        if day=1 and npreltm > 0 and flag=3 then flag=1; *incorrect date, time; </pre>
                              <pre>        if concstat = 1 and flag=. then flag=2; *post first dose BLQ; </pre>
                              <pre>        if npreltm=0 and afreltm>0 and flag=2 then flag=1; *this should be predose sample with incorrect date, time; </pre>
                              <pre>    end; </pre>
                              <pre>run;</pre>
                  
                              <br />
                              <pre>data pk; </pre>
                              <pre>    set pk;</pre>
                              <pre>    retain dttm1 usubjid2;</pre>
                              <pre>    drop dttm1 usubjid2;</pre>
                              <pre> </pre>
                              <pre>    if evid=1 and amt>0 and dttm ne . then do; dttm1=dttm; usubjid2=usubjid; end; </pre>
                              <pre>    if evid=0 and usubjid=usubjid2 then apreltm=(dttm-dttm1)/3600; </pre>
                              <pre> </pre>
                              <pre>    format dttm1 datetime19.; </pre>
                              <pre>run; </pre>
                  
                              <br />
                        
                                    <pre><font size="4" color="red">* -------------- carry dose information to pk ------------- *; </font></pre>
                                    <pre>****** flag pk-related dosing records ******; </pre>
                                    <pre>proc sort data=pk; by usubjid descending dttm descending evid; run; </pre>
                                    <br />
                                    <pre>data pk (drop=sampprv); </pre>
                                    <pre>    set pk; </pre>
                                    <pre>    by usubjid descending dttm; </pre>
                                    <pre>    retain sampprv;</pre>
                                    <pre> </pre>
                                    <pre>    if first.usubjid then sampprv=.; </pre>
                                    <pre>    if evid=0 then sampprv=1; </pre>
                                    <pre>    else if evid=1 and sampprv=1 then do; </pre>
                                    <pre>        flgpkrelat=1;</pre>
                                    <pre>        sampprv=.; *reset to missing; </pre>
                                    <pre>    end; </pre>
                                    <pre>run;</pre>
                                    <br />
                                    <pre>****** Carry dose information to pk records ******; </pre>
                                    <pre>proc sort; by usubjid dttm evid; run; </pre>
                                    <br />
                                    <pre>data pk; </pre>
                                    <pre>    set pk; </pre>
                                    <pre>    by usubjid; </pre>
                                    <pre>    retain amt1 amt2 occ dosenumc flg2 dn; </pre>
                                    <pre> </pre>
                                    <pre>    if first.usubjid then do;</pre>
                                    <pre>        amt1=.; amt2=.; occ=.; dosenumc=.; dn=.; flg2=.; </pre>
                                    <pre>    end; </pre>
                                    <pre> </pre>
                                    <pre>    select (evid); </pre>
                                    <pre>        when (1) do; </pre>
                                    <pre>            if flgpkrelat=1 then do; </pre>
                                    <pre>                if nmiss(occ)=1 then occ=1; *start occ numbering for first pk-related dose; </pre>
                                    <pre>                else occ=occ+1; *increment for this pk-related dose; </pre>
                                    <pre>            end;</pre>
                                    <pre> </pre>
                                    <pre>            if nmiss(dosenumc)=1 then dosenumc=1; </pre>
                                    <pre>            else dosenumc=dosenumc+1;</pre>
                                    <pre>            dosenum=dosenumc; </pre>
                                    <pre>            flg2=flag;</pre>
                                    <pre>            amt1=amt; </pre>
                                    <pre>            bdose=amt; </pre>
                                    <pre>            amt2=ndose; </pre>
                                    <pre>            dn=dosenum; </pre>
                                    <pre>        end; </pre>
                                    <pre> </pre>
                                    <pre>        when (0) do; </pre>
                                    <pre>            dosenum=dn; </pre>   
                                    <pre>            bdose=amt1; </pre>
                                    <pre>            ndose=amt2; </pre>
                                    <pre>            if flg2 = 14 and flag=. then flag=flg2; *update this if other flags are retained to pk records;</pre>
                                    <pre>        end; </pre>
                                    <pre>        otherwise do; </pre>
                                    <pre>            put "unexpected value of evid " evid= usubjid=; </pre>
                                    <pre>            abort; </pre>
                                    <pre>        end; </pre>
                                    <pre>    end; </pre>
                                    <pre>run; </pre>
                        
                                    <br />
                        
                                    <pre><font size="4" color="red">* ------------ flag dose record if all pk records are flagged as 1-5 ----------- *; </font></pre>
                                    <pre>proc sort; by usubjid evid; run; </pre>
                                    <br />
                                    <pre>data pk;</pre>
                                    <pre>    set pk; </pre>
                                    <pre>    by usubjid evid; </pre>
                                    <pre>    retain allflagged; </pre>
                                    <pre> </pre>
                                    <pre>    if first.usubjid then allflagged=1; *initialize assuming all are flagged; </pre>
                                    <pre> </pre>
                                    <pre>    *** set allflagged to 0 if not flagged with flag=1,2,3,4,5; </pre>
                                    <pre>    if evid=0 and allflagged=1 and (flag &lt; 1 or flag &gt; 5) then allflagged=0; </pre>
                                    <pre> </pre>
                                    <pre>    if evid=1 and allflagged=1 then do; </pre>
                                    <pre>        put "this record is flagged due to no unflagged pk " usubjid= atafd=; </pre>
                                    <pre>        flag=1; </pre>
                                    <pre>    end; </pre>
                                    <pre>run;</pre>
                                    <br />
                                    <pre>proc sort; by usubjid date; run; </pre>

                                    <br />';
                     }
            }
?>

		<pre><font size="4" color="red">* -------------- rounding and missing impute ----------- *; </font></pre>
		<pre>data your_dataset_name; </pre>
		<pre>    set your_dataset_name ; </pre>
		<?php 
 			if (isset($round1)) {
 				echo '<pre>    *** round to 0.1 ***; </pre>';
 				echo '<pre>    array rou01 [*] ' . implode(' ', $round1) . '; </pre>';
 				echo '<pre>    do i=1 to dim(rou01); </pre>';
 				echo '<pre>        if not missing(rou01[i]) then rou01[i] = round(rou01[i], 0.1); </pre>';
 				echo '<pre>    end; </pre>';
 			}

 			if (isset($round2)) {
 				echo '<pre>    *** round to 0.01 ***; </pre>';
 				echo '<pre>    array rou001 [*] ' . implode(' ', $round2) . '; </pre>';
 				echo '<pre>    do i=1 to dim(rou001); </pre>';
 				echo '<pre>        if not missing(rou001[i]) then rou001[i] = round(rou001[i], 0.01); </pre>';
 				echo '<pre>    end; </pre>';
 			}

 			if (isset($round3)) {
 				echo '<pre>    *** round to 0.001 ***; </pre>';
 				echo '<pre>    array rou0001 [*] ' . implode(' ', $round3) . '; </pre>';
 				echo '<pre>    do i=1 to dim(rou0001); </pre>';
 				echo '<pre>        if not missing(rou0001[i]) then rou0001[i] = round(rou0001[i], 0.001); </pre>';
 				echo '<pre>    end; </pre>';
 			}

 			if (isset($miss99)) {
 				echo '<pre>    *** impute missing as -99 ***; </pre>';
 				echo '<pre>    array miss99 [*] ' . implode(' ', $miss99) . '; </pre>';
 				echo '<pre>    do i=1 to dim(miss99); </pre>';
 				echo '<pre>        if missing(miss99[i]) then miss99[i] = -99; </pre>';
 				echo '<pre>    end; </pre>';
 			}

                  if (isset($round4)) {               
                        echo '<pre>    *** round to 4 significant digits ***; </pre>';
                        echo '<pre>    array rou4sd[*] ' . implode(' ', $round4) . '; </pre>';
                        echo '<pre>    do i=1 to dim(rou4sd); </pre>';
                        echo '<pre>        if rou4sd[i] not in (0 .) then do; </pre>';
                        echo '<pre>            if int(rou4sd[i]) ne 0 then rou4sd[i]=round(rou4sd[i], 10**(int(log10(abs(rou4sd[i])))-3)); </pre>';
                        echo '<pre>            else rou4sd[i]=round(rou4sd[i],10**(-1*(abs(int(log10(abs(rou4sd[i]))))+4))); </pre>';
                        echo '<pre>        end; </pre>';
                        echo '<pre>    end; </pre>';
                  }     
	

 			if (isset($round5)) {
 				echo '<pre>    *** round to 3 significant digits ***; </pre>';
 				echo '<pre>    array rou3sd[*] ' . implode(' ', $round5) . '; </pre>';
 				echo '<pre>    do i=1 to dim(rou3sd); </pre>';
 				echo '<pre>        if not missing(rou3sd[i]) then do; </pre>';
 				echo '<pre>            if int(rou3sd[i]) ne 0 then rou3sd[i]=round(rou3sd[i], 10**(int(log10(abs(rou3sd[i])))-2));
 				</pre>';
 				echo '<pre>            else rou3sd[i]=round(rou3sd[i],10**(-1*(abs(int(log10(abs(rou3sd[i]))))+3)));
 				</pre>';
 				echo '<pre>        end; </pre>';
 				echo '<pre>    end; </pre>';

 			}	

                  if (isset($round6)) {
                        echo '<pre>    *** round to 1 ***; </pre>';
                        echo '<pre>    array rou1 [*] ' . implode(' ', $round6) . '; </pre>';
                        echo '<pre>    do i=1 to dim(rou1); </pre>';
                        echo '<pre>        if not missing(rou1[i]) then rou1[i] = round(rou1[i], 1); </pre>';
                        echo '<pre>    end; </pre>';
                  }
		?>

		<pre>run; </pre>
		<br />

		<pre><font size="4" color="red">* --------------------- final dataset ---------------- *; </font></pre>
		<pre>proc sort data = your_dataset_name ;</pre>
		<pre>    by <?php echo $dataset_sort; ?>; </pre>
		<pre>run; </pre>
		<br />
		<pre>data derived.<?php echo $dataset_name?> (label="<?php echo $dataset_label ?>") ; </pre>
		<pre>    retain <?php echo $vars ?> ;</pre>
		<pre>    set your_dataset_name ; </pre>
		<pre>    keep <?php echo $vars ?> ;</pre>
		<pre> </pre>
		<?php
     		foreach ($varlabels as $label) {
     			echo '<pre>    label  ' . $label . ' ; </pre>';  
     		}
		?>
		<pre>run; </pre>
		<br />
		<pre><font size="4" color="red">* --------------------- call macros ---------------- *; </font></pre>
		<pre>*** update this part to match the variable name in your dataset ***; </pre>
		<pre>%savefiles(final_ds = <?php echo $dataset_name; ?>); </pre>
		<pre>%qcdata(ds = derived.<?php echo $dataset_name; ?>, cutoff = 75, numcatvar = ); </pre>

		</div>
	</body>

</html>

