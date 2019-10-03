
<?php

/*
 * custfeedback-Ctwo Performance Individual Mobile
 *
 * @author ismath Khan  
 */

class CtwoPerformance_Indvlmobile extends gui {

    protected function content() {



        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('thermometer/jquery-latest.min.js');
        $this->importPlugin('thermometer/jquery.thermometer.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');


        $form = "<div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CTWO - Performance Individual for Mobile</div><br><table cellpadding='15' cellspacing='10'>    
                    <tr>
                    <td><strong>Filter By Month And Year&nbsp;:</strong></td>    
              <td><input type='text' id='datepicker' size='5'/></td> <td><strong>Filter By Year&nbsp;:</strong></td><td><select id='fltr_year'><option>-Choose-</option>";
        for ($i = 1; $i < 100; $i++) {
            $year = 2003 + $i;
            $form .= "<option>" . $year . "</option>";
        }


        $form .= "</select></td> <td><strong>Filter By Week&nbsp;:</strong></td>    
              <td><input type='text' id='week-picker' size='5'/></td>
                  </tr>
                  
             <input type=\"hidden\" id=\"secno\" name=\"secno\" value=\"" . ((isset($_REQUEST['secno']) && $_REQUEST['secno'] != '') ? $_REQUEST['secno'] : 0) . "\" />        
         </table><br>";

        $mobile_valid_user = $_GET['mobile_indvl_staff_no'];
        $year = util::fetch('year');
        $month = util::fetch('monthyear');
        $week_start = util::fetch('startdate');
        $week_end = util::fetch('enddate');


        return $form . $this->performance($mobile_valid_user, $year, $month, $week_start, $week_end);
    }

    private function performance($mobile_user, $byyear, $bymonth, $start_week, $end_week) {

        try {

            if ($byyear != '') {
                $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_INDVL_BYYEAR', array("'$mobile_user'", "'$byyear'"));
            } else if ($bymonth != '') {

                $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_INDVL_BYMONTH', array("'$mobile_user'", "'$bymonth'"));
            } else if ($start_week != '' && $end_week != '') {
                $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_INDVL_BYWEEK', array("'$mobile_user'", "'$start_week'", "'$end_week'"));
            } else {

                $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_INDVL_STAFF', array("'$mobile_user'"));
            }

            $var_results_staff = db::callSQL('SQL_cf', 'MOBILE_STAFF_NAME', array("'$mobile_user'"));

            $staff_no = $var_results_staff[0]['NAME'] .'&nbsp;&nbsp;'. "[" . $var_results_staff[0]['STAFF_NO'] . "]";





            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {
                $staffno = "";
                if (isset($var_results[0]['TEAMNAME'])) {
                    $staffno = $var_results[0]['TEAMNAME'];
                }
                $workorderno = "-";
                if (isset($var_results[0]['WORKORDERNO'])) {
                    $workorderno = $var_results[0]['WORKORDERNO'];
                }
                $feedback = "-";
                if (isset($var_results[0]['FEEDBACK'])) {
                    $feedback = $var_results[0]['FEEDBACK'];
                }
                $avg = "-";
                if (isset($var_results[0]['AVG'])) {
                    $avg = $var_results[0]['AVG'];
                }
                $lowest = "-";
                if (isset($var_results[0]['LOWEST'])) {
                    $lowest = $var_results[0]['LOWEST'];
                }
                $highest = "-";
                if (isset($var_results[0]['HIGHEST'])) {
                    $highest = $var_results[0]['HIGHEST'];
                }

                $show_rslt = "<div><table style='margin-left:10px;'><tr>";
                if ($bymonth) {
                    $show_rslt .="<td><b>Month/Year :</b></td><td>$bymonth&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                }
                if ($byyear) {
                    $show_rslt .="<td><b>Year :</b></td><td>$byyear&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                }
                if ($start_week && $end_week) {

                    $week_st = str_replace("/", "-", $start_week);
                    $week_end = str_replace("/", "-", $end_week);

                    $start_date = date("d M Y", strtotime($week_st));
                    $end_date = date("d M Y", strtotime($week_end));

                    $show_rslt .="<td><b>Week :</b></td><td>$start_date&nbsp;&nbsp;<b>-</b>&nbsp;&nbsp;$end_date</td></tr></table></div>";
                }


                $show_rslt .= "<input type='hidden' id='staffno' value=$mobile_user />
                   <div> <table cellpadding='1' cellspacing='0' id='thermometer'  style='margin-top:60px;'>
<tr>
  <!--td><button id='year'>Year</button></td>
  <td><button id='month'>Month</button></td>		
  <td><button id='day'>Day</button></td-->
  <td colspan='3'><div style='text-align:center;'>$staff_no</div></td>

</tr>
<tr>";

                $fbpercent = "0";
                if (isset($var_results[0]['FBPERCENT'])) {
                    $fbpercent = $var_results[0]['FBPERCENT'];
                }

                $show_rslt .= "<td colspan='3' id='percentage_tbl' style='text-align:center;'><table align='center';><tr><td>FB&nbsp;-&nbsp;</td><td id='percentage'>" . $fbpercent . "%</td></tr></table></td>		
  
</tr>
<tr><td>100
<div id='percent' data-percent='0' data-orientation='vertical' data-animate='false'>
  <div class='thermometer-outer thermometer-outer-v'>
    <div class='thermometer-inner thermometer-inner-v'></div>
  </div>
</div>0</td>
<td colspan='2'><div><table cellpadding='10' cellspacing='10' style='margin-left:10px;'>
<tr><td>No of Job Completion:</td><td>" . $workorderno . "</td></tr>
<tr><td>Feedback:</td><td>" . $feedback . "</td></tr>
<tr><td>Average Feedback:</td><td>" . $avg . "</td></tr>
<tr><td>Lowest Feedback:</td><td>" . $lowest . "</td></tr>
<tr><td>Highest Feedback:</td><td>" . $highest . "</td></tr>

</table></div></td></tr>
</table></div>";
                return $show_rslt;
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    protected function js() {

        return "
    $(document).ready(function() {
    var percent_val = parseInt($('#percentage').text());
    
 $('#datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
          showOn: 'button',
         buttonImage: 'images/sword/icons/calender.png',
                buttonImageOnly: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy'
    }).focus(function() {
            var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
            var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
            var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
            var val = thisCalendar.datepicker('setDate', new Date(year, month, 2));
           var mon_year = $('#datepicker').val();
           
var uri = encodeURIComponent('monthyear='+ mon_year);
            
        var staffno  = $('#staffno').val();
               
        window.location.href ='?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('monthyear='+mon_year);
        
        });
    });

/**filter by year **/

$('#fltr_year').change(function(){
        
      var staffno  = $('#staffno').val();
        var year = $(this).val();
        window.location.href ='?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('year='+year);
      
});/* end of select */

/*filter by week*/


                    var startDate;
                    var endDate;
                     var selectCurrentWeek = function() {
                        window.setTimeout(function() {
                            $('#week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
                        }, 1);
                    };
$('#week-picker').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showOtherMonths: true,
                        selectOtherMonths: true,
                         showOn: 'button',
                          dateFormat: 'dd/mm/yy',
         buttonImage: 'images/sword/icons/calender.png',
                buttonImageOnly: true,
                            
        showButtonPanel: true,
                        onSelect: function(dateText, inst) {
                            var date = $(this).datepicker('getDate');
                            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
                            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
                            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                            $('#startDate').text($.datepicker.formatDate(dateFormat, startDate, inst.settings));
                            $('#endDate').text($.datepicker.formatDate(dateFormat, endDate, inst.settings));

                            selectCurrentWeek();
                            var week_start = $.datepicker.formatDate(dateFormat, startDate, inst.settings);
                            var  week_end = $.datepicker.formatDate(dateFormat, endDate, inst.settings);
                             var staffno  = $('#staffno').val();
                            window.location.href = '?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('startdate='+week_start+'&enddate='+week_end);
                        },
                        beforeShowDay: function(date) {
                            var cssClass = '';
                            if (date >= startDate && date <= endDate)
                                cssClass = 'ui-datepicker-current-day';
                            return [true, cssClass];
                        },
                        onChangeMonthYear: function(year, month, inst) {
                            selectCurrentWeek();
                        }
                    });

$('#percent').thermometer({
  percent: percent_val, 
  
});
 

    $('#year').click(function(){
          var year = new Date().getFullYear();
          var staffno  = $('#staffno').val();
        
        window.location.href ='?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('year='+year);
});
$('#month').click(function(){
           var date = new Date();
            var mon =  date.getMonth() + 1;
            var year  = '/' + date.getFullYear();
                         var month = mon+year;
                         
            var staffno  = $('#staffno').val();
               
        window.location.href ='?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('month='+month);
});
$('#day').click(function(){
          var date = new Date();
          var day = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
          var staffno  = $('#staffno').val();
          alert(staffno);
        window.location.href ='?clsid=CtwoPerformance_Indvlmobile&mobile_indvl_staff_no='+staffno+'&clspar='+encodeURIComponent('day='+day);
});

 



                    
 });/* end of jquery/




";
    }

    protected function css() {
        return "
            #thermometer {
                border:1px solid #000000;
                width:350px;
                height:400px;
                margin:auto;
                outline:20px solid #EEA244;
            }
            
#percentage_tbl{
      width:400px;
      border:1px solid #000000;
      height:20px;
margin-left:23px;
}

#percent{
padding:20px;
}
.thermometer-outer {
  background: #c4f0ff;
  border: 1px solid #c4c4c4;
  border-radius: 3px;
}
.thermometer-outer-h {
  height: 20px;
  width: 100%;
}
.thermometer-outer-v {
  height: 200px;
  width: 20px;
}
.thermometer-inner {
  background: #3f83f7;
}
.thermometer-inner-h {
  height: 20px;
}
.thermometer-inner-v {
  width: 20px;
}
";
    }

}
