<?php
	
	class AdminHomePage extends gui {
		
		public function __toString() {
			if(isset($_GET['t']) && $_GET['t'] != '') {
				switch($_GET['t']) {
					case 1: return $this->loadSystemMenu(); break;
					default: return "Under construction!";
				}
			}
			else {
				return parent::__toString();
			}
		}
		
		protected function content() {
			
			$this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');
			$this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');
			$this->importPlugin('colorbox/jquery.colorbox.js');
			$this->importPlugin('colorbox/colorbox.css');
			
			return "
				<article>
					<header>
						<h1>System Administration</h1>
					</header>
					
					<section>

						<div id=\"tabs-min\" >
							<ul>
								<li><a href=\"#tabs-1\">Home</a></li>
								<li><a href=\"?clsid=". __CLASS__ ."&t=1\">Sytem Menu</a></li>
								<li><a href=\"?clsid=". __CLASS__ ."&t=2\">User Group</a></li>
							</ul>
							
							<div id=\"tabs-1\">
								<h2>Welcome</h2>
								<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
							</div>
							
						</div>
						
					</section>
				</article>
			";
		}
		
		private function loadSystemMenu($int_menuCat=1) {
			try {
				$var_results = DataLoader::getData("MenuList",array($int_menuCat));
				if(is_array($var_results)) {
					$ary_section = array();
					for($i=0;$i<count($var_results);$i++) {
						if($var_results[$i]['PARENTID'] > 0) {
							$ary_section[$var_results[$i]['PARENTID']][$var_results[$i]['MENU_LIST_ID']] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
						else {
							$ary_section[$var_results[$i]['MENU_LIST_ID']][0] = array($var_results[$i]['DESCRIPTION'],$var_results[$i]['LINK']);
						}
					}
					
					$str_section = "";
					$i=1;
					
					foreach($ary_section as $index=>$data) {
						
						$str_submenu = "";
						$j=1;
						
						foreach($data as $id=>$val) {
							if($id != 0) {
								$str_submenu .= "
									<tr>
										<td>". $j .".</td>
										<td class=\"noWrapText\">". ucfirst($val[0]) ."</td>
										<td>". $val[1] ."</td>
										<td><a class=\"iframe\" onclick=\"javascript:callColorBox()\"><img src=\"images/Edit_16x16.png\" alt=\"\" title=\"Edit\" /></a></td>
									</tr>
								";
								$j++;
							}
						}
						
						$str_section .= "
							<tr>
								<td rowspan=\"". count($data) ."\">". $i .".</td>
								<td colspan=\"3\" class=\"greybg\">". strtoupper($data[0][0]) ."</td>
								<td class=\"greybg\"><a class=\"iframe\" onclick=\"javascript:callColorBox()\"><img src=\"images/Edit_16x16.png\" alt=\"\" title=\"Edit\" /></a></td>
							</tr>
							". $str_submenu ."
						";
						$i++;
					}
					
					$str_output = "
						<p>
							To customize system menu, please click on edit button. To create a new menu section, please click on New Section button.
							<div style=\"text-align:right\"><button type=\"button\">New Section</button></div>
						</p>
						
						<table id=\"tblfrm\">
							<tr>
								<th colspan=\"2\">No.</th>
								<th>Menu Label</th>
								<th>Hyperlink</th>
								<th>Edit</th>
							</tr>
							". $str_section ."
						</table>
					";
					
					return str_replace(array("\n","\r","\t"),"",$str_output);
				}
				else {
					throw new errors($var_results);
				}
			}
			catch(errors $e) {
				$this->runScript('alert',array(htmlspecialchars($e->message())));
			}
		}
		
		protected function js() {
			return "
				$(function() {
					$(\"#tabs-min\").tabs({
						beforeLoad: function( event, ui ) {
							ui.jqXHR.error(function() {
								ui.panel.html(\"Couldn't load this tab. We'll try to fix this as soon as possible.\");
							});
						}
					});
				});
				function callColorBox() {
					$(\".iframe\").colorbox({iframe:true, width:\"50%\", height:\"50%\"});
				}
			";
		}
		
		protected function css() {
			return "
				#tabs-min { 
					background: transparent; 
					border: none;
				} 
				#tabs-min .ui-widget-header { 
					background: transparent; 
					border: none; 
					border-bottom: 1px solid #c0c0c0; 
					-moz-border-radius: 0px; 
					-webkit-border-radius: 0px; 
					border-radius: 0px; 
				} 
				#tabs-min .ui-tabs-nav .ui-state-default { 
					background: transparent; 
					border: none; 
				} 
				#tabs-min .ui-tabs-nav .ui-state-active { 
					background: transparent url(images/uiTabsArrow.png) no-repeat bottom center; 
					border: none; 
				} 
				#tabs-min .ui-tabs-nav .ui-state-default a { 
					color: #c0c0c0; 
				} 
				#tabs-min .ui-tabs-nav .ui-state-active a { 
					color: #459e00; 
				}
				
				#tblfrm td {
					padding:5px;
					vertical-align:top;
					border:solid 1px #c0c0c0;
					text-align:left;
					background-color:#ffffcc;
				}
				#tblfrm td.greybg {
					background-color:#336699;
					color:#ffffff;
				}
				.noWrapText {
					white-space:nowrap;
				}
			";
		}
	}
?>