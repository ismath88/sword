
<?php
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(isset($_SESSION))
{ 
 unset($_SESSION['valid_id']);
 unset($_SESSION['valid_user']);
 unset($_SESSION['valid_id']);
 unset($_SESSION['user_role']);
 unset($_SESSION['valid_role']);
 unset($_SESSION['valid_time']);
 }
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: max-age=0");
header("Pragma: no-cache"); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Login TM Swift v2.0.3</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="FW 8 DW 8 XHTML" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />

	<Script LANGUAGE="JavaScript">
		function checkform(form1) {
		 var user_name = document.form1.user_name.value;
		 var user_pwd = document.form1.user_pwd.value;
			if (user_name == "" ) {
				alert( "Please enter your User Name!" );
				document.form1.user_name.focus();
				return false ;
			}
			if (user_pwd == "" ) {
				alert( "Please enter your Password!" );
				document.form1.user_pwd.focus();
				return false ;
			}
		}
	</Script>


 </head>

 <body>
 <form name="form1" method="post" action="checklogin.php">

 <div class="loginscreen">
	<table background="images/bg_login.png" align="center" border=0 cellspacing=0 cellpadding=0>
		<tr><td>

			<div class="loginboxwhite">
			<table border="0" cellspacing=0 cellpadding=0>
				<tr>
					<td width="100" align="center" colspan="3">
					
					</td>
				</tr>
				<tr><td width="100" align="right">Username: </td><td width="220" align="center"><input type="text" name="user_name" id="user_name" maxlength="20"></td><td><img src="images/login_user.png"></td></tr>
				<tr><td width="100" align="right">Password: </td><td width="220" align="center"><input type="password" name="user_pwd" id="user_pwd" maxlength="20"></td><td><img src="images/login_lock.png"></td></tr>
				<tr><td width="100" align="center" colspan="3"><input type="submit" id="Submit" name="Submit" value="Login" onclick="return checkform(this);"> <input name="Close" type="reset" id="Close" value="Reset" /></td></tr>
			</table>
			</div>

		</td></tr>
	</table>
 </div>

<div class="footer">
	<a href="http://www.tmrnd.com.my">Copyright &copy; 2013 Telekom Research & Development Sdn Bhd. Best view with IE 9 and above </a></div>
			
</form>
 </body>
</html>
