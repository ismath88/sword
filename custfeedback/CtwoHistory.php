<?php

/*
 * custfeedback-Admin module-Ctwo History
 *
 * @author ismath Khan  
 */

class CtwoHistory extends gui {

    protected function content() {

        //$validuser = $_SESSION['valid_user'];
        $validuser = 'B14331';
        $serviceno = util::fetch("serviceno");

        return $this->viewhistory($serviceno);
    }

    private function viewhistory($service_no) {

        try {

//            if ($valid_user == 'sword') {
//                $var_results = db::callSQL('SQL_cf', 'HISTORY', array("'$service_no'"));
//            } else {
//                $var_results = db::callSQL('SQL_cf', 'HISTORY_SUPVSR', array("'$service_no'", "'$valid_user'"));
//            }

            $var_results = db::callSQL('SQL_cf', 'HISTORY', array("'$service_no'"));
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = "<div><div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CTWO History</div><br><table border =\"1\"  cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><tr style=\"border:1px solid #000000;background:#EEA244;\"><th>No</th><th>TT No/Order No</th><th>Date Created</th><th>Date Closed</th><th>Resolution Code</th>"
                        . "<th>Owner Workgroup</th><th>Root Caused Category</th><th>Action By</th><th>Rating</th>";

                for ($i = 0; $i < count($var_results); $i++) {

                    //$id = $var_results[$i]['CTWOID'];
                    $no = $i + 1;
                    $show_rslt .= "<tr><td>" . $no . "</td><td>" . $var_results[$i]['DOCKET_NUMBER'] . "</td><td>" . $var_results[$i]['CREATED_DATE'] . "</td><td>" . $var_results[$i]['CLOSED_DATE'] . "</td>"
                            . "<td>" . $var_results[$i]['RESOLUTION_CODE'] . "</td><td>" . $var_results[$i]['OWNER_WORKGROUP'] . "</td><td>" . $var_results[$i]['ROOT_CAUSE_CAT'] . "</td><td>" . $var_results[$i]['ATTEND_BY'] . "</td><td>" . $var_results[$i]['RATING'] . "</td>";
                }
                $show_rslt .= "</table><div style='text-align:center'><button onclick=\"javascript:window.close();\">Ok</button></div></div>";
                return $show_rslt;
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function css() {
        return "
            table {
                border:1px solid #000000;
            }
            tr th{
               background:#0000ff;
               border-bottom:2px solid #000000;
               color:#fff;
            }
            tr td{
                background:#e0e0e0;
                border:2px solid #fff;
                text-align:center;
            }
            tr td:first-child{
                font-weight: bold;
                }";
    }

}
