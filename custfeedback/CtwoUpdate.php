<?php

/*
 * custfeedback-ctwo update
 *
 * @author ismath Khan  
 */

class CtwoUpdate extends gui {

    protected function content() {

        //retrieve  data from url
        $ctwo_id = util::fetch("ctwoid");
        $cf_rating_id = util::fetch("cfrating_id");
        //$user = $_SESSION['valid_user'];
        $user = 'sword';

//Save to  SWD_CF_CTWO  and swd_cf_ctwo_remarks using post function
        if (isset($_POST['save'])) {
            $remark = $_POST['remark'];
            $owg = $_POST['ownerwg'];
            $rootcc = $_POST['rootcc'];
            $rescode = $_POST['rescode'];



            try {
                $ary_data = array(
                    "OWNER_WORKGROUP" => $owg,
                    "ROOT_CAUSE_CAT" => $rootcc,
                    "RESOLUTION_CODE" => $rescode,
                    "CTWO_STATUS" => "RESOLVED",
                    "CLOSED_DATE" => date("d/M/Y h:i:s a")
                );
                $ary_data2 = array(
                    "CTWO_ID" => $ctwo_id,
                    "REMARKS" => $remark,
                    "SWD_CF_RATING_ID" => $cf_rating_id,
                    "INSERT_DATE" => date("d/M/Y h:i:s a")
                );


                $ary_data_audittrial = array(
                    "TRANSACTION_DATE" => date("d/M/Y h:i:s a"),
                    "USER_ID" => "1234",
                    "ACTIVITY" => "Insert,Update",
                    "DESCRIPTION" => "Insert From SWD_CF_CTWO,Update From SWD_CF_CTWO_REMARKS from file CtwoUpdate.php"
                );


                $var_results = db::query('update', array('tableName' => 'SWD_CF_CTWO', 'fieldWithValue' => $ary_data, 'condition' => "ID = '" . $ctwo_id . "'"), $str_errMsg, false);
                $inst_cf_remarks = db::query('insert', array('tableName' => 'SWD_CF_CTWO_REMARKS', 'fieldWithValue' => $ary_data2), $str_errMsg, false);
                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $ary_data_audittrial), $str_errMsg, false);

                if ($str_errMsg) {
                    throw new errors($str_errMsg);
                }

                echo ("<SCRIPT LANGUAGE='JavaScript'>
                 window.alert('Succesfully Saved');
                  window.opener.location.reload();
                                   window.close();
                                  
        </SCRIPT>");
            } catch (errors $e) {
                $this->runScript('alert', array(htmlspecialchars($e->message())));
            }
        }

        //Update to  SWD_CF_CTWO  and swd_cf_ctwo_remarks using post function
        if (isset($_POST['update'])) {
            $remark = $_POST['remark'];
            $owg = $_POST['ownerwg'];
            $rootcc = $_POST['rootcc'];
            $rescode = $_POST['rescode'];

            try {
                $ary_data = array(
                    "OWNER_WORKGROUP" => $owg,
                    "ROOT_CAUSE_CAT" => $rootcc,
                    "RESOLUTION_CODE" => $rescode,
                    "CTWO_STATUS" => "RESOLVED",
                    "CLOSED_DATE" => date("d/M/Y h:i:s a")
                );
                $ary_data_remark = array(
                    "CTWO_ID" => $ctwo_id,
                    "REMARKS" => $remark,
                    "SWD_CF_RATING_ID" => $cf_rating_id,
                    "INSERT_DATE" => date("d/M/Y h:i:s a")
                );
//                $ctwo_history_remarks = array(
//                    "REMARKS" => "Update By-" . $user,
//                );

                $ary_data_audittrial = array(
                    "TRANSACTION_DATE" => date("d/M/Y h:i:s a"),
                    "USER_ID" => "1234",
                    "ACTIVITY" => "Update",
                    "DESCRIPTION" => "Update From SWD_CF_CTWO,SWD_CF_CTWO_REMARKS,SWD_CF_RATING from file CtwoUpdate.php"
                );

                $upd_cf_ctwo = db::query('update', array('tableName' => 'SWD_CF_CTWO', 'fieldWithValue' => $ary_data, 'condition' => "ID = '" . $ctwo_id . "'"), $str_errMsg_upd, false);
                $upd_cf_ctwo_remarks = db::query('update', array('tableName' => 'SWD_CF_CTWO_REMARKS', 'fieldWithValue' => $ary_data_remark, 'condition' => "CTWO_ID = '" . $ctwo_id . "'"), $str_errMsg_upd, false);
                $audit_trial = db::query('insert', array('tableName' => 'SWD_AUDIT_TRAIL', 'fieldWithValue' => $ary_data_audittrial), $str_errMsg_upd, false);
               // $upd_cf_ctwo = db::query('update', array('tableName' => 'SWD_CF_CTWO_HISTORY', 'fieldWithValue' => $ctwo_history_remarks, 'condition' => "ID = '" . $ctwo_id . "'"), $str_errMsg_upd, false);

                if ($str_errMsg_upd) {
                    throw new errors($str_errMsg_upd);
                }

                echo ("<SCRIPT LANGUAGE='JavaScript'>
                 window.alert('Succesfully Updated');
                 window.opener.location.reload();
                  window.close();
        </SCRIPT>");
            } catch (errors $e) {
                $this->runScript('alert', array(htmlspecialchars($e->message())));
            }
        }


        return $this->updateform($ctwo_id);
    }

    private function updateform($ctwoid) {


        $show_rslt = "";
        try {

            $var_remarks = db::callSQL('SQL_cf', 'REMARKS', array($ctwoid));
            $var_results = db::callSQL('SQL_cf', 'CTWOUPDATE', array($ctwoid));
            $var_results_1 = db::callSQL('SQL_cf', 'RESCODE', array('resolution code'));
            $var_results_2 = db::callSQL('SQL_cf', 'OWNERWG', array('owner workgroup'));
            $var_results_3 = db::callSQL('SQL_cf', 'ROOTCC', array('root cause category'));

            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {


                $remarks = "";
                if (isset($var_remarks[0]['REMARKS'])) {
                    $remarks = $var_remarks[0]['REMARKS'];
                }

                $rescode = $var_results_1;
                $ownerwg = $var_results_2;
                $rootcat = $var_results_3;
                $reslc = $var_results[0]['RESOLUTION_CODE'];
                $owner = $var_results[0]['OWNER_WORKGROUP'];
                $root = $var_results[0]['ROOT_CAUSE_CAT'];


                $show_upd_rslt = "<div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CTWO Update</div><br><div id='div-update'><form method='post' action=''><table  cellpadding=5' cellspacing='3' align='center' ><tr><fieldset style='width:300px;margin:auto;'><legend>Supervisor's Finding:</legend><textarea cols='50' name='remark'>$remarks</textarea></fieldset></tr>";



                $show_upd_rslt .= "<tr><td>Owner Workgroup:</td><td><select name='ownerwg'>";
                //retrieve options for owner workgroup from database it same it selected in drop down
                for ($i = 0; $i < count($ownerwg); $i++) {
                    $opt = $ownerwg[$i]['CODE'];
                    $show_upd_rslt.="<option value='$opt'";
                    if ($opt == $owner) {
                        $show_upd_rslt.= "selected=selected";
                    }

                    $show_upd_rslt.=">$opt</option>";
                }

                $show_upd_rslt .= "</select><tr><td>Root caused category</td><td><select name='rootcc'>";
                //retrieve options for root cause category from database it same it selected in drop down
                for ($i = 0; $i < count($rootcat); $i++) {
                    $opt1 = $rootcat[$i]['CODE'];
                    $show_upd_rslt.="<option value='$opt1'";
                    if ($opt1 == $root) {
                        $show_upd_rslt.= "selected=selected";
                    }
                    $show_upd_rslt.=">$opt1</option>";
                }


                $show_upd_rslt .="</select></td></tr>   
        <tr><td>Resolution Code</td><td><select name='rescode'>";
                //retrieve options for resolution code workgroup from database it same it selected in drop down
                for ($i = 0; $i < count($rescode); $i++) {
                    $opt2 = $rescode[$i]['CODE'];
                    $show_upd_rslt.="<option value='$opt2'";
                    if ($opt2 == $reslc) {
                        $show_upd_rslt.= "selected=selected";
                    }
                    $show_upd_rslt.=">$opt2</option>";
                }

                $show_upd_rslt .="</select></td></tr><tr><td>";




                if ($remarks == "") {
                    $show_upd_rslt .= "<input type='submit' value='Save' name='save'/>";
                } else {
                    $show_upd_rslt .= "<input type='submit' value='Update' name='update'/>";
                }
                $show_upd_rslt .="</td><td><button onclick=\" javascript:window.close(); \">Cancel</button></td></tr>
    </table></form></div>";

                $show_upd_rslt .= "</table>";
                return $show_upd_rslt;
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function css() {
        return "#div-update{
                border:2px solid #000000;
                width:500px;
                margin: auto;
            }
            a{
                text-decoration: none;
            }";
    }

}
