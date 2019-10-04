<?php
/*****************************************************************************************
Author: Mohd Khairi Abdul Rahman
Creation Date  : 16/07/2012
******************************************************************************************
Modification List:
Date	|By				|Description
******************************************************************************************/
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: max-age=0");
header("Pragma: no-cache"); 

 
?>

<?php

require("global.php");

if ($conn = OCILogon(DB_USER, DB_PASS, DB_NAME)) {
  
	}else{
  		$err = OCIError();
  		echo "Oracle Connect Error " . $err[text];
	}

?>
<?php 
session_start(); // NEVER forget this!
// Session parameters setup
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/\\');
$page  = 'index.php';

// Check session to validate user
if(!isset($_SESSION["valid_id"]))
{
die("Your session has been expired, please <a href=http://$host$uri/$page target=_parent>re-login</a>");
  //echo "<meta http-equiv=Refresh content=3;url=security.php>";
}

$userID = $_SESSION["valid_id"];

?> 
<?php

$userID = $_SESSION["valid_id"];
$userName=$_SESSION["valid_user"];
$roleID=$_SESSION["valid_role"];

$filter=$_GET["filter"];
$filter1=$_GET["filter1"];
$ref=$_GET["ref"];
$tapis=$_GET["tapis"];
$adil=$_GET["adil"];
// echo "username=".$userName;

$sqlArea = "select a.login as user_login, a.role_id, b.name as area_name,b.id as areaid,b.type as areatype
						from system_user a, area b, system_user_area c
						where a.id = c.system_user_id
						and b.id = c.area_id and a.login = '".$userName."'";
			
			//echo $sqlArea;
			$query2 = OCIParse($conn, $sqlArea);
			OCIExecute($query2);    
			while (OCIFetch($query2))
							{ 
							$areaName=ociresult($query2,"AREA_NAME");
							$roleID=ociresult($query2,"ROLE_ID");
							$areaType=ociresult($query2,"AREATYPE");	
							$areaId=ociresult($query2,"AREAID");
							}	
 

?>



<html>
<head>
<link href="template.css" rel="stylesheet" type="text/css" />
<script src="scripts/jquery-1.8.3.min.js"></script>
<script src="sorttable.js"></script>
<script  src="calendar_us.js"></script>
<link rel="stylesheet" href="calendar.css">
</head>

<body>

<form name="form" method="post" action=""> 


<table align="center" width="100%" border="1" class="DataTable" >
    <tr class="DataBack">
	  <td width="62" align="left">
	
       
          <input name="refreshButton" id="refreshButton" type="button" value="Refresh">
		
      <td width="158" align="center"> <p align="left">
           
         
			
          
            <font class="DataHText"><strong>Status:</strong></font>
        <select name="filter" id="filterByStatus">
           <!--option value="0" >Select Status</option-->
           <option value="ALL" <?php if($_GET['filterByStatus']=='ALL') { echo 'selected'; }?>>All</option>
           <option value="RELEASE" <?php if($_GET['filterByStatus']=='RELEASE') { echo 'selected'; }?>>Release</option>
           <option value="ASSIGNED" <?php if($_GET['filterByStatus']=='ASSIGNED') { echo 'selected'; }?>>Assigned</option>
           <option value="PROCESSING" <?php if($_GET['filterByStatus']=='PROCESSING') { echo 'selected'; }?>>Pending Assign</option>
          <option value="PENDING CANCELLATION" <?php if($_GET['filterByStatus']=='PENDING CANCELLATION') { echo 'selected'; }?>>Unscheduled</option>
           <!--option value="INPROGRESS">Inprogress</option-->
        </select>
     
 
     </td>
	   <td width="253" align="left">
	   
	   <?php 
	   $appDate="";
	   if(isset($_GET['filterByDate']))
	   {
	   $appDate=$_GET['filterByDate'];
	   }
	   ?>
        <font class="DataHText"><strong>Appointment Date:</strong></font>
        <input type="text" name="appointmentDate" id="appointmentDate"  size="15" value="<?php echo $appDate;  ?>" readonly>
		<script>
		 new tcal ({
				// form name
				'formname': 'form',
				// input name
				'controlname': 'appointmentDate'
			});
			</script>
     	  </td>
	  
	    <td width="186" align="left">
	
         <font class="DataHText"><strong>PTT:</strong></font>
	
        <select name="ptt" id="ptt" onChange="javascript:loadOptions(this.value,'zone')">
            <?php 
		  
		   //$exchangeListArray=getExchangeList($conn,$roleID,$areaName);
		   $pttList=getPTTList($conn);
		 
		   $pttListArray=array_unique($pttList);
		   ?>
		   <option value=''>Select..</option>
		   <?php 
		   if($_GET['ptt']!='')
		   {
		   $selectedPTT=$_GET['ptt'];
		   }
		   else
		   {
		   $selectedPTT="";
		   }
		   
		   
		   foreach($pttListArray as $k=>$v){
              if($selectedPTT==$v)
				{
				$selectedPttOption="  selected='selected'";
				}
				else
				{
                $selectedPttOption="";
				}				
		   
		         ?>
		   <option value="<?php echo $v;?>"<?php echo $selectedPttOption;?>><?php echo getBuildingName($conn,$v); ?></option>
		   <?php } ?>
        </select>
     
      </td>
	  <td width="178" align="left">
	
         <font class="DataHText"><strong>Zone:</strong></font>
		 <img id="loader2" style="display:none"   src="../Fulfillment/img/loader.gif">
        <select name="zone" id="zone" onChange="javascript:loadOptions(this.value,'building')">
          <option value="">Select..</option>
      </select>      </td>
	  
	   <td width="179" align="left">
	
         <font class="DataHText"><strong>Exchange:</strong></font>
		 <img id="loader3" style="display:none" src="../Fulfillment/img/loader.gif">
        <select name="building" id="building" style="width: 60px">
           <option value="">Select..</option>
        </select>
     
      <input type="button" id="submitButton" name="submitButton" value="Go"></td>
	  
	  
	  
   
      <td width="184"><font class="DataHText"><strong>Search </strong> </font>
        <input name="txtKeyword" type="text" id="txtKeyword" value="<?php echo strtoupper($_GET["q"])?>">
      <input name="search" id="search" type="button" value="search">	  </td>
	   
    </tr>
  </table>
  
   
 <!-- START TABLE OF DATA ORDER INFO LIST -->
  <table class="sortable" width ="100%" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFCCCC" style="border:1px solid #00ADD9" >
   <tr class="DataHBack">
    
      <th width="9%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Order Number </font></strong></div></th>
 <th width="4%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Priority</font></strong></div></th>
  <th width="4%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Current Status</font></strong></div></th>
          
   
                         
    <th width="7%" style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Appointment<br>
    </font></strong></div></th>    
        <th width="4%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">HSBA Flag</font></strong></div></th>  
      <th width="3%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Order Type</font></strong></div></th>   
             
              <th width="4%" style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Segmt. Group</font></strong></div></th>  
              
                <th width="4%" style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Cabinet ID</font></strong></div></th> 
                                <th width="3%" style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">DP Type</font></strong></div></th> 
                                                         <th width="4%" style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">DP Location</font></strong></div></th> 
              
      <th width="4%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Exch.</font></strong></div></th>    
				  			      <th width="5%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Order Zone</font></strong></div></th> 
                   <th width="3%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Installer Zone</font></strong></div></th>   
				   
                     <th width="6%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center"><strong><font class="DataHText">Contractor ID</font></strong></div></th>  
                     
                      <th width="6%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center"><strong><font class="DataHText">Contractor Name</font></strong></div></th> 
                     
                     
      <th width="5%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center"><strong><font class="DataHText">Customer Name</font></strong></div></th> 
      <th width="5%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center"><strong><font class="DataHText">Op Err Msg</font></strong></div></th>       
                            
                             
                            
      <th width="20%"  style="border-right:1px solid #00ADD9;border-bottom:1px solid #00ADD9"> <div align="center" ><strong><font class="DataHText">Created Date</font></strong></div></th>
   
  
   <?php
			$sqlArea = "select a.login as user_login, a.role_id, b.name as area_name,b.id as areaid,b.type as areatype
						from system_user a, area b, system_user_area c
						where a.id = c.system_user_id
						and b.id = c.area_id and a.login = '".$userName."'";
			
			//echo $sqlArea;
			$query2 = OCIParse($conn, $sqlArea);
			OCIExecute($query2);    
			while (OCIFetch($query2))
							{ 
							$areaName=ociresult($query2,"AREA_NAME");
							$roleID=ociresult($query2,"ROLE_ID");
							$areaType=ociresult($query2,"AREATYPE");	
							$areaId=ociresult($query2,"AREAID");
							}	
							
							
							
				//get login id
                $sqlLogin= "SELECT staff_no FROM SYSTEM_USER WHERE ID= '".$userID."'";				
	         	//echo $sqlLogin;		
				$queryLogin = OCIParse($conn, $sqlLogin);
				OCIExecute($queryLogin);    	
							
				while (OCIFetch($queryLogin)){ 							
							$staffLogin=ociresult($queryLogin,"STAFF_NO");
						//echo $staffLogin;	
								
                }
	     /*
		      $sqlPost= "SELECT staff.position_id FROM SYSTEM_USER, STAFF WHERE system_user.staff_no=staff.staff_no and SYSTEM_USER.ID= ".$userID."";				
		//echo $sqlPost;		
				$queryPost = OCIParse($conn, $sqlPost);
				OCIExecute($queryPost);    	
							
				while (OCIFetch($queryPost)){ 							
							$post=ociresult($queryPost,"POSITION_ID");
							echo $post;
							
                           }
						   */

	?>
   
	<?php

	$sqlCMD ="SELECT order_number,
  priority,
  order_status,
  slot_start                                     AS appointment_datetime,
  TO_CHAR(ACTIVITY_PLANNED_START, 'DD-MON-YYYY') AS ACTIVITY_PLANNED_START,
  hsba_flag,
  order_type,
  upper(segment) AS segment,
  exchange,
  upper(rno_region)  AS rno_region,
  upper(rno_region2) AS zone,
  team_id,
  customer_name,
  created_date,
  addr_indicator,
  source_svc,
  cabinet_id,
  dp_type,
  dp_location,
  op_error_message
	FROM TABLE(f_unify_fullfilment)
	WHERE exchange IN
	 (SELECT AREA.NAME
	FROM SYSTEM_USER
	INNER JOIN SYSTEM_USER_AREA
	ON SYSTEM_USER.ID = SYSTEM_USER_AREA.SYSTEM_USER_ID
	INNER JOIN AREA
	ON SYSTEM_USER_AREA.AREA_ID = AREA.ID
	where login = '$userName')
	union
	SELECT order_number,
  priority,
  order_status,
  slot_start                                     AS appointment_datetime,
  TO_CHAR(ACTIVITY_PLANNED_START, 'DD-MON-YYYY') AS ACTIVITY_PLANNED_START,
  hsba_flag,
  order_type,
  upper(segment) AS segment,
  exchange,
  upper(rno_region)  AS rno_region,
  upper(rno_region2) AS zone,
  team_id,
  customer_name,
  created_date,
  addr_indicator,
  source_svc,
  cabinet_id,
  dp_type,
  dp_location,
  op_error_message
FROM TABLE(f_unify_fullfilment)
WHERE Team_id IN
  (SELECT staff_no FROM staff WHERE supervisor_staff_no = '$staffLogin'
  UNION
  SELECT vendorid FROM installer_info WHERE supervisor_id = '$staffLogin'
  )
ORDER BY created_date DESC 
   ";

	?>
	
<?php	

if(isset($_GET['filter'])){  // list by status

$q=strtoupper($_GET['filter']);

$filterByStatus=" ";

$filterByDate=" ";

$filterByExchange=" ";

$statusCondition=" ";

$dateCondition=" ";

$exchangeCondition=" ";



if(isset($_GET['filterByDate']) and $_GET['filterByDate']=='')
{

$dateCondition=" ";
}

if(isset($_GET['filterByDate']) and $_GET['filterByDate']!='')
{


$filterByDate=date("d-M-Y", strtotime($_GET['filterByDate'])); 
$filterByDate=strtoupper($filterByDate);
$dateCondition="  AND TO_CHAR(ACTIVITY_PLANNED_START, 'DD-MON-YYYY')='$filterByDate'";

}




if(isset($_GET['filterByStatus']) and $_GET['filterByStatus']!='')
{
 $filterByStatus=$_GET['filterByStatus'];
 $statusCondition="  AND UPPER(order_status)='$filterByStatus'";
}



if(isset($_GET['filterByExchange']) and $_GET['filterByExchange']!='')
{
 $filterByExchange=$_GET['filterByExchange'];
 $exchangeCondition="  AND EXCHANGE='$filterByExchange'";
}

if(strtoupper($q)=="SEARCH")
{	

$value = strtoupper($_GET["q"]);

$searchCondition="AND  UPPER(order_number) LIKE '%".$value."%'";
}

if($filterByStatus=="ALL")
{
$statusCondition=" ";
}


$sqlCMD ="SELECT order_number,
  priority,
  order_status,
  slot_start                                     AS appointment_datetime,
  TO_CHAR(ACTIVITY_PLANNED_START, 'DD-MON-YYYY') AS ACTIVITY_PLANNED_START,
  hsba_flag,
  order_type,
  upper(segment) AS segment,
  exchange,
  upper(rno_region)  AS rno_region,
  upper(rno_region2) AS zone,
  team_id,
  customer_name,
  created_date,
  addr_indicator,
  source_svc,
  cabinet_id,
  dp_type,
  dp_location,
  op_error_message
	FROM TABLE(f_unify_fullfilment)
	WHERE exchange IN
	 (SELECT AREA.NAME
	FROM SYSTEM_USER
	INNER JOIN SYSTEM_USER_AREA
	ON SYSTEM_USER.ID = SYSTEM_USER_AREA.SYSTEM_USER_ID
	INNER JOIN AREA
	ON SYSTEM_USER_AREA.AREA_ID = AREA.ID
	where login = '$userName')
	
	$statusCondition  $dateCondition  $exchangeCondition $searchCondition
	
	
	union
	SELECT order_number,
  priority,
  order_status,
  slot_start                                     AS appointment_datetime,
  TO_CHAR(ACTIVITY_PLANNED_START, 'DD-MON-YYYY') AS ACTIVITY_PLANNED_START,
  hsba_flag,
  order_type,
  upper(segment) AS segment,
  exchange,
  upper(rno_region)  AS rno_region,
  upper(rno_region2) AS zone,
  team_id,
  customer_name,
  created_date,
  addr_indicator,
  source_svc,
  cabinet_id,
  dp_type,
  dp_location,
  op_error_message
FROM TABLE(f_unify_fullfilment)
WHERE Team_id IN
  (SELECT staff_no FROM staff WHERE supervisor_staff_no = '$staffLogin'
  UNION
  SELECT vendorid FROM installer_info WHERE supervisor_id = '$staffLogin'
  )
  $statusCondition  $dateCondition  $exchangeCondition $searchCondition
  
ORDER BY created_date DESC ";
		







} // END OF ISSET FILTER 

			//echo $sqlCMD;
			$query = OCIParse($conn, $sqlCMD);
			OCIExecute($query);    	
			$nume=0;
			while($Result = oci_fetch_array($query,OCI_BOTH))
			{ 
					$nume =$nume + 1;
					if($bgcolor=='#f1f1f1')
					{$bgcolor='#ffffff';}
					else{$bgcolor='#f1f1f1';}
					
			$sourcesvc = ociresult($query,"SOURCE_SVC");	
			$appointment_datetime = ociresult($query,"APPOINTMENT_DATETIME");
			//echo $appointment_datetime
             $appointment_datetime = explode(" "  , $appointment_datetime);
			// echo $appointment_datetime[0]; // piece1
            //echo $appointment_datetime[1]; // piece2
			
			$team_id=ociresult($query,"TEAM_ID");
			$rnoregion=ociresult($query,"RNO_REGION");
			
			//echo $team_id;
	
	 $ORDER_STATUS1 =ociresult($query,"ORDER_STATUS");
		//echo $ORDER_STATUS1;
		
		if ( $ORDER_STATUS1== 'Processing'){
		 $ORDER_STATUS1= 'Pending Assign';
		
		}
		
		
        
		if ( $ORDER_STATUS1== 'Pending Cancellation'){
		 $ORDER_STATUS1= 'Unscheduled';
		
		}
			
			
			$created_date = ociresult($query,"CREATED_DATE");
			 $created_date = explode("."  , $created_date);
			$c_date = $created_date[3];
			 $cc_date = explode(" "  , $c_date);
		
			?>
	</tr>
 
			<tr  class="DataBack">		
              <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
              
              <?php 
			  $sourcesvc =ociresult($query,"SOURCE_SVC");
			   $addr_indicator =ociresult($query,"ADDR_INDICATOR");

			 //  echo  $addr_indicator;
	            if ($sourcesvc == 3  ) { ?>
              	  <a href=""  onClick=	"window.open('OrderDetailunifi.php?did=<?=ociresult($query,"ORDER_NUMBER");?>&addrindicator=<?=ociresult($query,"ADDR_INDICATOR");?>&ordertype=<?=ociresult($query,"ORDER_TYPE");?>','popup','width=900,height=1000,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0'); return false">	
       		   <?=$Result["ORDER_NUMBER"];  ?>
			 </a>
             		 </a>
	  <?php } else { ?>
       <a href=""  onClick=	"window.open('OrderDetailunifi.php?did=<?=ociresult($query,"ORDER_NUMBER");?>&addrindicator=<?=ociresult($query,"ADDR_INDICATOR");?>&ordertype=<?=ociresult($query,"ORDER_TYPE");?>','popup','width=1000,height=900,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0'); return false">	
       		   <?=$Result["ORDER_NUMBER"]; ?>
              </a>
			  </font></span></div></td>
              
                <?php } ?>
              
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			    <?=$Result["PRIORITY"];  ?>
			  </font></span></div></td>
              <td height="21" valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			  <?php echo $ORDER_STATUS1; ?>
			  </font></span></div></td>
   
              
               <td  sorttable_customkey="<?php echo date('Ymd',strtotime($Result["ACTIVITY_PLANNED_START"])); ?>" valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?php echo $Result["ACTIVITY_PLANNED_START"];  ?>
		      </font></span></div></td> 
              
              
              <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["HSBA_FLAG"];  ?>
		      </font></span></div></td> 
              
              <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["ORDER_TYPE"];  ?>
		      </font></span></div></td> 
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["SEGMENT"];  ?>
		      </font></span></div></td> 
              
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["CABINET_ID"];  ?>
		      </font></span></div></td> 
              
               <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["DP_TYPE"];; ?>
		      </font></span></div></td> 
              
               <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["DP_LOCATION"];  ?>
		      </font></span></div></td> 
              
              
              
                 <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?=$Result["EXCHANGE"];  ?>
		      </font></span></div></td> 
			 
			
			  
			     <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		        <?php

			  	echo ($ORDER_STATUS1=="Pending Assign" || $ORDER_STATUS1=="")? "" : $Result["ZONE"];

				?>
		      </font></span></div></td> 
              
                 <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
		         <?php

			  	echo ($ORDER_STATUS1=="Pending Assign" || $ORDER_STATUS1=="")? "" : $Result["RNO_REGION"];

				?>
		      </font></span></div></td> 
              
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			    <?=$Result["TEAM_ID"];  ?>
			  </font></span></div></td>
				 <?php 
	    $nama="";
				 $strIns= "SELECT NAME from  installer_info where passcardno  = '$team_id' "; 
						$queryIns = OCIParse($conn, $strIns);
						OCIExecute($queryIns); 
			 
						while (OCIFetch($queryIns)){ 	
				
							
							$nama = ociresult($queryIns,"NAME");
							}
						
					if($nama==""){
						$strStf= "SELECT NAME from staff where staff_no  = '$team_id' "; 
						$queryStf = OCIParse($conn, $strStf);
						OCIExecute($queryStf); 
			 
						while (OCIFetch($queryStf))	
				
							{ 
							$nama = ociresult($queryStf,"NAME");
							}
					}
					
			?> 
              
               <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			   <?php echo $nama; ?>
			  </font></span></div></td>

              
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			    <?=$Result["CUSTOMER_NAME"]; ?>
			  </font></span></div></td>
              
              
                <td valign="top"><div align="center"><span class="style26"><font class="DataBlue">
			    <?=$Result["OP_ERROR_MESSAGE"];  ?>
			  </font></span></div></td>
              
			  <td sorttable_customkey="<?php echo date('YmdHis',strtotime($created_date[0]. ":".$created_date[1].":".$created_date[2]." ". $cc_date[1])); ?> valign="top" width="20%"><div align="center"><span class="style26"><font class="DataBlue">
			 <?php echo $created_date[0]. ":".$created_date[1].":".$created_date[2]." ". $cc_date[1]; ?>
        
			  </font></span></div></td>		
			 
			</tr>
    		<?php 
			} //end of while

?>
</table>



<?php

function getExchangeList($conn,$userName)
{


	/*$sqlCMDExch ="SELECT distinct exchange FROM table(f_unify_fullfilment) WHERE  
					exchange IN (SELECT AREA.NAME
					FROM SYSTEM_USER
					INNER JOIN SYSTEM_USER_AREA
					ON SYSTEM_USER.ID = SYSTEM_USER_AREA.SYSTEM_USER_ID
					INNER JOIN AREA
					ON SYSTEM_USER_AREA.AREA_ID = AREA.ID
					where login = '$userName')
                    ";*/
 	$sqlCMDExch ="SELECT AREA.NAME as EXCHANGE
					FROM SYSTEM_USER
					INNER JOIN SYSTEM_USER_AREA
					ON SYSTEM_USER.ID = SYSTEM_USER_AREA.SYSTEM_USER_ID
					INNER JOIN AREA
					ON SYSTEM_USER_AREA.AREA_ID = AREA.ID
					where login ='$userName'"; 
		//echo $sqlCMDExch;
		$exchange=array();
		$query = OCIParse($conn, $sqlCMDExch);
		OCIExecute($query); 
		while($Result = oci_fetch_array($query,OCI_BOTH))
			{
			$exchange[]=$Result['EXCHANGE'];
			}
			return $exchange;
		
}

function getPTTList($conn)
{


$sqlCMDPTT ="SELECT ID,PARENT_ID,NAME FROM AREA WHERE UPPER(TYPE)='PTT'"; 
		//echo $sqlCMDExch;
		$ptt=array();
		$query = OCIParse($conn, $sqlCMDPTT);
		OCIExecute($query); 
		while($result = oci_fetch_array($query,OCI_BOTH))
			{
			$ptt[]=$result['ID'];
			}
			return $ptt;
}

 
	
	
	function getBuildingName($conn,$buildingID = '') {

       $sqlCMD="SELECT NAME FROM AREA WHERE ID=$buildingID"; 
		$query = OCIParse($conn, $sqlCMD);
		OCIExecute($query); 
       while($result = oci_fetch_array($query,OCI_BOTH))
			{
			$buildingName=$result['NAME'];
			}
			return $buildingName;
    }

?>


<font face='Verdana' size='2' color=blue>Total Record <?php echo $nume?> </font>
<!-- END TABLE OF DATA ORDER INFO LIST -->
</form>  
<?php 

			
				if($_GET['ptt']!='')
				{
				$pttID=$_GET['ptt'];
				}
				else
				{
				$pttID="0";
				}
				if($_GET['zone']!='')
				{
				$zoneID=$_GET['zone'];
				}
				else
				{
				$zoneID="0";
				}
				
				if($_GET['building']!='')
				{
				$buildingID=$_GET['building'];
				}
				else
				{
				$buildingID="0";
				}
				
				
				?>
       <script type="text/javascript">
	   
			$("#refreshButton").click(function () {
				
 				 window.location.href = "OrderInfoList_new.php";
			
				});
				$("#interV").change(function () {
					 intv = $("#interV option:selected").val();
					 q = $("#filter option:selected").val();
					  window.location.href = "OrderInfoList.php?filter="+q;
					  //$pg = "OrderInfoList.php?filter="+q;
					  clearInterval(refreshId);
					  refreshId = setInterval(function() {
					  if(intv!=""){
					  window.location.href = "OrderInfoList.php?filter="+q;
					
					  }
				   }, intv);
				   $.ajaxSetup({ cache: false });
				});
				
				$("#search").click(function () {
				var q = $('#txtKeyword').val();
				 q = $.trim(q);
				 window.location.href = "OrderInfoList_new.php?filter=SEARCH&q="+q;
				 
				
				});
		
	 $('#submitButton').click(function () {
	 
                                  var filterID=$(this).attr('id'); 
                                  
                                 
                                  var filterByStatus=$('#filterByStatus').val();
								  
								   var filterByDate=$('#appointmentDate').val();
                             
                                  var filterByExchange = $('#building').val();
								  
								  var pttValue=$('#ptt').val();
								  var zoneValue=$('#zone').val();
                             
                               
                                  var locPath=location.pathname;
								  
                                  var q='';
                                   if(filterByStatus!='' || filterByDate!='' || filterByExchange!='' )
                                   {
                                     var q='filter';
                                   }

                                
                                   if(q!='')
                                    {
                                    
                                    window.location.href ='OrderInfoList_new.php?filterByStatus='+filterByStatus+'&filterByDate='+filterByDate+'&filterByExchange='+filterByExchange+'&filter='+q+'&ptt='+pttValue+'&zone='+zoneValue+'&building='+filterByExchange;
                                    }
                                     
                                    
                                 
                                     
				});
	         
	         
				function loadOptions(id,index){


           
                   var userName='<?php echo $userName; ?>';


                        $.ajax({
                                url: 'OrderInfoAjaxResponse_new.php?index='+index+'&id='+id+'&userName='+userName,
                    type: 'POST',
                        dataType: 'html',
                        cache: false,
                        beforeSend: function () {

                    if(index=='ptt')
                    {
                    setTimeout(function(){ $('#loader1').show(),$('#'+index).hide(),300});
                    }
                    else if(index=='zone')
                    {
                    setTimeout(function(){ $('#loader2').show(),$('#'+index).hide(),300});
                    }
                    else if(index=='building')
                    {
                    setTimeout(function(){ $('#loader3').show(),$('#'+index).hide(),300});
                    }




                        },
                                success: function(data) {

                    if(index=='ptt')
                    {
                    setTimeout(function(){ $('#loader1').hide(),$('#'+index).show(),300}); 
                    }
                    else if(index=='zone')
                    {
                    setTimeout(function(){ $('#loader2').hide(),$('#'+index).show(),300}); 
                    }
                    else if(index=='building')
                    {
                    setTimeout(function(){ $('#loader3').hide(),$('#'+index).show(),300}); 
                    }



                                        $('#'+index).html(data);
                                        $('#'+index).show();
                                }
                        })


                }
				
				
				
				
				
				function loadOptions1(id,index){

                  var zoneId=<?php echo $zoneID;?>;
				
				var zoneIndex='building';
				
				
				
				var pttId=<?php echo $pttID;?>;
				
				var pttIndex='zone';
				
				var buildingId='<?php echo $buildingID;?>';
				
           
                   var userName='<?php echo $userName; ?>';


                        $.ajax({
                                url: 'OrderInfoAjaxResponse_new.php?index='+index+'&id='+id+'&userName='+userName+'&zone='+zoneId+'&building='+buildingId,
                    type: 'POST',
                        dataType: 'html',
                        cache: false,
                        beforeSend: function () {

                    if(index=='ptt')
                    {
                    setTimeout(function(){ $('#loader1').show(),$('#'+index).hide(),300});
                    }
                    else if(index=='zone')
                    {
                    setTimeout(function(){ $('#loader2').show(),$('#'+index).hide(),300});
                    }
                    else if(index=='building')
                    {
                    setTimeout(function(){ $('#loader3').show(),$('#'+index).hide(),300});
                    }




                        },
                                success: function(data) {

                    if(index=='ptt')
                    {
                    setTimeout(function(){ $('#loader1').hide(),$('#'+index).show(),300}); 
                    }
                    else if(index=='zone')
                    {
                    setTimeout(function(){ $('#loader2').hide(),$('#'+index).show(),300});
				
                    }
                    else if(index=='building')
                    {
					 
                    setTimeout(function(){ $('#loader3').hide(),$('#'+index).show(),300}); 
					 
                    }


                                         
                                        $('#'+index).html(data);
                                        
                                }
                        })


                }
		
		</script>
		 <?php
			if($_GET['ptt']!='')
				{
	             echo "<script type='text/javascript'>  
				         loadOptions1($pttID,'zone');
				         loadOptions1($zoneID,'building');
						 </script>";
						  
				}
			
			?>
			
</body>
</html>