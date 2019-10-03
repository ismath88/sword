
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/base/jquery-ui.css">
            <script type="text/javascript">
                $(function() {
                    var startDate;
                    var endDate;

                    var selectCurrentWeek = function() {
                        window.setTimeout(function() {
                            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
                        }, 1);
                    }

                    $('.week-picker').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        onSelect: function(dateText, inst) {
                            var date = $(this).datepicker('getDate');
                            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
                            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
                            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                            $('#startDate').text($.datepicker.formatDate(dateFormat, startDate, inst.settings));
                            $('#endDate').text($.datepicker.formatDate(dateFormat, endDate, inst.settings));

                            selectCurrentWeek();
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

                    $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() {
                        $(this).find('td a').addClass('ui-state-hover');
                    });
                    $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() {
                        $(this).find('td a').removeClass('ui-state-hover');
                    });





                });
            </script>
    </head>





    <body>
        <div class="week-picker"></div>
        <br /><br />
        <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span>

        <a href="" id="previous">&laquo; Previous</a>
        <input type="text" value="10/30/2009" id="datepicker" />
        <a href="" id="next">Next &raquo;</a>
    </body>
</html>

<!--<form id="dateForm">
    <script src="@Url.Content("~/Scripts/jquery-1.5.1.min.js")" type="text/javascript"></script>
    <script type="text/javascript" src="@Url.Content("~/Scripts/libs/jquery-ui.min.js")"></script> 

    <script language="javascript">
        $(document).ready(function() {
            $('.date').datepicker({dateFormat: "yy-mm-dd"});
        });
    </script>
    <script type="text/javascript">
        $(function() {

            var startDate;
            var endDate;
            var clickDate;
            var selectCurrentWeek = function() {
                window.setTimeout(function() {
                    $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
                }, 1);
            }

            var selectCurrentClick = function() {
                window.setTimeout(function() {
                    $('.week-picker').find('.ui-datepicker-current-clickday a').addClass('ui-state-error')
                }, 1);
            }

            var selectCurrentWeek2 = function() {
                alert("v");
                window.setTimeout(function() {
                    $('.week-picker').find('.ui-datepicker-current-day a').addClass('')
                }, 1);
            }

            var selectCurrentClick2 = function() {
                alert("w");
                window.setTimeout(function() {
                    $('.week-picker').find('.ui-datepicker-current-clickday a').addClass('')
                }, 1);
            }

            // 1 = monday, 2 = tuesday, 3 = wednesday, 4 = thursday, 5=friday, 6 = saturday, 0=sunday
            var daysToDisable = [0, 6];

            $('.week-picker').datepicker({
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showWeek: false,
                changeYear: true,
                        changeMonth: true,
                        selectOtherMonths: true,
                onSelect: function(dateText, inst) {
                    var date = $(this).datepicker('getDate');
                    startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
                    endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 5);
                    clickDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                    $("#startDate").val($.datepicker.formatDate(dateFormat, startDate, inst.settings));
                    $("#endDate").val($.datepicker.formatDate(dateFormat, endDate, inst.settings));
                    $("#datestr").val($.datepicker.formatDate(dateFormat, date, inst.settings));
                    selectCurrentWeek();
                    selectCurrentClick();
                },
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    for (i = 0; i < daysToDisable.length; i++) {

                        if ($.inArray(day, daysToDisable) != -1) {
                            return [false];
                        }
                    }

                    var cssClass = '';

                    if (date >= startDate && date <= endDate) {
                        cssClass = 'ui-datepicker-current-day';
                    }
                    if (clickDate != undefined) {
                        if (clickDate.toString() == date.toString()) {
                            cssClass = 'ui-datepicker-current-clickday';
                        }
                    }
                    return [true, cssClass];

                },
                onChangeMonthYear: function(year, month, inst) {
                    selectCurrentWeek();
                }
            }).click(function() {

                var date = ($('.week-picker').datepicker("getDate"));

                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
                endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 5);
                clickDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                var dateFormat = $.datepicker._defaults.dateFormat;
                $("#startDate").val($.datepicker.formatDate(dateFormat, startDate));
                $("#endDate").val($.datepicker.formatDate(dateFormat, endDate));
                $("#datestr").val($.datepicker.formatDate(dateFormat, date));

                $(".ui-datepicker-current-day").removeClass('ui-state-active');
                $(".ui-datepicker-current-clickday").removeClass('ui-state-active');
                if (date >= startDate && date <= endDate) {
                    $(".ui-datepicker-current-day a").removeClass('ui-state-active');
                    $(".ui-datepicker-current-day").addClass('ui-datepicker-current-day');


                }
                if (clickDate != undefined) {
                    if (clickDate.toString() == date.toString()) {
                        $(".ui-datepicker-current-clickday a").removeClass('ui-state-active');
                        $(".ui-datepicker-current-clickday").addClass('ui-datepicker-current-clickday');

                    }
                }

            });


            $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() {
                $(this).find('td a').addClass('ui-state-hover');
            });
            $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() {
                $(this).find('td a').removeClass('ui-state-hover');
            });
        });
    </script> 
</head> 
<body> 
    <div class="week-picker"></div> 
    @*<br /><br /> 
    <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span>*@ 
    @using (Html.BeginForm())
    {


    @Html.TextBox("startDate", "")
    @Html.TextBox("endDate", "")
    @Html.TextBox("datestr", "")
    }-->

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//date("Y-m-d", strtotime("+1 week"));
//
//$date = strtotime(date("Y-m-d", strtotime($date)) . " +1 week");
//$year = 2013; 
//$week = 1;
//$prev_week = date("W", strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT) . ' -1 week'));
//echo $prev_week;
$dd = substr('2-1-414022300',2);
echo $dd; 

