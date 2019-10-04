<?php

/*
 * custfeedback-Admin module-ctwo Admin-add Table code
 *
 * @author ismath Khan 
 * 
 * 
 */

class CtwoAdminadd extends gui {

    protected function content() {

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');

        $user_id = $_SESSION['valid_user'];
        //$user_id = "sword";


        $tblcode_form = "<div style=\"background:#EEA244;text-align:center;font-weight:bold;\">Admin Add Table Code</div><br>
            <form action =\"\" method=\"post\"><table  cellpadding='10' style=\"margin:auto;\">
            <tr><td>Name:</td><td><input type=\"text\" name=\"name\" required=\"required\" maxlength=\"20\" /></td></tr>
                <tr><td>Code:</td><td><input type=\"text\" name=\"code\" required=\"required\" maxlength=\"20\" /></td></tr>
                <tr><td>Description:</td><td><textarea name=\"desc\" id=\"description\" maxlength=\"20\" cols=\"23\"></textarea></td></tr><tr><td><input type=\"submit\" name=\"add\" value=\"Add\" /></td><td><input type=\"reset\" value=\"Cancel\"/></td></tr>
                
</table></form>
                
<table></table>";


        return $tblcode_form . $this->inserttblcode($user_id);
    }

    private function inserttblcode($usrid) {
        $post_btn = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);

        if (isset($post_btn)) {

            try {
                $ary_data = array(
                    "NAME" => $name,
                    "CODE" => $code,
                    "DESCRIPTION" => $description
                );

                $adt_trldata = array(
                    "TRANSACTION_DATE" => date("d/M/Y"),
                    "USER_ID" => $usrid,
                    "ACTIVITY" => "Insert",
                    "DESCRIPTION" => "Insert from SWD_SYSTEM_CODE"
                );

                 $MDM_DEVICE_CONFIG_array = array(
                    "LAST_UPDATE"=> date("d/m/Y H:i:s") 
                 );


                $var_rslt = db::query('insert', array('tableName' => 'SWD_SYSTEM_CODE', 'fieldWithValue' => $ary_data), $str_errMsg, false);
                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $adt_trldata), $str_errMsg, false);
                $MDM_DEVICE_CONFIG = db::query('update', array('tableName' => 'MDM_DEVICE_CONFIG_LASTUPDATE', 'fieldWithValue' => $MDM_DEVICE_CONFIG_array, 'condition' => "MODULE_CODE='CF' and MODULE_NAME='CUSTOMER_FEEDBACK_LOV'"), $str_errMsg, false);
                if ($str_errMsg) {
                    throw new errors($str_errMsg);
                }

                echo ("<SCRIPT LANGUAGE='JavaScript'>
                    alert('Successfully Saved');
                                  window.opener.location.reload();
                                  window.location.reload();
                                                                    
        </SCRIPT>");
            } catch (errors $e) {
                $this->runScript('alert', array(htmlspecialchars($e->message())));
            }
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
	
 });                                       

";
    }

    protected function css() {
        return "";
    }

}
