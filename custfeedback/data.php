<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class data extends gui {

    protected function content() {

        return '<html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><title>Welcome to TM Swift</title><meta name="description" content=""><meta name="viewport" content="width=device-width"><link rel="stylesheet" href="core/normalize.min.css"><link rel="stylesheet" href="core/main.gui.css"><style type="text/css" media="screen">#datagridFrame, #realtimestat, #pdq {border:solid 1px;overflow:auto;}#realtimestat {padding:5px;background-color:#f0f0f0;}#realtimestat td{width:200px;text-align:right;font-size:small;}#pdq {padding:5px;}#pdqFrame {width:100%;}#pdqFrame td.rf {border-left:solid 1px;width:20%;text-align:right;vertical-align:top;}#pdqFrame td.lf {width:80%;}#pdqctn td{width:263px;text-align:right;font-size:small;}legend {font-weight:bold;}#footer {padding-top:1em;}.showHideBtn {border:solid 1px #a0a0a0;background-color:#c0c0c0;font-size:10pt;padding:3px;}</style><style type="text/css" media="print">.noPrint {display:none;}</style><link rel="stylesheet" type="text/css" href="./plugin/DataTables-1.9.4/media/css/jquery.dataTables.css" media="screen" /><link rel="stylesheet" type="text/css" href="./plugin/jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css" media="screen" /><script src="core/jquery-1.8.3.min.js"></script><script src="core/modernizr-2.6.2-respond-1.1.0.min.js"></script><script type="text/javascript" src="./plugin/DataTables-1.9.4/media/js/jquery.dataTables.min.js"></script><script type="text/javascript" src="./plugin/jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js"></script></head><body><!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]--><!-- div class="header-container"><header class="wrapper clearfix"><h1 class="title">SWIFT 3</h1></header></div --><div class="main-container"><div class="main wrapper clearfix"><article><header><h1>Order Dashboard</h1></header><section><div id="pdq"><form method="post" action="?clsid=OrderDashboard" type="application/x-www-form-urlencoded"><table id="pdqFrame"><tr><td class="lf"><table id="pdqctn"><tr><td>PTT: <select id="ptt" name="ptt" onchange="javascript: getOPTList(this,"zone","build")"><option value="">All</option><option value="492">PTT Johor Selatan</option><option value="548">PTT Johor Utara</option><option value="676">PTT KD</option><option value="1633">PTT Kedah</option><option value="1695">PTT Kelantan</option><option value="343">PTT Kuala Lumpur</option><option value="730">PTT MELAKA</option><option value="506">PTT MSC</option><option value="716">PTT N. SEMBILAN</option><option value="1788">PTT Negeri Sembilan</option><option value="1841">PTT Pahang</option><option value="454">PTT Perak</option><option value="1685">PTT Perlis</option><option value="526">PTT Petaling Jaya</option><option value="422">PTT Pulau Pinang</option><option value="670">PTT SH</option><option value="2116">PTT Sabah</option><option value="2295">PTT Sarawak</option><option value="2568">PTT Selangor Barat</option><option value="482">PTT Selangor Barat </option><option value="401">PTT Selangor Timur</option><option value="2633">PTT Terengganu</option><option value="2604">Selangor Timur</option></select></td><td>Zone: <select id="zone" name="zone" onchange="javascript: getOPTList(this,"build","")"><option value="">All</option></select></td><td>Building: <select id="build" name="build"><option value="">All</option></select></td></tr><tr><td>Order Status: <select id="BINF_ORDER_STATUS" name="BINF_ORDER_STATUS"><option value="" selected="selected">All</option><option value="Pending Processing" >Pending Processing</option><option value="Processing" >Processing</option><option value="Cancelled" >Cancelled</option></select></td><td>TEAM_ID: <input type="text" id="teamId" name="BSI_TEAM_ID" value="" /></td><td>Prod Type: <select id="BINF_SOURCE_SVC" name="BINF_SOURCE_SVC" ><option value="" >All</option><option value="2" selected="selected">BAU</option><option value="3" >Unifi</option></select></td></tr><tr><td>Order Type: <select id="BINF_ORDER_TYPE" name="BINF_ORDER_TYPE"><option value="" selected="selected">All</option><option value="New Install" >New Install</option><option value="Relocate" >Relocate</option><option value="Terminate" >Terminate</option><option value="Modify Bandwidth" >Modify Bandwidth</option><option value="Modify Bandwidth Downtime" >Modify Bandwidth Downtime</option><option value="Relocation" >Relocation</option><option value="Modify" >Modify</option><option value="Modify Number" >Modify Number</option><option value="Modify Internal Premise" >Modify Internal Premise</option><option value="Sent to Survey" >Sent to Survey</option><option value="Remove" >Remove</option></select></td><td>Activity Status: <select id="BAC_ACTIVITY_STATUS" name="BAC_ACTIVITY_STATUS"><option value="" selected="selected">All</option><option value="Not Started" >Not Started</option><option value="Scheduled" >Scheduled</option><option value="Assigned" >Assigned</option><option value="Request Cancelled" >Request Cancelled</option><option value="Cancelled" >Cancelled</option><option value="Proposed Return" >Proposed Return</option><option value="Returned" >Returned</option></select></td><td>ORDER ID: <input type="text" id="BINF_ORDER_NUMBER" name="BINF_ORDER_NUMBER" value="" /></td></tr><tr><td>Installer Team: <select id="BSI_TEAM_ID" name="BSI_TEAM_ID" ><option value="">All</option><option value="Q1000018" >Q1000018</option><option value="B14331" >B14331</option><option value="Q001456" >Q001456</option><option value="Q1000067" >Q1000067</option><option value="VSTR1230023" >VSTR1230023</option><option value="VSTR1210067" >VSTR1210067</option></select></td><td>Service No: <input type="text" id="FSI_SVC_NUMBER" name="FSI_SVC_NUMBER" value="" /></td><td>Appt Date: <input type="text" id="APPOINTMENT_DATETIME" name="APPOINTMENT_DATETIME" class="datepicker" value=""  /></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table></td><td class="rf" rowspan="2">Colour Code</td></tr><tr><td style="padding:3px;text-align:right;"><input name="pdqsubmit" type="submit" value="Search" /></td></tr></table></form></div>&nbsp;<fieldset id="realtimestat"><legend>Status Updates</legend><table><tr><td>Survey: 10</td><td>Un-jumpering: 57</td><td>Config Server: 17</td><td>Proposed Return: 32</td><td>Payment-approval: 48</td></tr><tr><td>Pending Survey Complete: 93</td><td>Returned Technical: 28</td><td>Jumpering: 14</td><td>Speed Config: 73</td><td>Verification: 21</td></tr><tr><td>Post-Complete: 21</td><td>Processing (scheduled): 22</td><td>Wifi AP Installation: 66</td><td>Design & Assign: 22</td><td>Delayed: 29</td></tr><tr><td>Subscriber Activity: 24</td><td>Returned: 18</td><td>Miss Appt: 25</td><td>Processing (un-scheduled): 11</td><td>Total Active Order: 13</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table></fieldset>&nbsp;<div id="datagridFrame"><table id="datagrid"><thead><tr><th>No.</th><th>OrderNum</th><th>ServiceNum</th><th>LoginId</th><th>OrderType</th><th>Combo</th><th>HardTone</th><th>Segment</th><th>Appt Date Time</th><th>Appt Session</th><th>Jumpering</th><th>SpeedConfig</th><th>SoftTone</th><th>Order Status</th><th>Appt Team</th><th>Aging(O)</th><th>Rebook count</th><th>ApptSource</th><th>Op Err Msg</th></tr></thead><tbody><tr><td>1.</td><td><a  href="javascript:void(0)" onClick="window.open("?clsid=OrderDetailsBAU&orderNumber=NIZAM10&binfId=2-NIZAM10","popup","width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0")">NIZAM10</a></td><td></td><td>B14331</td><td>New Install</td><td>Y</td><td>N</td><td></td><td>19-JUN-2018 09:06:00</td><td>AM</td><td>N</td><td>N</td><td>N</td><td>Processing</td><td></td><td>1231:38 hour(s)</td><td>1</td><td>BAU</td><td></td></tr><tr><td>2.</td><td><a  href="javascript:void(0)" onClick="window.open("?clsid=OrderDetailsBAU&orderNumber=NIZAM10&binfId=2-NIZAM10","popup","width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0")">NIZAM10</a></td><td></td><td>B14331</td><td>New Install</td><td>Y</td><td>N</td><td></td><td>19-JUN-2018 09:06:00</td><td>AM</td><td>N</td><td>N</td><td>N</td><td>Processing</td><td></td><td>1231:38 hour(s)</td><td>1</td><td>BAU</td><td></td></tr><tr><td>3.</td><td><a  href="javascript:void(0)" onClick="window.open("?clsid=OrderDetailsBAU&orderNumber=1-2234961855&binfId=2-1-2234961855","popup","width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0")">1-2234961855</a></td><td></td><td>B14331</td><td>New Install</td><td></td><td>N</td><td>SME</td><td>03-JAN-2014 08:01:18</td><td>PM</td><td>N</td><td>N</td><td>N</td><td>Processing</td><td>1KLZ300105</td><td>259:28 hour(s)</td><td>3</td><td>BAU</td><td></td></tr><tr><td>4.</td><td><a  href="javascript:void(0)" onClick="window.open("?clsid=OrderDetailsBAU&orderNumber=1-2234961855&binfId=2-1-2234961855","popup","width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0")">1-2234961855</a></td><td></td><td>B14331</td><td>New Install</td><td></td><td>N</td><td>SME</td><td>03-JAN-2014 08:01:18</td><td>PM</td><td>Y</td><td>N</td><td>N</td><td>Processing</td><td>1KLZ300106</td><td>259:28 hour(s)</td><td>3</td><td>BAU</td><td></td></tr></tbody></table>&nbsp;&nbsp;&nbsp;<a class="showHideBtn" onclick="javascript:showColumnCtrlPage(18)">Show/Hide Column</a><div id="dialog-message" title="Show/Hide Column"><div><input type="checkbox" id="c1"/ onclick="javascript:fnShowHide(1)"> OrderNum</div><div><input type="checkbox" id="c2"/ onclick="javascript:fnShowHide(2)"> ServiceNum</div><div><input type="checkbox" id="c3"/ onclick="javascript:fnShowHide(3)"> LoginId</div><div><input type="checkbox" id="c4"/ onclick="javascript:fnShowHide(4)"> OrderType</div><div><input type="checkbox" id="c5"/ onclick="javascript:fnShowHide(5)"> Combo</div><div><input type="checkbox" id="c6"/ onclick="javascript:fnShowHide(6)"> HardTone</div><div><input type="checkbox" id="c7"/ onclick="javascript:fnShowHide(7)"> Segment</div><div><input type="checkbox" id="c8"/ onclick="javascript:fnShowHide(8)"> Appt Date Time</div><div><input type="checkbox" id="c9"/ onclick="javascript:fnShowHide(9)"> Appt Session</div><div><input type="checkbox" id="c10"/ onclick="javascript:fnShowHide(10)"> Jumpering</div><div><input type="checkbox" id="c11"/ onclick="javascript:fnShowHide(11)"> SpeedConfig</div><div><input type="checkbox" id="c12"/ onclick="javascript:fnShowHide(12)"> SoftTone</div><div><input type="checkbox" id="c13"/ onclick="javascript:fnShowHide(13)"> Order Status</div><div><input type="checkbox" id="c14"/ onclick="javascript:fnShowHide(14)"> Appt Team</div><div><input type="checkbox" id="c15"/ onclick="javascript:fnShowHide(15)"> Aging(O)</div><div><input type="checkbox" id="c16"/ onclick="javascript:fnShowHide(16)"> Rebook count</div><div><input type="checkbox" id="c17"/ onclick="javascript:fnShowHide(17)"> ApptSource</div><div><input type="checkbox" id="c18"/ onclick="javascript:fnShowHide(18)"> Op Err Msg</div></div></div><div id="footer"><div style="text-align:right"><input type="button" value="Export" /></div></div></section></article></div></div><!-- div class="footer-container"><footer class="wrapper"><h3></h3></footer></div --><script src="core/main.js"></script><script type="text/javascript">/*<![CDATA[*/$(document).ready(function() {$("#datagrid").dataTable({"sScrollY": "300px","bPaginate": true,"bScrollCollapse": true,"oLanguage": {"sSearch": "Filter Text:"}});$("#dialog-message").dialog({modal: true,autoOpen: false});});function fnShowHide( iCol ) {'
        . ''
                . 'var oTable = $("#datagrid").dataTable();var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;oTable.fnSetColumnVis( iCol, bVis ? false : true );}function showColumnCtrlPage(int_totalCol) {try {var oTable = $("#datagrid").dataTable();for(var i=1;i<=int_totalCol;i++) {document.getElementById("c"+i).checked = (oTable.fnSettings().aoColumns[i].bVisible)? true : false;}$("#dialog-message").dialog({modal: true,autoOpen: true,buttons: {Ok: function() {$(this).dialog("close");}}});}catch(e) {alert(e.message);}}function getOPTList(obj,str_optBoxNm,str_nextBoxToClear) {try {var obj_selectBox;var optGroup;var ary_optBox = [str_optBoxNm,str_nextBoxToClear];for(x in ary_optBox) {if(ary_optBox[x] != "") {obj_selectBox = document.getElementById(ary_optBox[x]);for(i=obj_selectBox.length-1;i>=0;i--) {obj_selectBox.removeChild(obj_selectBox[i]);}optGroup = document.createElement("option");optGroup.innerHTML = "All";optGroup.value = "";obj_selectBox.appendChild(optGroup);}}if(obj.value != "") {obj_selectBox = document.getElementById(str_optBoxNm);actBtnEvt.ajaxSend("OrderDashboard","optbox="+ str_optBoxNm +"&val="+ obj.value ,function(){if(actBtnEvt.xmlHttpComObj.readyState == 4) {var i;var ary_output = actBtnEvt.xmlHttpComObj.responseText.split("|");if(ary_output[0] == "ok") {var ary_data = ary_output[1].split("~");for(i=0;i<ary_data.length;i++) {ary_optList = ary_data[i].split("#");optGroup = document.createElement("option");optGroup.innerHTML = ary_optList[1];optGroup.value = ary_optList[0];obj_selectBox.appendChild(optGroup);}}else {alert(actBtnEvt.xmlHttpComObj.responseText);}}});}}catch(e) {alert(e.message);}}var dateToday = new Date();$(function() {$(".datepicker").datepicker({monthNamesShort: [ "JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC" ],dateFormat: "dd-M-yy",firstDay: 1,changeMonth: true,changeYear: true,defaultDate: dateToday});});/*]]>*/</script><!-- script>var _gaq=[["_setAccount","UA-XXXXX-X"],["_trackPageview"]];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";s.parentNode.insertBefore(g,s)}(document,"script"));</script --></body></html>"';
    }

}