<?php

/*
 * custfeedback-Admin module-Customer Detail
 *
 * @author ismath Khan  
 */

class CtwoCustDetail extends gui {

    protected function content() {

        $serviceno = util::fetch("svc_no");
        $source = util::fetch("source");
        $id = util::fetch("ctwoid");

        return $this->view_custdetail($serviceno, $source, $id);
    }

    private function view_custdetail($service_no, $source, $ctwoid) {

        try {
            if ($source == "Assurance") {
                $var_results = db::callSQL('SQL_cf', 'CUSTDETAIL', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    //print_r($var_results);
                    $show_rslt = "<table  border='1'  cellspacing='0' align='center'>";

                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Category</td><td>" . $var_results[0]['CATEGORY'] . "</td></tr><tr><td>Login ID</td><td>" . $var_results[0]['LOGINID'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr><tr><td>Service Affected</td><td>" . $var_results[0]['SERVICEAFF'] . "</td></tr>"
                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    $show_rslt .= "<div class='hist_btn'><button id='history' class=\"btnhist\" onclick=\"return popitup('?clsid=CtwoHistory&clspar=" . urlencode("serviceno=" . $service_no) . "',' ','width=900,height=400,left=200');\">History</button></div>";
                    return $show_rslt;
                }
            } else {
                $var_results = db::callSQL('SQL_cf', 'FL_CUSTDETAIL', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    $show_rslt = "<table  border='1'  cellspacing='10' cellpadding='10' align='center'>";

                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr>"
                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    $show_rslt .= "<div class='hist_btn'><button id='history' class=\"btnhist\" onclick=\"return popitup('?clsid=CtwoHistory&clspar=" . urlencode("serviceno=" . $service_no) . "',' ','width=900,height=400,left=200');\">History</button></div>";
                    return $show_rslt;
                }
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {

        return"function popitup(url){newwindow=window.open(url,'name','width=900,height=400,left=200');
	if (window.focus) {newwindow.focus()}
	return false;}";
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
            }
            tr td:first-child{
                font-weight: bold;
                }
                
.hist_btn{


text-align:right;
}

";
    }

}
