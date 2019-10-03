<?php
	
	class Dir extends gui {
		
		public function __toString() {
			if(util::fetch('c') != "") {
				show_source(util::fetch('c').'.php');
				return "";
			}
			else {
				return parent::__toString();
			}
		}
		
		protected function content() {
			return "
				<article>
					<section>
						<h2>Hyperlink for Demo page</h2>
						". $this->scanFiles() ."
					</section>
				</article>
			";
		}
		
		private function scanFiles() {
			$str_path = rtrim($_SERVER["DOCUMENT_ROOT"],'/\\') .'/'. ltrim(dirname($_SERVER['PHP_SELF']),'/\\') ."/modules";
			
			if($obj_dir = opendir($str_path)) {
				
				$str_hyperlink = "";
				$ary_sort = array();
				
				while(($obj_item = readdir($obj_dir)) !== false) {
					
					if(is_dir($str_path .'/'. $obj_item) && !in_array($obj_item, array('.','..'))) {
						
						$str_pathX = $str_path ."/". $obj_item;
						
						if($obj_dirX = opendir($str_pathX)) {
						
							while(($obj_itemX = readdir($obj_dirX)) !== false) {
								if(is_file($str_pathX .'/'. $obj_itemX) && !in_array($obj_itemX, array('.','..','Dir.php'))) {
									
									if(!isset($ary_sort[$obj_item])) {
										$ary_sort[$obj_item] = array();
									}
									array_push($ary_sort[$obj_item],$obj_itemX);
								}
							}
						}
					}
				}
				
				ksort($ary_sort);
				
				foreach($ary_sort as $obj_key=>$obj_item) {
					
					$str_link = "";
					
					foreach($obj_item as $item) {
						$str_link .= "
							<p class=\"dtx\">
								<i id=\"pname\">". $item ."</i>
								<a href=\"?clsid=". substr($item,0,-4) ."\" target=\"_blank\"><i>View Page</i></a>
								<a href=\"?clsid=". __CLASS__ ."&clspar=". urlencode("c=".substr($item,0,-4)) ."\" target=\"_blank\"><i>View Source</i></a>
							</p>
						";
					}
					
					$str_hyperlink .= "
						<li>
							<h3>". strtoupper($obj_key) ."</h3>
							". $str_link ."
						</li>
					";
				}
				
				return "<ol>". $str_hyperlink ."</ol>";
			}
		}
		
		protected function css() {
			return "
				.dtx {width:95%; border-bottom:dotted 1px; text-align:right}
				#pname {float:left;}
				.dtx a {padding-left:1em; font-size:small}
				.dtx a i {border-left:dotted 1px; padding:2px;}
				ol {border-top:solid 1px;}
				.dtx:hover {background-color:#ffffcc;}
			";
		}
	}
?>