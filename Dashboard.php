<?php

class Dashboard extends gui {

    ///// Configuration Area ////////////////////////////////////////////////////////////////////////////////////////

    protected $headerTitle = "Header Title"; // Page Title
    protected $subTitle = "Sub Title"; // Page Title
    protected $subTitle1 = "Sub Title1"; // Page Title
    protected $subTitle2 = "Sub Title2"; // Page Title
    protected $subTitle3 = "Sub Title3"; // Page Title
    protected $sys_className = "IPAdminHomePage"; // Page Title
    protected $datagridHeight = "300px"; // Height of data grid. Change if you feel it too small.
    private $sqlPDQ = "";

    // Pre-defined query
    protected function pdq() {
        return array();
    }

    // Additional space
    protected function buttonJob() {
        return array();
    }

    // Dashboard status display
    protected function realtimeStatus() {
        return array();
    }

    // Table data
    protected function gridData() {
        return array();
    }

    // Additional space
    protected function buttonJob1() {
        return false;
    }

    // Additional space
    protected function footer() {
        return false;
    }

    // Javascript
    protected function userdefined_js() {
        return false;
    }

    // CSS
    protected function userdefined_css() {
        return false;
    }

    ///// End of configuration area/////////////////////////////////////////////////////////////////////////////////////////

    final protected function callSQL($str_className, $str_indexLabel) {
        try {

            if (isset($_POST["pdqsubmit"]) && $_POST["pdqsubmit"] != "") {

                $ary_postData = $_POST;
                //$ary_postData = unset($ary_postData["pdqsubmit"]);

                $ary_colData = $this->pdq();
                $str_sql = call_user_func_array(array($str_className, "sql"), array($str_indexLabel));

                $str_newSql = "SELECT * FROM (" . $str_sql . ") X WHERE ";

                foreach ($ary_postData as $fieldname => $val) {
                    $ary_excapeChar = array("pdqsubmit");
                    if (!in_array($fieldname, $ary_excapeChar)) {
                        if ($val != '') {
                            $str_newSql .= "X." . $fieldname . "='" . $val . "' AND ";
                        }
                    }
                }

                $this->sqlPDQ = substr($str_newSql, 0, -4);

                //echo "ori: ". $str_sql ."<br>";
                //echo "new: ". $this->sqlPDQ;
                //print_r($_POST);
            }

            return db::callSQL($str_className, $str_indexLabel);
        } catch (errors $e) {
            return $e->message();
        }
    }

    final protected function content() {

        $this->importPlugin("DataTables-1.9.4/media/js/jquery.dataTables.min.js");
        $this->importPlugin("DataTables-1.9.4/media/css/jquery.dataTables.css");
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');

        return "
				<article>
				<div>Welcome | Abdul Aziz Bin Aznan | BPQM | 13/12/2013</div>
					<header>
						<h1>" . $this->headerTitle . "</h1>
					</header>

					<section>
						" . $this->showPDQPanel() . "
						" . $this->showStatusMonitorPanel() . "
						" . $this->showButtonJob() . "
						<div id=\"buttonJob1\">" . $this->buttonJob1() . "</div>
						<!--header><h1>" . $this->subTitle . "</h1></header>
						" . $this->makeDataGrid() . "
						<header><h1>" . $this->subTitle1 . "</h1></header>
						<header><h1>" . $this->subTitle2 . "</h1></header>
						<header><h1>" . $this->subTitle3 . "</h1></header-->
						<div id=\"footer\">" . $this->footer() . "</div>
					</section>
					
				</article>
			";
    }

    private function showPDQPanel() {

        $ary_colData = $this->pdq();

        if (is_array($ary_colData) && count($ary_colData) > 0) {
            $str_tbl = "";
            $i = 1;

            foreach ($ary_colData as $colData) {
                $str_tbl .= "<tr><td>" . (is_array($colData) ? $colData[0] : $colData) . "</td></tr>";
                if (($i % 3) == 0) {
                    $str_tbl .= "</tr><tr>";
                    $i = 1;
                } else {
                    $i++;
                }
            }

            /* foreach($ary_colData as $colData) {
              $str_tbl .= "<td>". $colData ."</td>";
              if(($i%3)==0) {
              $str_tbl .= "</tr><tr>";
              $i=1;
              }
              else {
              $i++;
              }
              } */

            for ($i; $i < 4; $i++) {
                $str_tbl .= "<td>&nbsp;</td>";
            }

            return "
					<div id=\"pdq\">
						<form method=\"post\" action=\"?clsid=" . $this->sys_className . "\" type=\"application/x-www-form-urlencoded\">
						<table id=\"pdqFrame\"  >
							<tr>
								<td class=\"lf\"><table id=\"pdqctn\" cellpadding=\"10\" style=\"width:1000px\"><tr>" . $str_tbl . "</tr></table></td>
								<!--td class=\"rf\" rowspan=\"2\">Colour Code</td-->
							</tr>
							<tr>
								<td style=\"padding:3px;text-align:center;\"><!--input name=\"pdqsubmit\" type=\"submit\" value=\"Submit\" /--></td>
							</tr>
						</table>
						</form>
                                                <div style='text-align:center'><a  href='http://localhost/SWORD/?clsid=IPJobCC'><button>Submit</button></a></div>
					</div>&nbsp;
				";
        }
    }

    private function showStatusMonitorPanel() {
        $ary_stat = $this->realtimeStatus();
        if (is_array($ary_stat) && count($ary_stat) > 0) {
            $str_tbl = "";
            $i = 1;

            foreach ($ary_stat as $db => $nm) {
                $str_tbl .= "<td>" . $nm . ": " . substr(rand(), 0, 2) . "</td>";
                if (($i % 5) == 0) {
                    $str_tbl .= "</tr><tr>";
                    $i = 1;
                } else {
                    $i++;
                }
            }

            for ($i; $i < 6; $i++) {
                $str_tbl .= "<td>&nbsp;</td>";
            }

            return "<fieldset id=\"realtimestat\"><legend>Status Updates</legend><table><tr>" . $str_tbl . "</tr></table></fieldset>&nbsp;";
        }
    }

    private function showButtonJob() {
        $ary_stat = $this->buttonJob();
        if (is_array($ary_stat) && count($ary_stat) > 0) {
            $str_tbl = "";
            $i = 1;

            foreach ($ary_stat as $db => $nm) {
                $str_tbl .= "<td>" . $nm . ": " . substr(rand(), 0, 2) . "</td>";
                if (($i % 5) == 0) {
                    $str_tbl .= "</tr><tr>";
                    $i = 1;
                } else {
                    $i++;
                }
            }

            for ($i; $i < 6; $i++) {
                $str_tbl .= "<td>&nbsp;</td>";
            }

            return "<fieldset id=\"realtimestat\"><legend></legend><table><tr>" . $str_tbl . "</tr></table></fieldset>&nbsp;";
        }
    }

    private function makeDataGrid() {
        $ary_gridData = $this->gridData();

        if (isset($_POST["pdqsubmit"]) && $_POST["pdqsubmit"] != "") {

            try {
                $var_results = db::query('', array($this->sqlPDQ), $str_errMsg);
                if ($str_errMsg) {
                    throw new errors($str_errMsg);
                } else {
                    $var_results["Columns"] = $ary_gridData["Columns"];
                    $ary_gridData = $var_results;
                }
            } catch (errors $e) {
                return $e->message();
            }
        }

        if (is_array($ary_gridData) && count($ary_gridData) > 0) {
            $str_tbl = "";
            $ary_columnDbName = array();
            $str_showHideCol = "";
            $str_tbl .= "<table id=\"datagrid\">";
            $str_tbl .= "<thead><tr><th>No.</th>";

            $j = 1;

            foreach ($ary_gridData["Columns"] as $dbcol => $colNm) {
                $str_tbl .= "<th>" . $colNm . "</th>";
                array_push($ary_columnDbName, $dbcol);
                $str_showHideCol .= "<div><input type=\"checkbox\" id=\"c" . $j . "\"/ onclick=\"javascript:fnShowHide(" . $j . ")\"> " . $colNm . "</div>";
                $j++;
            }

            $str_tbl .= "</tr></thead><tbody>";

            for ($i = 0; $i < count($ary_gridData) - 1; $i++) {
                $str_colVal = "";
                foreach ($ary_columnDbName as $colID) {
                    $str_colVal .= "<td>" . $ary_gridData[$i][$colID] . "</td>";
                }
                $str_tbl .= "<tr><td>" . ($i + 1) . ".</td>" . $str_colVal . "</tr>";
            }

            $str_tbl .= "
					</tbody></table>
					&nbsp;&nbsp;&nbsp;<a class=\"showHideBtn\" onclick=\"javascript:showColumnCtrlPage(" . count($ary_columnDbName) . ")\">Show/Hide Column</a>
					<div id=\"dialog-message\" title=\"Show/Hide Column\">" . $str_showHideCol . "</div>
				";

            return "<div id=\"datagridFrame\">" . $str_tbl . "</div>";
        }
    }

    final protected function js() {
        return "
				$(document).ready(function() {
					$('#datagrid').dataTable({
						'sScrollY': '" . $this->datagridHeight . "',
						'bPaginate': true,
						'bScrollCollapse': true,
						'oLanguage': {
							'sSearch': 'Filter Text:'
						}
					});
					$('#dialog-message').dialog({
						modal: true,
						autoOpen: false
					});
				});
				function fnShowHide( iCol ) {
					var oTable = $('#datagrid').dataTable();
					var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
					oTable.fnSetColumnVis( iCol, bVis ? false : true );
				}
				function showColumnCtrlPage(int_totalCol) {
					try {
						var oTable = $('#datagrid').dataTable();
						for(var i=1;i<=int_totalCol;i++) {
							document.getElementById('c'+i).checked = (oTable.fnSettings().aoColumns[i].bVisible)? true : false;
						}
						$('#dialog-message').dialog({
							modal: true,
							autoOpen: true,
							buttons: {
								Ok: function() {
									$(this).dialog('close');
								}
							}
						});
					}
					catch(e) {
						alert(e.message);
					}
				}
			" . $this->userdefined_js();
    }

    final protected function css() {
        return "
				#datagridFrame, #realtimestat, #pdq {
					border:solid 1px;
					overflow:auto;
				}
				#realtimestat {
					padding:5px;
					background-color:#f0f0f0;
				}
				#realtimestat td{
					width:200px;
					text-align:right;
					font-size:small;
				}
                                input,textarea,select{
                                width:150px;
                                box-sizing:content-box;
                             -ms-box-sizing:content-box;
                               -moz-box-sizing:content-box;
                               -webkit-box-sizing:content-box; 
                                    }
				#pdq {
					padding:5px;
				}
				#pdqFrame {
					width:100%;
				}
				#pdqFrame td.rf {
					border-left:solid 1px;
					width:20%;
					text-align:right;
					vertical-align:top;
				}
				#pdqFrame td.lf {
					width:80%;
				}
				#pdqctn td{
					width:263px;
					text-align:right;
					font-size:small;
				}
				legend {
					font-weight:bold;
				}
				#footer {
					padding-top:1em;
				}
				.showHideBtn {
					border:solid 1px #a0a0a0;
					background-color:#c0c0c0;
					font-size:10pt;
					padding:3px;
				}
			" . $this->userdefined_css();
    }

}

?>