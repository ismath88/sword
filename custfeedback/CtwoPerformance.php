
<?php

/*
 * custfeedback-Ctwo Performance
 *
 * @author ismath Khan  
 */

class CtwoPerformance extends gui {

    protected function content() {



        $monthyear = util::fetch('monthyear');
        $yearonly = util::fetch('year');
        $week_start = util::fetch('startdate');
        $week_end = util::fetch('enddate');



        //$valid_user = $_SESSION['valid_user'];
        $valid_user = "sword";

//$ctwoid = util::fetch("ctwoid");
//        $this->importPlugin('sword/css/fullcalendar.css');
//        $this->importPlugin('sword/js/jquery.min.js');
//        $this->importPlugin('sword/js/jquery-ui.custom.min.js');
//        $this->importPlugin('sword/js/fullcalendar.min.js');

        $this->importPlugin('DataTables-1.9.4/media/js/jquery.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');

        $this->importPlugin('DataTables-1.9.4/media/css/jquery.dataTables.css');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.js');
        $this->importPlugin('DataTables-1.9.4/media/js/jquery.dataTables.min.js');






        $form = " <div style=\"background:#EEA244;text-align:center;font-weight:bold;\">CTWO - Performance Team</div><br><table cellpadding='5' cellspacing='50'>    
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
         </table> 
         ";



        return $form . $this->performance($monthyear, $yearonly, $week_start, $week_end, $valid_user);
    }

    private function performance($monthyear, $year,$start_week, $end_week, $user) {

        try {

            if ($user == 'sword') {
                if ($monthyear != '') {
                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_BYMONTHYEAR', array("'$monthyear'"));
                } else if ($year != '') {

                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_BYYEAR', array("'$year'"));
                } else if ($start_week != '' && $end_week != '') {
                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_BYWEEK', array("'$start_week'", "'$end_week'"));
                } else {

                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE');
                }
            } else {
                if ($monthyear != '') {
                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_SUPVSR_BYMONTHYEAR', array("'$monthyear'", "'$user'"));
                } else if ($year != '') {

                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_SUPVSR_BYYEAR', array("'$year'", "'$user'"));
                } else if ($start_week != '' && $end_week != '') {
                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_SUPVSR_BYWEEK', array("'$start_week'", "'$end_week'", "'$user'"));
                } else {

                    $var_results = db::callSQL('SQL_cf', 'PERFORMANCE_SUPVSR', array("'$user'"));
                }
            }

            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {

                $show_rslt = "<div id=\"datagridFrame\"><div><table cellpadding='5' cellspacing='50'><tr>";
                if ($monthyear) {
                    $show_rslt .="<td><b>Month/Year :</b></td><td>$monthyear&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                }
                if ($year) {
                    $show_rslt .="<td><b>Year :</b></td><td>$year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                }
                if ($start_week && $end_week) {

                    $week_st = str_replace("/", "-", $start_week);
                    $week_end = str_replace("/", "-", $end_week);

                    $start_date = date("d M Y", strtotime($week_st));
                    $end_date = date("d M Y", strtotime($week_end));

                    $show_rslt .="<td><b>Week :</b></td><td>$start_date&nbsp;&nbsp;<b>-</b>&nbsp;&nbsp;$end_date</td>";
                }
                $show_rslt .= "</tr></table></div><table border =\"1\"  id=\"datagrid\" cellpadding=\"10\" cellspacing=\"10\" width=\"100%\"><thead><tr style=\"border:1px solid #000000;background:#EEA244;\"><th>No</th><th>Name(Staff No)</th><th>No of Job Completion </th><th>Feedback</th>"
                        . "<th>FB%</th><th>Avg</th><th>Lowest</th><th>Highest</th></thead>";





                for ($i = 0; $i < count($var_results); $i++) {
                    $staffno = "";
                    if (isset($var_results[$i]['TEAMNAME'])) {
                        $staffno = $var_results[$i]['TEAMNAME'];
                    }
                    $workorderno = "-";
                    if (isset($var_results[$i]['WORKORDERNO'])) {
                        $workorderno = $var_results[$i]['WORKORDERNO'];
                    }
                    $feedback = "-";
                    if (isset($var_results[$i]['FEEDBACK'])) {
                        $feedback = $var_results[$i]['FEEDBACK'];
                    }
                    $avg = "-";
                    if (isset($var_results[$i]['AVG'])) {
                        $avg = $var_results[$i]['AVG'];
                    }
                    $lowest = "-";
                    if (isset($var_results[$i]['LOWEST'])) {
                        $lowest = $var_results[$i]['LOWEST'];
                    }
                    $highest = "-";
                    if (isset($var_results[$i]['HIGHEST'])) {
                        $highest = $var_results[$i]['HIGHEST'];
                    }

                    $no = $i + 1;

                    $show_rslt .= "<tr ><td>" . $no . "</td><td>" . $var_results[$i]['TEAMNAME'] . "</td><td>" . $workorderno . "</td><td>" . $feedback . "</td><td>" . $var_results[$i]['FBPERCENT'] . "</td>"
                            . "<td>" . $avg . "</td><td>" . $lowest . "</td><td>" . $highest . "</td>";
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
         
                        
            
         window.location.href = '?clsid=CtwoPerformance&clspar='+encodeURIComponent('monthyear='+ mon_year);
        

           /*actBtnEvt.ajaxSend('" . __CLASS__ . "','monthyear='+ mon_year,function(){
								if(actBtnEvt.xmlHttpComObj.readyState == 4) {
									if(actBtnEvt.xmlHttpComObj.responseText){
										alert('ok');
									}
				 					else {
										alert(actBtnEvt.xmlHttpComObj.responseText);
									}
								}
							});*/
        });
    });
     
     

$('#fltr_year').change(function(){
     
      var year = $(this).val();
      window.location.href = '?clsid=CtwoPerformance&clspar='+encodeURIComponent('year='+year);
      
});/* end of select */

/* Select Week */

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
                             window.location.href = '?clsid=CtwoPerformance&clspar='+encodeURIComponent('startdate='+week_start+'&enddate='+week_end);
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

  $('#week-picker .ui-datepicker-calendar tr').live('mousemove', function() {
                        $(this).find('td a').addClass('ui-state-hover');
                    });
                    $('#week-picker .ui-datepicker-calendar tr').live('mouseleave', function() {
                        $(this).find('td a').removeClass('ui-state-hover');
                    });
                    
 });/* end of jquery/




";
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
                }
           

";
    }

}
