<?php

class DashboardCommonUI extends gui {

    /// BEGIN: Dynamic Dropdown Script For PTT/Zone/Building
    public static function pdq_PTTZoneBuild($str_pttLabel = "ptt", $str_zoneLabel = "zone", $str_buildingLabel = "build") {
        try {
            $var_results = db::callSQL('SQL_harezad', 'AllPTT');
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {
                $str_opt = "<option value=''>All</option>";
                for ($i = 0; $i < count($var_results); $i++) {
                    $str_opt .= "<option value=\"" . $var_results[$i]["ID"] . "\">" . $var_results[$i]["DESCRIPTION"] . "</option>";
                }

                return array(
                    "PTT: <select id=\"ptt\" name=\"" . $str_pttLabel . "\" onchange=\"javascript: getOPTList(this,'zone','build')\">" . $str_opt . "</select>",
                    "Zone: <select id=\"zone\" name=\"" . $str_zoneLabel . "\" onchange=\"javascript: getOPTList(this,'build','')\"><option value=''>All</option></select>",
                    "Building: <select id=\"build\" name=\"" . $str_buildingLabel . "\"><option value=''>All</option></select>"
                );
                
            }
        } catch (errors $e) {
            $this->runScript('alert', array(htmlspecialchars($e->message())));
        }
    }

    public static function getAreaNm($str_areaType, $int_parentID) {
        try {
            if ($str_areaType == "zone") {
                $str_sqlID = "ZoneByParentID";
            } else {
                $str_sqlID = "BuildingByParentID";
            }
            $var_results = db::callSQL('SQL_harezad', $str_sqlID, array($int_parentID));
            if (!is_array($var_results)) {
                throw new errors($var_results);
            } else {
                $str_data = "";
                for ($i = 0; $i < count($var_results); $i++) {
                    $str_data .= $var_results[$i]["ID"] . "#" . $var_results[$i]["DESCRIPTION"] . (($i < (count($var_results) - 1)) ? "~" : "");
                }
                return ($str_data != "") ? "ok|" . $str_data : "No record found";
            }
        } catch (errors $e) {
            return $e->message();
        }
    }

    public static function js_PTTZoneBuild($str_targetClassName) {
        return "
				function getOPTList(obj,str_optBoxNm,str_nextBoxToClear) {
					try {
						var obj_selectBox;
						var optGroup;
						var ary_optBox = [str_optBoxNm,str_nextBoxToClear];
						for(x in ary_optBox) {
							if(ary_optBox[x] != '') {
								obj_selectBox = document.getElementById(ary_optBox[x]);
								for(i=obj_selectBox.length-1;i>=0;i--) {
									obj_selectBox.removeChild(obj_selectBox[i]);
								}
								optGroup = document.createElement('option');
								optGroup.innerHTML = 'All';
								optGroup.value = '';
								obj_selectBox.appendChild(optGroup);
							}
						}
						if(obj.value != '') {
							obj_selectBox = document.getElementById(str_optBoxNm);
							actBtnEvt.ajaxSend('" . $str_targetClassName . "','optbox='+ str_optBoxNm +'&val='+ obj.value ,function(){
								if(actBtnEvt.xmlHttpComObj.readyState == 4) {
									var i;
									var ary_output = actBtnEvt.xmlHttpComObj.responseText.split('|');
									if(ary_output[0] == 'ok') {
										var ary_data = ary_output[1].split('~');
										
										for(i=0;i<ary_data.length;i++) {
											ary_optList = ary_data[i].split('#');
											optGroup = document.createElement('option');
											optGroup.innerHTML = ary_optList[1];
											optGroup.value = ary_optList[0];
											obj_selectBox.appendChild(optGroup);
										}
									}
									else {
										alert(actBtnEvt.xmlHttpComObj.responseText);
									}
								}
							});
						}
					}
					catch(e) {
						alert(e.message);
					}
				}
			";
    }

    /// END ///
    /// BEGIN: Date Input Box
    public static function dateBox($str_boxID, $str_defaultValue = "") {
        return "<input type=\"text\" id=\"" . $str_boxID . "\" name=\"" . $str_boxID . "\" class=\"datepicker\" value=\"" . $str_defaultValue . "\" readonly=\"readonly\" required=\"required\" />";
    }

    public static function js_dateBox() {
        return "
				var dateToday = new Date();
				$(function() {
					$('.datepicker').datepicker({
						dateFormat: 'dd-mm-yy',
						firstDay: 1,
						maxDate: '+1Y',
						defaultDate: dateToday
					});
				});
			";
    }

    /// END ///
}

?>