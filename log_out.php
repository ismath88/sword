<?php

session_start(); // NEVER forget this!

if(isset($_SESSION))
{ 

 unset($_SESSION['valid_id']);
 unset($_SESSION['valid_user']);
 unset($_SESSION['valid_id']);
 unset($_SESSION['user_role']);
 unset($_SESSION['valid_role']);
 unset($_SESSION['valid_time']);
 }

?>
<html>
<head>





<title>Swift v2.0 - Logout</title>
</head>
<body >

<div align="center">
  <p><br />
  <br />
    <span class="style2">Thank you  ! You have been successfully logged out.  </span></p>
  <p><a href="index.php" target="_self">Click here to login </a><br />
    <br />
  </p>
</div>

</body>
<!--  <td><input type="button" name="Back" value="Back" onClick="window.location.href='index.php'"></td> -->
</html>