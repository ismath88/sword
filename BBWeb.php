<?php
/*****************************************************************************************
Author: Faz & Hus
Author: Azrol
******************************************************************************************
Modification List:
Date			|By				|Description
11-02-2013		Azrol			Change layout, menu, header, graphic
18-02-2013		Azrol			Change the date from jscript (client-side date) into php function (server-side date)
26-02-2013		Azrol			Convert menu into XML
******************************************************************************************/
require("global.php");
date_default_timezone_set('Asia/Kuala_Lumpur'); 
include "Includes/Hijri_GregorianConvert.class";

session_start();
				
				if ($conn = OCILogon(DB_USER, DB_PASS, DB_NAME)) {
  				
				}else{
					$err = OCIError();
				echo "Oracle Connect Error " . $err[text];
			    }
				
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$page = 'index.php';

if (!$_SESSION["valid_user"])
{
	header("Location: http://$host$uri/$page");
}
														
if (!$conn) {
	echo("Could not connect to database");
}
else
//echo("Connect to database");
$urole=$_SESSION["valid_role"];

/* area name*/
$userName=$_SESSION["valid_user"];
$staffNO=$_SESSION["staff_no"];
$sqlArea = "select a.login as user_login, a.role_id, b.name as area_name
						from system_user a, area b, system_user_area c
						where a.id = c.system_user_id
						and b.id = c.area_id and a.login = '".$userName."'";

//echo $sqlArea;
$query2 = OCIParse($conn, $sqlArea);
OCIExecute($query2);    
while (OCIFetch($query2)){ 
	$areaName=ociresult($query2,"AREA_NAME");
	//$userLevel=ociresult($query2,"USER_LEVEL");

}
	//echo "http://$host$uri/fw/?clsid=UserMenuXml&userid=". $_SESSION["valid_id"] ."&owner=1";
	$str_getUserMenu =  file_get_contents("http://$host$uri/../?clsid=UserMenuXml&userid=". $_SESSION["valid_id"] ."&owner=1");
	//echo $str_getUserMenu;
	foreach ($http_response_header as $header) {   
		if (preg_match('#^Content-Type: text/xml; charset=(.*)#i', $header, $m)) {   
			switch (strtolower($m[1])) {   
				case 'utf-8':
					// do nothing
				break;

				case 'iso-8859-1':
				$str_getUserMenu = utf8_encode($str_getUserMenu);
				break;

				default:
				$str_getUserMenu = iconv($m[1], 'utf-8', $str_getUserMenu);
			}
			break;
		}
	}

$userLevel=1;		
//echo $areaName;
if(!$areaName){
	echo "area name not found in DB";
}		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>Welcome To TM Swift v2.0.3 <?=  "http://$host$uri/$page" ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="FW 8 DW 8 XHTML" />
  <script type="text/javascript" src="scripts/dropdowntabs.js"></script>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/menu.css" />
 </head>

 <body>
  
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr> 
     <td colspan="11" align="top" width="100%" background="images/new_layout/bg_banner.jpg"><img name="BBWeb_r1_c2" src="images/new_layout/bg_banner_logo1.jpg" border="0" id="BBWeb_r1_c2" alt="" /></td>
  </tr>

  <!-- MENU BAR -->
  <tr background="images/new_layout/bg_blue.jpg"> 
    <td> 
		<div id="navmenu" class="navtabs">
		<table width="100%" background="images/new_layout/bg_blue.jpg" border="0" cellspacing="0" cellpadding="0">
		<tr align="left" valign="center"> 
			<td><img src="images/new_layout/bg_blue_left.jpg"></td>
			<td><img src="images/new_layout/bg_btn_left.png"></td>
			<td width="100%" background="images/new_layout/bg_btn_center.jpg" align="left">
			&nbsp;&nbsp;
			<?php
				//$menus = simplexml_load_file('menu.xml');
				
				$menus = simplexml_load_string($str_getUserMenu);
				foreach ($menus->group as $menu) {
					echo '<a rel="'.$menu["name"].'">'.$menu["name"].'</a>'."&nbsp;&nbsp;|&nbsp;&nbsp;\n";
				}
				
			?>
			<a href="log_out.php" target="_parent">Logout</a>&nbsp;&nbsp;
			</td>
			<td background="images/new_layout/bg_blue_right.jpg"><img src="images/new_layout/bg_btn_right.png"></td>
			<td><img src="images/new_layout/bg_blue_right.jpg"></td>
        </tr>
		</table>
		</div>


		<!--
		userLevel = 1 ==> Module BAU shj
		userLevel = 2 ==> Module UNIFI shj
		userLevel = 3 ==> Module BAU & UNIFI 
		userLevel = 4 ==> Module DATA shj
		userLevel = 5 ==> Module DATA, UNIFI, BAU
		userLevel = 6 ==> Module DATA & BAU
		userLevel = 7 ==> Module DATA & UNIFI

		userRole = 1 ==> Administrator - Malaysia
		userRole = 2 ==> RCC Supervisor & Executive - State
		userRole = 3 ==> RNO Supervisor	 - Zone		
		userRole = 4 ==> Management	- Malaysia	
		userRole = 5 ==> RCC Agent	- Zone
		userRole = 6 ==> TMUC Agent	 - Malaysia		
		-->

		<?php
		foreach ($menus->group as $menu) {
			echo '<div id="'.$menu["name"].'" class="dropmenudiv_b" style="width: 230px;">'."\n";
			foreach ($menu->link as $link){
				echo '<a href="'.htmlentities($link->hyperlink).'" target="iframe">'.htmlentities($link->label) . '</a>'."\n";

				foreach ($link->subgroup as $subgroup){
					foreach ($subgroup->link as $link){
						echo '<a href="'.htmlentities($link->hyperlink).'" target="iframe">'.htmlentities($link->label) . '</a>'."\n";
					}
				}
			}
			echo "</div>\n";
		}
		?>

		<script type="text/javascript">
		//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
		tabdropdown.init("navmenu")
		</script>

	</td>
  </tr>
  <!-- MENU BAR END -->
  

  <!-- GREETINGS -->
  <tr> 
    <td> 
		<table border="0" cellspacing="0" cellpadding="0" background="images/new_layout/bg_white.jpg">
		<tr background="images/new_layout/bg_white.jpg">
			<td background="images/new_layout/bg_white.jpg"><img src="images/new_layout/bg_white.jpg"></td>
			<td valign="center" background="images/new_layout/bg_white.jpg">Welcome :  
			<?  
				$DateConv=new Hijri_GregorianConvert;
				echo $_SESSION["valid_fullname"] . " | " . $_SESSION["user_role"] .  " | " . date("l, d F Y") . "M | " . $DateConv->GregorianToHijri(date("Y/m/d"),"YYYY/MM/DD"); 
			?>
			</td>
		</tr>
		</table>
	</td>
  </tr>
  <!-- GREETINGS END -->



  <!-- CONTENT -->
  <tr> 
    <td align="center"> 
		 
			<iframe height='700'  width="100%" name="iframe" frameborder="1" src="../?clsid=IPAdminHomePage" align="top" hspace="0"></iframe>
			<!--iframe height='700'  width="100%" name="iframe" frameborder="1" src="fw/?clsid=LandingPage" align="top" hspace="0"></iframe-->

	</td>
  </tr>
  <!-- CONTENT END -->

  </table>

<div class="footer">
  <table height="50" bgcolor="#F0F0F0">
  <tr bgcolor="#F0F0F0"> 
		<td align="center" width="100%"><a href="http://www.tmrnd.com.my">&copy; Telekom Research & Development Sdn Bhd </a></td>
  </tr>
  </table>
</div>

 </body>
</html>
