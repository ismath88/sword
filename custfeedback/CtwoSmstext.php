<?php

/*
 * custfeedback-Admin module-ctwo Sms Text
 *
 * @author ismath Khan 
 * 
 * 
 */

class CtwoSmstext extends gui {

    public function __toString() {
        if (util::fetch('delmenuid') != '') {
            return $this->deltblcode(util::fetch('delmenuid'));
        } else {
            return parent::__toString();
        }
    }

    protected function content() {

        //$user = $_SESSION['valid_user'];
        $user = "1112";

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('colorbox/jquery.colorbox.js');
        $this->importPlugin('colorbox/colorbox.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');
        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');

        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        $int_id = filter_input(INPUT_POST, 'mid', FILTER_SANITIZE_STRING);

        if (isset($message)) {


            try {
                $ary_data = array(
                    "MESSAGE" => $message
                );

                //events inserted to SWD_AUDIT_TRIAL table 
                $adt_trldata = array(
                    "TRANSACTION_DATE" => date("d/M/Y"),
                    "USER_ID" => $user,
                    "ACTIVITY" => "Update",
                    "DESCRIPTION" => "Update from SWD_SMS_TEXT"
                );

                $sms_code = 'T' . $int_id;

                $var_results = db::query('update', array('tableName' => 'SWD_SMS_TEXT', 'fieldWithValue' => $ary_data, 'condition' => "SMS_CODE = '" . $sms_code . "'"), $str_errMsg, false);


                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $adt_trldata), $str_errMsg1, false);
                if ($str_errMsg) {
                    throw new errors($str_errMsg);
                }

                $this->runScript('alert', array("Update Successfully"));
            } catch (errors $e) {
                $this->runScript('alert', array(htmlspecialchars($e->message())));
            }
        }

//for colorbox pop onclick edit

        $inline_content = "<div style = 'display:none'>
<div id = 'inline_content' style = 'padding:10px; background:#fff;'>
<form method = \"post\" action=\"?clsid=" . __CLASS__ . "\"   type=\"application/x-www-form-urlencoded\">
								<h2>Edit/Update SMS Text</h2>
								<table id=\"tblfrm\" cellspacing='10'>
									
                                                                        <tr>
										<td>Message</td>
										<td><textarea  id=\"message\" name=\"message\" value=\"\" maxlength=\"200\" rows=7\"></textarea></td>
									</tr>
                                                                        <tr>
										<td colspan=\"2\" style=\"text-align:right\"><button type=\"submit\" class=\"btn5save\">Save</button></td>
									</tr>
                                                                       
								</table>
								<input type=\"hidden\" id=\"mid\" name=\"mid\" value=\"\" />
								<input type=\"hidden\" id=\"mpid\" name=\"mpid\" value=\"\" />
								<input type=\"hidden\" id=\"nxno\" name=\"nxno\" value=\"\" />
								<input type=\"hidden\" id=\"secno\" name=\"secno\" value=\"" . ((isset($_REQUEST['secno']) && $_REQUEST['secno'] != '') ? $_REQUEST['secno'] : 0) . "\" />
							</form>
						</div>
					</div>";
        $add_tblcode = "";
        return $inline_content . $this->viewsmstext() . $add_tblcode;
    }

    private function viewsmstext() {


        $show_rslt = "";
        try {

            $var_results = db::callSQL('SQL_cf', 'SMSTEXT');
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = '<div style="background:#EEA244;text-align:center;font-weight:bold;">CTWO Sms Text</div><br><table id ="datagrid" class="view-smstext" cellpadding="10"><thead><tr style="border:1px solid #000000;background:#EEA244;""><th>Date Create</th><th>User ID</th><th>Message</th><th>Edit</th><!--th>Delete</th--></tr></thead>';
                for ($i = 0; $i < count($var_results); $i++) {

                    //$id = $var_results[$i]['ID'];
                    $sms_code = $var_results[$i]['SMS_CODE'];
                    $id = filter_var($sms_code, FILTER_SANITIZE_NUMBER_INT);
                    $show_rslt .= "<tr><td>" . $var_results[$i]['DATE_CREATE'] . "</td><td>" . $var_results[$i]['USERID'] . "</td><td>" . $var_results[$i]['MESSAGE'] . "</td><td><button class=\"btn5edit inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(0," . $id . ",false,this)\">Edit</button></a></td>"
                            . "<!--td><button class=\"btn5del\" onclick=\"javascript:delMenu(" . $id . ")\">Delete</button></td--></tr>";
                }
                $show_rslt .= "</table><br><div style=\"text-align:right;color:#0000ff\"><a onclick=\"javascript:window.open('?clsid=CtwoAddSmstext','_blank','width=900,height=400,left=200');\">Add SMS Text</a></div>";

                return $show_rslt;
                //->updatetblcode($id);
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {
        return "
				$(function() {
                                
                                
                                     
 $(' input[type=text],#message ').keypress(function(){
 
        var  maxl= $(this).attr('maxlength'); 
 
if(this.value.length >= maxl){
alert('Maximum 200 Characters only');
                       
}

});



					$(\".inline\").colorbox({inline:true, width:\"30%\"});
					
					$(\".btn5s\").button({icons: {primary: \"ui-icon-folder-open\"}});
					$(\".btn5\").button({icons: {primary: \"ui-icon-circle-plus\"}});
					$(\".btn5save\").button({icons: {primary: \"ui-icon-circle-check\"}});
					$(\".btn5edit\").button({icons: {primary: \"ui-icon-pencil\"}, text: false});
					$(\".btn5del\").button({icons: {primary: \"ui-icon-trash\"}, text: false});
					
					$(\".xbtn\").colorbox({width:\"70%\", height:\"80%\", iframe:true});
                                        

$('#datagrid').dataTable({
            
             'bPaginate': true,                         
		'oLanguage': {
			'sSearch': 'Search:'        
		} 
                });  
                                        
				});
     
                               
				function delMenu(int_menuID) {
					try {
						if(confirm('Are you sure you want to delete the selected record and it associated data?')) {
							actBtnEvt.ajaxSend('" . __CLASS__ . "','delmenuid='+ int_menuID,function(){
								if(actBtnEvt.xmlHttpComObj.readyState == 4) {
									if(actBtnEvt.xmlHttpComObj.responseText == 'ok') {
										alert('Deleted!');
										location.replace(window.location.href +'&secno='+ $('#secno').val());
									}
									else {
										alert(actBtnEvt.xmlHttpComObj.responseText);
									}
								}
							});
						}
					}
					catch(e) {
						alert(e.message);
					}
				}
				function formChecker() {
					try {
						if(actBtnEvt.trim($('#name').val()) != '') {
							return true;
						}
						else {
							throw('Oops! Something went wrong. Please check your input.');
						}
					}
					catch(e) {
						if(e.message) {
							alert(e.message);
						}
						else {
							alert(e);
						}
						return false;
					}
				}
				function setFormData(int_nextNo,int_mid,int_mpid,obj,str_secNm) {
					try {
						$('#nxno,#mid,#mpid,#name,#code','#message').val('');
						$('#nxno').val(int_nextNo);
                                              
						if(int_mid) {
							$('#mid').val(int_mid);
						}
						if(int_mpid) {
							$('#mpid').val(int_mpid);
						}
						if(obj) {
							
							$('#message').val(obj.parentNode.parentNode.getElementsByTagName('td')[2].innerHTML);
						}
						if(str_secNm) {
							var objx = document.getElementById(str_secNm);
							
							$('#message').val(objx.getElementsByTagName('i')[0].innerHTML);
						}
					}
					catch(e) {
						alert(e.message);
					}
				}
				
			";
    }

    protected function css() {
        return ".tbl-code{ background:#a0a0a0;margin-bottom:20px;border-top:1px solid #666666;border-right:1px solid #666666;border-bottom:4px solid #666666;border-left:4px solid #666666;} td{margin-bottom:10px;height:50px;border-left:none;text-align:center;}"
                . ".view-smstext{width:inherit;border:2px solid #666666;margin:auto;margin-top:20px;}";
    }

}
