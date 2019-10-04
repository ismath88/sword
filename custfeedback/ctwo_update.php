<?php
/*

 * POC for CTWO Update
 * 
 * @author Ismath Khan
 *  */
?>
<html>
    <head>
        <style type="text/css">
            #div-brd{
                border:2px solid #000000;
                width:500px;
                margin: auto;
            }
            a{
                text-decoration: none;
            }
        </style>
        
    </head>
    <body>
        
        <div id='div-brd'>
        <table  cellpadding="5" cellspacing="3" align='center' >
            
            <tr><fieldset style="width:300px;margin:auto;"><legend>Supervisor's Finding:</legend><textarea cols='50'>The Customer was not satisfy due to time Consuming</textarea></fieldset></tr>
        <tr><td>Owner Workgroup:</td><td><select><option></option>Sales/Channel<option>TM Point</option><option>RNO</option><option>Marketing/product</option><option>Call Center</option></select></td></tr>   
        <tr><td>Root caused category</td><td><select><option></option>Sales/Channel<option>TM Point</option><option>RNO</option><option>Marketing/product</option><option>Call Center</option></select></td></tr>   
        <tr><td>Resolution Code</td><td><select><option></option>Resolve By RNO<option>Return To Owner</option><option>RNO</option><option>Marketing/product</option><option>Call Center</option></select></td></tr>   
        <tr><td><input type='button' value='Update'/></td><td><a href='ctwo.php'><input type='button' value='Cancel'/></a></td></tr>
    </table>
            </div>
        
        

</html>