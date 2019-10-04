<?php

/*
 * custfeedback-Admin module-ctwo Admin-add SMS Text
 *
 * @author ismath Khan 
 * 
 * 
 */

class CtwoAddSmstext extends gui {

    protected function content() {

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');



//$user_id = $_SESSION['valid_user'];
        $user_id = "sword";
        if (util::fetch('change_flagsms') != '') {
            return $this->changeflagsms(util::fetch('change_flagsms'), $user_id);
        }



        $smsadd_form = "<div style=\"background:#EEA244;text-align:center;font-weight:bold;\">Admin Add SMS Text</div><br>
            <form action =\"\" method=\"post\"><table  cellpadding='10' style=\"margin:auto;\">
                <!--tr><td>Flag SMS:</td><td><select name=\"flagsms\" id=\"flagsms\"><option>-Choose-</option><option value=\"0\">0</option><option value=\"1\">1</option></select></td></tr-->
                <tr><td>Description:</td><td><textarea name=\"msg\" id=\"message\" maxlength=\"200\" cols=\"23\" required></textarea></td></tr></table><div id=\"btn-div\"><input type=\"submit\" name=\"add\" value=\"Add\" /><input type=\"button\" value=\"Cancel\" onclick=\"javascript:test()\" /></div>
                
</form>
                
<table></table>";


        return $smsadd_form . $this->insertsmstext($user_id);
    }

    private function insertsmstext($usrid) {
        $post_btn = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);


        //$flagsms = filter_input(INPUT_POST, 'flagsms', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_STRING);

        $var_rslts = db::callSQL("SQL_cf", "LASTSMS_CODE");



        if (isset($post_btn)) {

            try {
                $ary_data = array(
                    "MESSAGE" => $message,
                    "USERID" => $usrid
                );

                $adt_trldata = array(
                    "TRANSACTION_DATE" => date("d/M/Y"),
                    "USER_ID" => $usrid,
                    "ACTIVITY" => "Insert",
                    "DESCRIPTION" => "Insert from SWD_SMS_TEXT"
                );




                $var_rslt = db::query('insert', array('tableName' => 'SWD_SMS_TEXT', 'fieldWithValue' => $ary_data), $str_errMsg, false);
                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $adt_trldata), $str_errMsg, false);
                if ($str_errMsg) {
                    throw new errors($str_errMsg);
                }

                echo ("<SCRIPT LANGUAGE='JavaScript'>
                                 alert('Successfully Saved');
                                 window.opener.location.reload();
                                  window.close();
                                  
                                                                    
        </SCRIPT>");
            } catch (errors $e) {
                $this->runScript('alert', array(htmlspecialchars($e->message())));
            }
        }
    }

//    private function checkflagsms($flagval) {
//
//        try {
//
//            $var_results = db::callSQL('SQL_cf', 'CHECKFLAGSMS', array("'$flagval'"));
//            if (!is_array($var_results)) {
//                throw new errors($var_results);
//            } else {
//
//
//                for ($i = 0; $i < count($var_results); $i++) {
//
//                    $smsval = $var_results[$i]['FLAG_SMS'];
//                    if ($smsval == 1) {
//                        return "ok";
//                    }
//                }
//            }
//        } catch (errors $e) {
//            return $e->message();
//        }
//    }
//    private function changeflagsms($smsvalue,$user) {
//        try {
//            $ary_data = array(
//                "FLAG_SMS" => "0"
//            );
//
//            $adt_trldata = array(
//                "TRANSACTION_DATE" => date("d/M/Y"),
//                "USER_ID" => $user,
//                "ACTIVITY" => "Update",
//                "DESCRIPTION" => "Update from table SWD_SMS_TEXT"
//            );
//
//            $var_results = db::query('update', array('tableName' => 'SWD_SMS_TEXT', 'fieldWithValue' => $ary_data, 'condition' => "FLAG_SMS = '" . $smsvalue . "'"), $str_errMsg, false);
//            $audit_trial = db::query('update', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $adt_trldata), $str_errMsg, false);
//            if ($str_errMsg) {
//                throw new errors($str_errMsg);
//            }
//
//            $this->runScript('alert', array("Update Successfully"));
//        } catch (errors $e) {
//            $this->runScript('alert', array(htmlspecialchars($e->message())));
//        }
//    }

    protected function js() {
        return "
				function test() {
					window.close();
				}
                                
              $(document).ready(function() {
                $(' #message ').keypress(function(){
             
        var  maxl= $(this).attr('maxlength'); 

if(this.value.length >= maxl){
alert('Maximum 200 Characters only');
                       
}

});
     
     });";
    }

    protected function css() {
        return " #btn-div{text-align:center;margin-left:100px;} #btn-div input{margin:10px; }  ";
    }

}
