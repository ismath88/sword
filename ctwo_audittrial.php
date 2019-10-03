<?php
/*

 * POC for CTWO Audit trial
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
        <div style="width:300px;margin:auto;"><input typ="text"/><input type="submit" value="Find"/></div>
        <table border="1" cellpadding="3" cellspacing="0" align="center"><tr style="background: #0000ff" align="center"><th>TT No</th><th>Team Name</th><th>Date Time</th><th>Previous Status</th><th>New Status</th>

            </tr>
            <tr align="center"><td>1-768787</td><td>1KUH5378738</td><td>07-01-2014 9:00am</td><td>Reshedule</td><td>Team A</td>
            <tr align="center"><td>2-454524545</td><td>1KUH5378738</td><td>07-01-2014 11:00am</td><td>Reshedule</td><td>Team B</td>
            <tr align="center"><td>3-56747467467</td><td>1KUH5378738</td><td>08-01-2014 9:00am</td><td>Reshedule</td><td>Team C</td>

            <tr align="center"><td>4-565435653</td><td>1KUH5378738</td><td>08-01-2014 11:00am</td><td>Reshedule</td><td>Team D</td>

            <tr align="center"><td>5-3563535355</td><td>1KUH5378738</td><td>08-01-2014 11:50am</td><td>Reshedule</td><td>Team A</td>

            <tr align="center"><td>6-3635635656</td><td>1KUH5378738</td><td>08-01-2014 12:10am</td><td>Reshedule</td><td>Team B</td>
            <tr align="center"><td>7-56553565</td><td>1KUH5378738</td><td>08-01-2014 12:60am</td><td>Reshedule</td><td>Team C</td>
            <tr align="center"><td>8-345768457</td><td>1KUH5378738</td><td>08-01-2014 1:00pm</td><td>Reshedule</td><td>Team D</td>
            <tr align="center"><td>9-56745632</td><td>1KUH5378738</td><td>08-01-2014 11:00pm</td><td>Reshedule</td><td>Team B</td>
            <tr align="center"><td>10-56463524</td><td>1KUH778788</td><td>08-01-2014 11:00pm</td><td>Reshedule</td><td>Team C</td>


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