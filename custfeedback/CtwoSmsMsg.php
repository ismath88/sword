<?php

/*
 * custfeedback-Admin module-Ctwo CF SMS Message
 *
 * @author ismath Khan  
 */

class CtwoSmsMsg extends gui {

    protected function content() {

        $doc_no = util::fetch("docketno");

        return $this->viewsmsmsg($doc_no);
    }

    private function viewsmsmsg($docketno) {

        try {

            $var_results = db::callSQL('SQL_cf', 'CF_SMS_MSG', array("'$docketno'"));
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = "<div><div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CF SMS Message</div><br><table border =\"1\"  cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><tr style=\"border:1px solid #000000;background:#EEA244;\"><th>No</th><th>Date</th><th>Message</th><th>SMS Status</th>";


                for ($i = 0; $i < count($var_results); $i++) {

                    //$id = $var_results[$i]['CTWOID'];
                    $no = $i + 1;
                    $show_rslt .= "<tr><td>" . $no . "</td><td>" . $var_results[$i]['SMS_DATE'] . "</td><td>" . $var_results[$i]['MESSAGE'] . "</td><td>" . $var_results[$i]['MSG_CODE'] . "</td></tr>";
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
