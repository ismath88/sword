<?php /* @author Sufy Nasri
 */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <style type='text/css'>
            #container{
                width:1000px;
                margin:auto;
            }
            #header{
                text-align:center;
                background: orange;
                font-size:25px;
				width: 1000px;
				margin: auto;
            }
            #searchzone{
                background: #e0e0e0;
                /*background: #fffff;*/
                border-bottom: 3px solid #fff;
            }

            #searchzone table{
				width: 800px;
                margin:auto;
            }
            #dropdown{
                width:170px;
            }
            #search{
                height:50px;
                text-align:right;
                position:relative;
                right:200px;

            }
            table tr td{
                border-bottom: 0;
            }
            #btn{
                position:relative;

            }
            #data_tbl{
                border:1px solid #000000;
            }
            #data_tbl th{
                border:1px solid #000000;
            }
            #data_tbl tr td{
                border-right: 1px solid #000000;
            }

        </style>
        <script type="text/javascript">
            function showhide() {
                var elem = document.getElementById("tbldata");
                var hide = elem.style.display === "none";
                if (hide) {
                    elem.style.display = "table";
                }
                else {
                    elem.style.display = "none";
            }
        </script>
    </head>
    <body>
	<table id="container" width="100%" border="0">
	  <tr>
		<td>		  <img src="../../images/layoutTemplete/ip_login.png" border="0" align="baseline" usemap="#Map" style="vertical-align:middle"></td>
	  </tr>
</table>

	
        <map name="Map">
          <area shape="rect" coords="417,400,500,432" href="http://localhost/SWORD/modules/custfeedback/CtwoAdminHomePage.php">
          <area shape="rect" coords="504,401,591,432" href="#">
        </map>
    </body>
</html>
