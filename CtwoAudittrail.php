<?php

/*
 * custfeedback-Admin module-Ctwo Audit Trial
 *
 * @author ismath Khan  
 */

class CtwoAudittrail extends gui {

    protected function content() {

        //$ctwoid = util::fetch("ctwoid");
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');
        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');

        $valid_user = $_SESSION['valid_user'];
        //$valid_user = "sword";

        return $this->audittrail($valid_user);
    }

    private function audittrail($validuser) {

        try {
            if ($validuser == "sword") {
                $var_results = db::callSQL('SQL_cf', 'AUDITTRAIL');
            } else {
                $var_results = db::callSQL('SQL_cf', 'AUDITTRAIL_SUPVSR', array("'$validuser'"));
            }
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = "<div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CTWO - Audit Trail</div><br><div id=\"datagridFrame\"><table border =\"1\"  id=\"datagrid\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><thead><tr style=\"border:1px solid #000000;background:#EEA244;\"><th>TT No/Order No</th><th>Staff No</th><th>Date Insert</th><th>Status</th>"
                        . "</thead>";

                for ($i = 0; $i < count($var_results); $i++) {



                    $show_rslt .= "<tr><td>" . $var_results[$i]['DOCKET_NUMBER'] . "</td><td>" . $var_results[$i]['STAFFNO'] . "</td>"
                             . "<td>" . $var_results[$i]['DATE_INSERT'] . "</td>" . "<td>" . $var_results[$i]['CTWO_STATUS'] . "</td>" . "</tr>";
                }
                $show_rslt .= "</table></div>";
                return $show_rslt;
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {

        return "
    $(document).ready(function() {
     $('#datagrid').dataTable({
      'sScrollY': '1000px', 
        'bPaginate': true,
      'bScrollCollapse': true,
      'oLanguage': {
       'sSearch': 'Filter Text:'
      }
     });
     
     }); ";
    }

    protected function css() {
        return "
            table {
                border:1px solid #000000;
            }
           
            tr td{
               
                border:2px solid #fff;
                text-align:center;
            }
            tr td:first-child{
                font-weight: bold;
                }";
    }

}
