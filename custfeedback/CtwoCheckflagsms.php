<?php

/*
 * custfeedback-Admin module-Ctwo Audit Trial
 *
 * @author ismath Khan  
 */

class CtwoAudittrial extends gui {

    public function __toString() {
        if (util::fetch('delmenuid') != '') {
            return $this->deltblcode(util::fetch('delmenuid'));
        } else {
            return parent::__toString();
        }
    }

    protected function content() {

        //$ctwoid = util::fetch("ctwoid");
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');
        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');



        return $this->audittrial();
    }

    private function audittrial() {

        try {

            $var_results = db::callSQL('SQL_cf', 'AUDITTRIAL');
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = "<div id=\"datagridFrame\"><table border =\"1\"  id=\"datagrid\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><thead><tr style=\"border:1px solid #000000;background:#EEA244;\"><th>TTNO</th><th>Team Name</th><th>Created Date</th><th>Date Insert</th><th>Closed Date</th><th>Status</th><th>Customer Name</th>"
                        . "</thead>";

                for ($i = 0; $i < count($var_results); $i++) {



                    $show_rslt .= "<tr><td>" . $var_results[$i]['DOCKET_NUMBER'] . "</td><td>" . $var_results[$i]['TEAM_NAME'] . "</td><td>" . $var_results[$i]['CREATED_DATE'] . "</td>"
                            . "<td>" . $var_results[$i]['CLOSED_DATE'] . "</td>" . "<td>" . $var_results[$i]['DATE_INSERT'] . "</td>" . "<td>" . $var_results[$i]['CTWO_STATUS'] . "</td>" . "<td>" . $var_results[$i]['CUSTOMER_NAME'] . "</td></tr>";
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
