
<?php

/*
 * custfeedback-Admin module
 *
 * @author ismath Khan      
 */

class Ctwo extends gui {

    protected function content() {

        $this->importPlugin('sword/DataTables-1.9.4/media/js/jquery-1.4.4.min.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');
        $this->importPlugin('sword/DataTables-1.9.4/media/js/jquery.dataTables.columnFilter.js');


        $valid_user = "sword";
        //$valid_user = $_SESSION['valid_user'];

        return $this->viewctwo($valid_user);
    }

    private function viewctwo($login_user) {

//view of ctwo

        $show_rslt = "";

        try {
            $var_rslt_supvsr = db::callSQL('SQL_cf', 'CHECK_SUPVSR_NO', array("'$login_user'"));
            if (!is_array($var_rslt_supvsr)) {
                throw new errors($var_rslt_supvsr);
            } else {

                $supvsr_no = "";
                if (isset($var_rslt_supvsr[0]['SUPERVISOR_STAFF_NO'])) {
                    $supvsr_no = $var_rslt_supvsr[0]['SUPERVISOR_STAFF_NO'];
                }
            }
            if ($login_user == $supvsr_no) {
                $var_results = db::callSQL('SQL_cf', 'CTWOLIST_SUPVSR', array("'$login_user'"));
            } else if ($login_user == "sword") {
                $var_results = db::callSQL('SQL_cf', 'CTWOLIST');
            } else if ($supvsr_no == "") {
                $var_results = db::callSQL('SQL_cf', 'CTWOLIST_SUPVSR', array("'$login_user'"));
            } else {
                
            }


            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {


                $show_data = '<div style="background:#EEA244;text-align:center;font-weight:bold;">Complaint Type Work Order(CTWO)</div><br><div><div><div style="float:left">Filter By Month And Year:<!--select id="engines">
                    
    <option value="JAN">JAN</option> <option value="FEB">FEB</option> <option value="MAR">MAR</option> <option value="APR">APR</option> <option value="MAY">MAY</option> <option value="JUN">JUN</option> <option value="JUL">JUL</option>
   
    <option value="AUG">AUG</option> <option value="SEP">SEP</option> <option value="OCT">OCT</option> <option value="NOV">NOV</option> <option value="DEC">DEC</option>
</select--><input type="text" id="datepicker"/></div><div style="float:right">Filter by Status:<select id="statusfilter">
    <option value="">--------Choose-------</option>
    <option value="OPEN">OPEN</option>
    <option value="DELAYED">DELAYED</option>
    <option value="RESOLVED">RESOLVED</option>
</select></div></div><br/><div id="datagridFrame"><table id="datagrid" border="1"><thead>

<tr style="border:1px solid #000000;background:#EEA244;">                          
                              <th>No</th>
                               <th>Time Created</th>
                                <th>TT No/Order No</th>
                                <th>Exch</th>
                                <th>Source</th>
                                <th>Service No</th>
                                <th>Customer Name</th>
                                <th>Aging</th>
                                <th>Priority</th>
                                <th>Status</th>
                            <th>Rating</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspAction&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
                        </thead>';




//fetching datas from db tables->swd_cf_ctwo,swd_sf_rating
                for ($i = 0; $i < count($var_results); $i++) {

//print_r($var_results);


                    $id = $var_results[$i]['CTWO_ID'];
                    $auto_no = $i + 1;
                    $created_date = $var_results[$i]['CREATED_DATE'];
                    $serviceno = $var_results[$i]['SERVICE_NUMBER'];
                    $source = $var_results[$i]['SOURCE_SYSTEM'];
                    $docketNumber = $var_results[$i]['DOCKET_NUMBER'];
                    $docNumber = substr($docketNumber,2);
                    $swift_src_system = $var_results[$i]['SWIFT_SOURCE_SYSTEM'];
                    $cf_rating_id = $var_results[$i]['SWD_CF_RATING_ID'];
                    $priority = $var_results[$i]['PRIORITY'];
                    $rating = $var_results[$i]['RATING'];
                    $building = $var_results[$i]['BUILDING'];
                    $teamname = $var_results[$i]['TEAM_NAME'];
                    $customer_name = $var_results[$i]['CUSTOMER_NAME'];

                    $docId = $var_results[$i]['DOC_ID'];

                    $woID = $var_results[$i]['WORK_ORDER_ID'];

                    $access_type = $var_results[$i]['NETWORK_TYPE'];

                    $hours = $var_results[$i]['AGING'];
                    $ctwo_status = $var_results[$i]['CTWO_STATUS'];

                    if ($source == 1) {
                        $source = "Assurance";
                        $status = "Closed";
                    } elseif ($source == 2) {
                        $source = "FullFilment";
                        $status = "Complete";
                    }

                    $adsl = '';
                    if ($access_type == "LEASED LINE" || $access_type == "XSDL" || $access_type == "ADSL" || $access_type == "SDSL") {
                        $adsl = 'Yes';
                    } else {
                        $adsl = 'No';
                    }

                    if ($swift_src_system == 4 && $adsl == 'Yes') {

                        $link_ttno = "http://swift.tmrnd.com.my:8080/SWIFT4/fw/?clsid=TTDetailDataADSL_L&did=$docId&accessType=$access_type";
                    } elseif ($swift_src_system == 4 && strtoupper($access_type) == "METRO ETHERNET") {

                        $link_ttno = "http://swift.tmrnd.com.my:8080/SWIFT4/fw/?clsid=TTDetailDataMe&did=$docId&accessType=$access_type";
                    } elseif ($swift_src_system == 4 && $adsl == 'No') {

                        $link_ttno = "http://swift.tmrnd.com.my:8080/SWIFT4/fw/?clsid=TTDetailData_Others&did=$docId&accessType=$access_type";
                    } elseif ($swift_src_system == 3) {

                        $link_ttno = "http://swift.tmrnd.com.my:8080/SWIFT4/fw/?clsid=TTDetailUnifi&did=$docId&accessType=$access_type";

                        //$noteStatus = "DisplayNotes&doc_number=$docNumber&ctt_number=$docNumber&source_system=$swift_src_system&activity_id=$activityId";
                    } elseif ($swift_src_system == 2) {
                        $link_ttno = "http://swift.tmrnd.com.my:8080/SWIFT4/fw/?clsid=TTDetail&did=$docId&accessType=$access_type";
                    } else {
                        $link_ttno = '';
                    }



//if aging hours beyond 72 hours update swd_cf_ctwo table cf_status to delayed
                    if ($hours >= 72 && $ctwo_status != 'RESOLVED' && $ctwo_status == 'OPEN' && $ctwo_status != 'DELAYED') {


                        try {
                            $ary_data = array(
                                "CTWO_STATUS" => "DELAYED"
                            );

                            $upd_status = db::query('update', array('tableName' => 'SWD_CF_CTWO', 'fieldWithValue' => $ary_data, 'condition' => "ID = '" . $id . "'"), $str_errMsg, false);

                            if ($str_errMsg) {
                                throw new errors($str_errMsg);
                            }
                        } catch (errors $e) {
                            $this->runScript('alert', array(htmlspecialchars($e->message())));
                        }
                    }



                    $show_data .= "<tr><td>" . $auto_no . "</td><td>" . $created_date . "</td><td><a href=\"\" onclick=\"javascript:window.open('$link_ttno',' ','width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0'); return false;\">" . $docNumber . "</a></td><td>" . $building . "</td>"
                            . "<td>" . $source . "</td><td><a id='serviceno' href=\"\" onclick=\"javascript:window.open('?clsid=CtwoCustDetail&clspar=" . urlencode("svc_no=" . $serviceno . "&source=" . $source . "&ttno=" . $docNumber . "&ctwoid=" . $id) . "',' ','width=1000,height=600,left=200'); return false;\">" . $serviceno . "</a></td><td>" . $customer_name . "</td>"
                            . "<td class='hours'>" . $hours . " hours</td>"
                            . "<td>" . $priority . "</td><td class='status'>" . $ctwo_status . "</td><td><a id='' href='' onclick=\"javascript:window.open('?clsid=CtwoSmsMsg&clspar=" . urlencode("&docketno=" . $docNumber) . "',' ','width=1000,height=600,left=200'); return false;\" >" . $rating . "</a></td>";
                    if ($ctwo_status == 'RESOLVED' && $login_user != 'sword') {
                        $show_data .= "<td><button  class=\"btnupd\" id=\"disable_btn\" autocomplete='off' disabled='disabled' onclick=\"javascript:window.open('?clsid=CtwoUpdate&clspar=" . urlencode("ctwoid=" . $id . "&cfrating_id=" . $cf_rating_id) . "','_blank','width=900,height=400,left=200'); return false;\">update</button>";
                    } else {
                        $show_data .= "<td><button  class=\"btnupd\" autocomplete='off' onclick=\"javascript:window.open('?clsid=CtwoUpdate&clspar=" . urlencode("ctwoid=" . $id . "&cfrating_id=" . $cf_rating_id) . "','_blank','width=900,height=400,left=200'); return false;\">update</button>";
                    }
                    $show_data .= "<button id='history' class=\"btnhist\" onclick=\"javascript:window.open('?clsid=CtwoHistory&clspar=" . urlencode("serviceno=" . $serviceno) . "',' ','width=900,height=400,left=200'); return false;\">History</button></td></tr>";
                }
                $show_data .= "<table></div></div>";
                return $show_data;
                // print_r("dvdvd");
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {
        return "
				$(function() {
                                
                            
                            
                            var hh = $('td.hours').text();
        
$('td.hours').each(function(){
var txt = $(this).html();
var aging_hours = parseInt(txt, 10);
if( aging_hours >= 72){

    $(this).parent().css('background-color','#FAD2D6');
   }
else{
$(this).parent('tr').css('background-color','#F7F5B0');
}
});

$('td.status').each(function(){

    var status = $(this).html();
    
if(status == 'RESOLVED'){

$(this).parent('tr').css('background-color',' #D2FAD9');


}


});
$('#disable_btn').attr('disable','disable');
 $(\".btnupd\").button({icons: {primary: \"ui-icon-upd\"}, text: false});
 $(\".btnhide\").button({icons: {primary: \"ui-icon-upd\"}, text: false});
 
 
 
		            $(\".btnhist\").button({icons: {primary: \"ui-icon-hist\"}, text: false}); 
				
/*datatable for ctwo with filter search*/
                                        /*$('#datagrid').dataTable({'bJQueryUI': true,'sPaginationType': 'full_numbers','sScrollY': '1000px','bScrollCollapse': true,'oLanguage': {'Search': 'Filter Text:'}});*/
    
 var oTable = $('#datagrid').dataTable({
            'aaSorting': [],
             'bPaginate': true,                         
		'oLanguage': {
			'sSearch': 'Search:' ,
                        'sZeroRecords': '',
                        'sEmptyTable': ''
		},
                'aoColumnDefs': [{ 'bSortable': false, 'aTargets': [ 0 ] }],
               	}).columnFilter({sPlaceHolder: 'head:before',aoColumns:[null,null,null,null,null,null,null,null,null,null,null,null,null]});
        

         $('select#engines').change( function() { oTable.fnFilter( $(this).val() ); } );
         $('select#statusfilter').change( function() { oTable.fnFilter( $(this).val() ); } );
         $('#dialog-message').dialog({
						modal: true,
						autoOpen: false
					});

$(window).load(function(){
    $('#datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
          showOn: 'button',
         buttonImage: 'images/sword/icons/calender.png',
                buttonImageOnly: true,
        showButtonPanel: true,
        dateFormat: 'M-yy'
    }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
            var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
            var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
            var val = thisCalendar.datepicker('setDate', new Date(year, month, 1));
            oTable.fnFilter( $('#datepicker').val() );
        });
    });
     });/* end -window load fn*/

                

});/*end of jquery */


";
    }

    protected function css() {
        return " a:visited{color:#0000ff} #datagrid thead #filter{background:none;border:none} td{text-align:center;} #statusfilter{ width:150px;} "
                . "select{width:100px} .ui-icon-upd {background-image: url('images/sword/icons/u4.png')!important; }"
                . ".ui-icon-hist {background-image: url('images/sword/icons/h3.png')!important; } .ui-datepicker-calendar {
    display: none;#datagrid_wrapper
}/*#datagrid_wrapper{width:1600px;border:1px solid #000000}*/";
    }

}
