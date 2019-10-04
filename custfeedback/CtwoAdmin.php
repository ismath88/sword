<?php

/*
 * custfeedback-Admin module-ctwo Admin
 *
 * @author ismath Khan 
 * 
 * 
 */

class CtwoAdmin extends gui {

    public function __toString() {
        if (util::fetch('delmenuid') != '') {
            return $this->deltblcode(util::fetch('delmenuid'));
        } else {
            return parent::__toString();
        }
    }

    protected function content() {

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('colorbox/jquery.colorbox.js');
        $this->importPlugin('colorbox/colorbox.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');
        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');


        $user = $_SESSION['valid_user'];
        //$user = '1232';


        if (isset($_POST['upd_name'])) {
            $upd_name = $_POST['upd_name'];
            $upd_code = $_POST['upd_code'];
            $upd_desc = $_POST['upd_description'];
            $int_id = $_POST['mid'];

            try {
                $ary_data = array(
                    "NAME" => $upd_name,
                    "CODE" => $upd_code,
                    "DESCRIPTION" => $upd_desc
                );

                //events inserted to SWD_AUDIT_TRIAL table 
                $adt_trldata = array(
                    "TRANSACTION_DATE" => date("d/M/Y"),
                    "USER_ID" => $user,
                    "ACTIVITY" => "Update",
                    "DESCRIPTION" => "Update from SWD_SYSTEM_CODE"
                );
                $MDM_DEVICE_CONFIG_array = array(
                    "LAST_UPDATE" => date("d/m/Y H:i:s")
                );

                $var_results = db::query('update', array('tableName' => 'SWD_SYSTEM_CODE', 'fieldWithValue' => $ary_data, 'condition' => "ID = '" . $int_id . "'"), $str_errMsg, false);
                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $adt_trldata), $str_errMsg1, false);
                $MDM_DEVICE_CONFIG = db::query('update', array('tableName' => 'MDM_DEVICE_CONFIG_LASTUPDATE', 'fieldWithValue' => $MDM_DEVICE_CONFIG_array, 'condition' => "MODULE_CODE='CF' and MODULE_NAME='CUSTOMER_FEEDBACK_LOV'"), $str_errMsg, false);

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
								<h2>Edit/Update Tablecode</h2>
								<table id=\"tblfrm\" cellspacing='10'>
									<tr>
										<td>Name</td>
										<td><input type=\"text\" id=\"name\" name=\"upd_name\" value=\"\" required=\"required\" maxlength=\"20\"/></td>
									</tr>
									<tr>
										<td>Code</td>
										<td><input type=\"text\" id=\"code\" name=\"upd_code\" maxlength=\"20\"></textarea></td>
									</tr>
                                                                        <tr>
										<td>Description</td>
										<td><textarea  id=\"description\" name=\"upd_description\" value=\"\" maxlength=\"20\"></textarea></td>
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
        return $inline_content . $this->viewtblcode() . $add_tblcode;
    }

    private function viewtblcode() {


        $show_rslt = "";
        try {

            $var_results = db::callSQL('SQL_cf', 'VIEWTBLCODE');
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = '<div style="background:#EEA244;text-align:center;font-weight:bold;">CTWO Configuration</div><br><table id ="datagrid" class="view-tblcode" cellpadding="10"><thead><tr style="border:1px solid #000000;background:#EEA244;""><th>Name</th><th>Code</th><th>Description</th><th>Edit</th><th>Delete</th></tr></thead>';
                for ($i = 0; $i < count($var_results); $i++) {
                    $id = $var_results[$i]['ID'];
                    $show_rslt .= "<tr><td>" . $var_results[$i]['NAME'] . "</td><td>" . $var_results[$i]['CODE'] . "</td><td>" . $var_results[$i]['DESCRIPTION'] . "</td><td><button class=\"btn5edit inline\" href=\"#inline_content\" onclick=\"javascript:setFormData(0," . $id . ",false,this)\">Edit</button></a></td>"
                            . "<td><button class=\"btn5del\" onclick=\"javascript:delMenu(" . $id . ")\">Delete</button></td></tr>";
                }
                $show_rslt .= "</table><br><div style=\"text-align:right;color:#0000ff\"><a onclick=\"javascript:window.open('?clsid=CtwoAdminadd','_blank','width=900,height=400,left=200');\">Add Table Code</a></div>";

                return $show_rslt;
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    private function deltblcode($codeid) {
        try {

            $adt_trldata = array(
                "TRANSACTION_DATE" => date("d/M/Y"),
                "USER_ID" => "1234",
                "ACTIVITY" => "Update",
                "DESCRIPTION" => "Update from SWD_SYSTEM_CODE"
            );

            $MDM_DEVICE_CONFIG_array = array(
                    "LAST_UPDATE"=> date("d/m/Y H:i:s") 
                 );

            
            $var_rslt = db::query('delete', array('tableName' => 'SWD_SYSTEM_CODE', 'condition' => "ID =" . $codeid), $str_errMsg, false);
            $MDM_DEVICE_CONFIG = db::query('update', array('tableName' => 'MDM_DEVICE_CONFIG_LASTUPDATE', 'fieldWithValue' => $MDM_DEVICE_CONFIG_array, 'condition' => "MODULE_CODE='CF' and MODULE_NAME='CUSTOMER_FEEDBACK_LOV'"), $str_errMsg, false);
            
            if ($str_errMsg) {
                throw new errors($str_errMsg);
            } else {
                return "ok";
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {
        return "
				$(function() {
                                
                                
                                     
 $(' input[type=text],#name,#code,#description ').keypress(function(){
 
        var  maxl= $(this).attr('maxlength'); 
 
if(this.value.length >= maxl){
alert('Maximum 20 Characters only');
                       
}

});



					$(\".inline\").colorbox({inline:true, width:\"30%\"});
					
					$(\".btn5s\").button({icons: {primary: \"ui-icon-folder-open\"}});
					$(\".btn5\").button({icons: {primary: \"ui-icon-circle-plus\"}});
					$(\".btn5save\").button({icons: {primary: \"ui-icon-circle-check\"}});
					$(\".btn5edit\").button({icons: {primary: \"ui-icon-pencil\"}, text: false});
					$(\".btn5del\").button({icons: {primary: \"ui-icon-trash\"}, text: false});
					$(\".btn5info\").button({icons: {primary: \"ui-icon-help\"}, text: false});
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
						$('#nxno,#mid,#mpid,#name,#code','#description').val('');
						$('#nxno').val(int_nextNo);
                                              
						if(int_mid) {
							$('#mid').val(int_mid);
						}
						if(int_mpid) {
							$('#mpid').val(int_mpid);
						}
						if(obj) {
							$('#name').val(obj.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML);
							$('#code').val(obj.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML);
							$('#description').val(obj.parentNode.parentNode.getElementsByTagName('td')[2].innerHTML);
						}
						if(str_secNm) {
							var objx = document.getElementById(str_secNm);
							$('#name').val(objx.getElementsByTagName('b')[0].innerHTML);
							$('#code').val(objx.getElementsByTagName('i')[0].innerHTML);
							$('#description').val(objx.getElementsByTagName('i')[0].innerHTML);
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
                . ".view-tblcode{width:inherit;border:2px solid #666666;margin:auto;margin-top:20px;}";
    }

}
