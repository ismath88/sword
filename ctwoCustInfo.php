<?php

/*
 * custfeedback-Admin module-Customer Info
 *
 * @author ismath Khan  
 */

class CtwoCustInfo extends gui {

    protected function content() {

        $serviceno = util::fetch("svc_no");
        $source = util::fetch("source");
        $ttno = util::fetch("ttno");


        return $this->ticketinfo($ttno, $source, $serviceno) . $this->custinfo($serviceno, $source) . $this->networkinfo($serviceno, $source);
    }

    private function ticketinfo($tt_no, $source, $service_no) {

        try {
            if ($source == "Assurance") {
                $var_results = db::callSQL('SQL_cf', 'TICKETINFO', array("'$tt_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    // if fetching data from from db is empty- to display field name
                    $empt_array = array();
                    if ($var_results == $empt_array) {
                        $var_results = array(0 => Array("TTNO" => "", "CTTID" => "", "ACTTYPE" => "", "DIAGNOSIS" => "", "CREATED_DATE" => "", "CURJOBSTATUS" => "", "PRIORITY" => "", "RPTCOUNTER" => "", "RESDLE_DATE" => "", "REAPT_COUNT" => "", "APT_TIME" => "", "ACTIVITY_ID" => "", "ACT_PLNSTART" => "", "ACT_PLNEND" => ""));
                    }
                    $show_rslt = "<table  border='1'  cellspacing='0'>";

                    $show_rslt.= "<tr><th colspan='2'>Ticket Info</th></tr><tr><td>TT Number</td><td>" . $var_results[0]['TTNO'] . "</td></tr><tr><td>CTT ID</td><td>" . $var_results[0]['CTTID'] . "</td></tr><tr><td>Activity Type</td><td>" . $var_results[0]['ACTTYPE'] . "</td></tr><tr><td>Diagnosis</td><td>" . $var_results[0]['DIAGNOSIS'] . "</td></tr><tr><td>Created Date</td><td>" . $var_results[0]['CREATED_DATE'] . "</td></tr><tr><td>Current Job Status</td><td>" . $var_results[0]['CURJOBSTATUS'] . "</td></tr>"
                            . "<tr><td>Priority</td><td>" . $var_results[0]['PRIORITY'] . "</td></tr><tr><td>Repeat Report Counter</td><td>" . $var_results[0]['RPTCOUNTER'] . "</td></tr><tr><td>Multiple Report Counter</td><td>" . $var_results[0]['RPTCOUNTER'] . "</td></tr><tr><td>Service Type</td><td></td></tr><tr><td>Package Name</td><td></td></tr>"
                            . "<tr><td>Re-Appointment Counter</td><td>" . $var_results[0]['REAPT_COUNT'] . "</td></tr><tr><td>Appt.Start Time/Reschedule Date</td><td>" . $var_results[0]['APT_TIME'] . "</td></tr><tr><td>Activity ID</td><td>" . $var_results[0]['ACTIVITY_ID'] . "</td></tr><tr><td>Activity Planned Start(ETTA)</td><td>" . $var_results[0]['ACT_PLNSTART'] . "</td></tr><tr><td>Activity Planned End(ETTA)</td><td>" . $var_results[0]['ACT_PLNEND'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    return $show_rslt;
                }
            } else {
                $var_results = db::callSQL('SQL_cf', 'FL_CUSTDETAIL', array("'$service_no'"));
                $customerName = "";
                if (isset($var_results[0]['CUSTNAME'])) {
                    $customerName = $var_results[0]['CUSTNAME'];
                }

                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

//                    $empt_array = array();
//                    if($var_results == $empt_array){
//                        $var_results = array([0] => Array ( "TTNO" => "","CTTID"=>"","ACTTYPE"=>"","DIAGNOSIS" =>"","CREATED_DATE"=>"","CURJOBSTATUS"=>"","PRIORITY"=>"","RPTCOUNTER"=>"","RESDLE_DATE" =>"", "REAPT_COUNT" =>"","APT_TIME"=>"","ACTIVITY_ID"=>"","ACT_PLNSTART"=>"","ACT_PLNEND" =>"") );
//                    }
//                    $show_rslt = "<table  border='1'  cellspacing='' cellpadding='5' align='center' style='width:inherit'>";
//
//                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $customerName . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
//                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr>"
//                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
//                    $show_rslt .= "</table>";
//                    return $show_rslt;
                }
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    private function custinfo($service_no, $source) {

        try {
            if ($source == "Assurance") {
                $var_results = db::callSQL('SQL_cf', 'CUSTDETAIL', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    // if fetching data from from db is empty- to display field name
                    $empt_array = array();
                    if ($var_results == $empt_array) {
                        $var_results = array(0 => Array("CUSTNAME" => "", "CONTACT_NAME" => "", "CONTACTNO" => "", "MOBILENO" => "", "ADDRESS" => "", "SEGMGROUP" => "", "CATEGORY" => "", "LOGINID" => "", "PRODUCT" => "", "SERVICEAFF" => "", "SERVICENO" => ""));
                    }

                    $show_rslt = "<table  border='1'  cellspacing='0'  style='width:inherit'>";

                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Category</td><td>" . $var_results[0]['CATEGORY'] . "</td></tr><tr><td>Login ID</td><td>" . $var_results[0]['LOGINID'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr><tr><td>Service Affected</td><td>" . $var_results[0]['SERVICEAFF'] . "</td></tr>"
                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    return $show_rslt;
                }
            } else {
                $var_results = db::callSQL('SQL_cf', 'FL_CUSTDETAIL', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    $show_rslt = "<table  border='1'  cellspacing='10' cellpadding='10' >";

                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr>"
                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    return $show_rslt;
                }
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    private function networkinfo($service_no, $source) {

        try {
            if ($source == "Assurance") {
                $var_results = db::callSQL('SQL_cf', 'NETWORKINFO', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    $show_rslt = "<table  border='1'  cellspacing='0'>";

                    $show_rslt.= "<tr><th colspan='2'>Network Info</th></tr><tr><td>Cabinet ID</td><td>" . $var_results[0]['CABINET_ID'] . "</td></tr><tr><td>Data Installed</td><td></td></tr><tr><td>Cabinet Type</td><td>" . $var_results[0]['CABINET_TYPE'] . "</td></tr><tr><td>DP/DP Pair</td><td>" . $var_results[0]['DP_PAIR'] . "</td></tr><tr><td>DSLAM Id</td><td>" . $var_results[0]['DSLAM_ID'] . "</td></tr><tr><td>DSLAM type</td><td>" . $var_results[0]['DSLAM_TYPE'] . "</td></tr>"
                            . "<tr><td>Port Id</td><td>" . $var_results[0]['PORT_ID'] . "</td></tr><tr><td>VCI Id</td><td>" . $var_results[0]['VCI_ID'] . "</td></tr><tr><td>ADSL pair No</td><td>" . $var_results[0]['ADSL_PAIR_NO'] . "</td></tr><tr><td>ADSL In out</td><td>" . $var_results[0]['ADSL_IN_OUT'] . "</td></tr><tr><td>LEN</td><td>" . $var_results[0]['LEN'] . "</td></tr>"
                            . "<tr><td>BAR Pair</td><td>" . $var_results[0]['BAR_PAIR'] . "</td></tr><tr><td>HBAR Pair</td><td>" . $var_results[0]['HBAR_PAIR'] . "</td></tr><tr><td>Card type</td><td>" . $var_results[0]['CARD_TYPE'] . "</td></tr><tr><td>NIS Service ID</td><td>" . $var_results[0]['NIS_SERVICE_ID'] . "</td></tr><tr><td>NIS Status</td><td>" . $var_results[0]['NIS_STATUS'] . "</td></tr>"
                            . "<tr><td>Equip Name</td><td>" . $var_results[0]['D_EQUIP_NAME'] . "</td></tr><tr><td>Speed</td><td>" . $var_results[0]['D_SPEED'] . "</td></tr><tr><td>Access Type</td><td>" . $var_results[0]['D_ACCESS_TYPE'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    return $show_rslt;
                }
            } else {
                $var_results = db::callSQL('SQL_cf', 'FL_CUSTDETAIL', array("'$service_no'"));
                if (!is_array($var_results)) {
                    throw new errors($var_results);
                } else {

                    $show_rslt = "<table  border='1'  cellspacing='10' cellpadding='10'>";

                    $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                            . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['PRODUCT'] . "</td></tr>"
                            . "<tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                    $show_rslt .= "</table>";
                    return $show_rslt;
                }
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    private function nisinfo($service_no, $source) {
        try {
            if ($source == 1) {
                $var_results = db::callSQL('SQL_cf', 'CUSTDETAIL', array("'$service_no'"));
            } else {
                $var_results = db::callSQL('SQL_cf', 'FL_CUSTDETAIL', array("'$service_no'"));
            }
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {


                $show_rslt = "<table  border='1'  cellspacing='0' align='center'>";

                $show_rslt.= "<tr><th colspan='2'>Customer Info</th></tr><tr><td>Customer Name</td><td>" . $var_results[0]['CUSTNAME'] . "</td></tr><tr><td>Customer Site Name</td><td></td></tr><tr><td>Contact Name</td><td>" . $var_results[0]['CONTACT_NAME'] . "</td></tr><tr><td>Contact No</td><td>" . $var_results[0]['CONTACTNO'] . "</td></tr><tr><td>Mobile No</td><td>" . $var_results[0]['MOBILENO'] . "</td></tr><tr><td>Address</td><td>" . $var_results[0]['ADDRESS'] . "</td></tr>"
                        . "<tr><td>Segment Group</td><td>" . $var_results[0]['SEGMGROUP'] . "</td></tr><tr><td>Category</td><td>" . $var_results[0]['CATEGORY'] . "</td></tr><tr><td>Login ID</td><td>" . $var_results[0]['LOGINID'] . "</td></tr><tr><td>Product</td><td>" . $var_results[0]['LOGINID'] . "</td></tr><tr><td>TT Subtype</td><td>" . $var_results[0]['TTSUBTYPE'] . "</td></tr><tr><td>Service Affected</td><td>" . $var_results[0]['TTSUBTYPE'] . "</td></tr><tr><td>Symptoms</td><td>" . $var_results[0]['SYMPTOM'] . "</td></tr><tr><td>Cause Code</td><td></td></tr>"
                        . "<tr><td>Cause Code Category</td><td></td></tr><tr><td>Disp Code</td><td></td></tr><tr><td>Remark</td><td>" . $var_results[0]['REMARK'] . "</td></tr><tr><td>Comment</td><td>" . $var_results[0]['COMMENTS'] . "</td></tr><tr><td>Service/VOBB Number</td><td>" . $var_results[0]['SERVICENO'] . "</td></tr>";
                $show_rslt .= "</table>";
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
                width:1000px;
            }
            .det-info{
            background:#FFA500;
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
                }";
    }

}
