
<?php
require("global.php");

session_start();

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$page = 'BBWeb.php';



if ($connDB = OCILogon(DB_USER, DB_PASS, DB_NAME)) {
  	
	}else{
  		$err = OCIError();
  		echo "Oracle Connect Error " . $err[text];
	}


// Define $myusername and $mypassword 
$username=$_POST['user_name']; 
$password=$_POST['user_pwd']; 

  if (!$_POST["user_name"] || !$_POST["user_pwd"])
        {
        die("You need to provide a username and password.");
        }
  
  // Create query
  /*$q = "SELECT * FROM SYSTEM_USER
        WHERE login='".$_POST["user_name"]."'
        AND password='".$_POST["user_pwd"]."'";*/

  $q ="SELECT * FROM SYSTEM_USER, user_group
        WHERE login='".$_POST["user_name"]."'
        AND password='".$_POST["user_pwd"]."'
        and SYSTEM_USER.role_id = user_group.role_id";
		
  echo 	$q;
  // Run query
  $query = OCIParse($connDB, $q);
  OCIExecute($query);    	

	
  if ( $obj =  OCIFetch($query)  )
        {
		$userID=ociresult($query,"ID");
  		$userName=ociresult($query,"LOGIN");
		$staffNO=ociresult($query,"STAFF_NO");
	  
		$userFullName=ociresult($query,"NAME");
  		//$userRoleID=ociresult($query,"ROLE_ID"); --asal
		$userDescription=ociresult($query,"DESCRIPTION");
		$lstatus=ociresult($query,"LOGIN_STATUS");
		
		if ($userDescription=="Administrator") { $userRoleID =1;}
		if ($userDescription=="RNO Supervisor"){ $userRoleID =3;}
		if ($userDescription=="RCC Executive") { $userRoleID =4;}
		
		// Login good, create session variables
        $_SESSION["valid_id"] = $userID;
	    $_SESSION["valid_user"] = $userName;
		$_SESSION["staff_no"] = $staffNO;
		$_SESSION["valid_fullname"] = $userFullName;
		$_SESSION["valid_role"] = $userRoleID;
		$_SESSION["user_role"] = $userDescription;
        $_SESSION["valid_time"] = time();
		
      
	    //aku tambah sini pd 13052011
		$strSQL1 =  "UPDATE  SYSTEM_USER SET LOGIN_STATUS = '1' WHERE ID =  '$userID' ";  

        $strSQL1 =OCIParse($connDB, $strSQL1 );
        $objExecute = OCIExecute($strSQL1);  
		//sampai sini
	
		header("Location: http://$host$uri/$page");
		
        }
  else
        {

		  //$val = 1;
		  $page = 'index.php?val=1';
		  header("Location: http://$host$uri/$page");

        }
 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
