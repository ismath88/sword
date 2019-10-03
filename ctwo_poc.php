<?php
/*

 * POC for CTWO
 * 
 * @author Ismath Khan
 *  */
?>
<html>
    <head>
        <style type="text/css">
            th{
                color:#fff;
            }
            a{
                text-decoration: none;
            }
            #info{
                color:#0000ff;
            }
            
        </style>
        <script type="text/javascript">
            function showinfo() {
                w = '850';
                h = '500';
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings =
                        'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
                window.open("ctwo_custdetail.php", " ", settings);
            }
            function showhistory() {
                window.open("ctwo_history.php", " ", "width=800,height=400, location=no,titlebar=0,toolbar=0,menubar=0,status=0");
            }
        </script>
    </head>
    <body>
        <table border="1" cellpadding="3" cellspacing="0" align="center"><tr style="background: blueviolet" align="center"><th>ctwo_id</th><th>Time created</th><th>TT No</th><th>Exch</th><th class="service">Service No</th><th>Team name</th><th>Customer Name</th>
                <th>Aging</th><th>Priority</th><th>Status</th><th>Rating Value</th><th id="act">Action</th>


            </tr>
            <tr align="center"><td>1</td><td>06-12-2013 4:30pm</td><td><a href="ctwo_custinfo.php">123</a></td><td>123</td><td><a id="info" onclick="showinfo();">12</a></td><td>Team A</td><td>Sufy</td><td>123</td><td>p01</td><td>Complete</td>
                <td>4</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>

            <tr align="center"><td>2</td><td>06-12-2013 4:40pm</td><td><a href="ctwo_custinfo.php">232</a></td><td>1234</td><td><a id="info" onclick="showinfo();">123</a></td><td>Team B</td><td>Khan</td><td>124</td><td>p02</td><td>Incomplete</td>
                <td>1</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"</a></a><a onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>

            <tr align="center"><td>3</td><td>06-12-2013 5:30pm</td><td><a href="ctwo_custinfo.php">378</a></td><td>1256</td><td><a id="info"  onclick="showinfo();">124</a></td><td>Team C</td><td>Rizal</td><td>125</td><td>P03</td><td>Complete</td>
                <td>3</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>

            <tr align="center"><td>4</td><td>06-12-2013 6:50pm</td><td><a href="ctwo_custinfo.php">423</a></td><td>12675</td><td><a id="info" onclick="showinfo();">125</a></td><td>Team D</td><td>Zaini</td><td>126</td><td>P04</td><td>Incomplete</td>
                <td>2</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>

            <tr align="center"><td>5</td><td>07-12-2013 10:30am</td><td><a href="ctwo_custinfo.php">534</a></td><td>2134</td><td><a id="info" onclick="showinfo();">213</a></td><td>Team A</td><td>Haja Maideen</td><td>127</td><td>P05</td><td>Delay</td>
                <td>2</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>

            <tr align="center"><td>6</td><td>07-12-2013 11:30am</td><td><a href="ctwo_custinfo.php">612</a></td><td>1349</td><td><a id="info" onclick="showinfo();">278</a></td><td>Team B</td><td>Monsoor</td><td>128</td><td>P06</td><td>Complete</td>
                <td>1</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a id="info" onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>
            <tr align="center"><td>7</td><td>07-12-2013 12:00pm</td><td><a href="ctwo_custinfo.php">689</a></td><td>2899</td><td><a id="info" onclick="showinfo();">312</a></td><td>Team C</td><td>Farook</td><td>210</td><td>P07</td><td>Incomplete</td>
                <td>1</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a id="info" onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>
            <tr align="center"><td>8</td><td>07-12-2013 2:30pm</td><td><a href="ctwo_custinfo.php">711</a></td><td>3456</td><td><a id="info"="showinfo();">308</a></td><td>Team D</td><td>Arjun</td><td>290</td><td>P08</td><td>Complete</td>
                <td>4</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a id="info" onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>
            <tr align="center"><td>9</td><td>07-12-2013 4:00pm</td><td><a href="ctwo_custinfo.php">812</a></td><td>4321</td><td><a id="info"onclick="showinfo();">415</a></td><td>Team B</td><td>Mohammed</td><td>342</td><td>P09</td><td>Incomplete</td>
                <td>1</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a id="info" onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>
            <tr align="center"><td>10</td><td>08-12-2013 11:00am</td><td><a href="ctwo_custinfo.php">925</a></td><td>5221</td><td><a id="info" onclick="showinfo();">5233</a></td><td>Team C</td><td>Saleem Khan</td><td>342</td><td>P10</td><td>Incomplete</td>
                <td>1</td><td><a href="ctwo_update.php"><input type="submit" name="Update" value="Update"></a><a id="info" onclick="showhistory();"><input type="submit" name="History" value="History"></a></td></tr>


        </table>
        <script language="javascript">
            var popupWindow = null;
            function centeredPopup(url, winName, w, h, scroll) {
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings =
                        'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
                popupWindow = window.open(url, winName, settings)
            }
        </script>

       
    </body>

</html>