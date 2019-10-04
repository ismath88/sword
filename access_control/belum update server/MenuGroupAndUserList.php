<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuGroupAndUserList
 *
 * @author avnish.awasthi
 */
class MenuGroupAndUserList extends gui{
    //put your code here
    public function __toString() {          
                    return parent::__toString();         
    }
    
    protected function content() {
        
        $this->import('datagrid.min.css');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.js');
        $this->importPlugin('jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.min.css');

        
        $str_page_heading = "Menu Group And User Listing";
        
        if(isset($_GET['menulistid']) && !empty($_GET['menulistid'])){
            
            $str_group_list = $this->getUserGroupByMenuListId($_GET['menulistid']);
            $str_menu_users_list = $this->getUserMenuByMenuListId($_GET['menulistid']);
            
        }else{
            $this->runScript('alert',array(htmlspecialchars("Menu Id does not exist.")));
            $str_group_list = "<tr><td colspan=\"3\">Sorry, No Reocrd Found!</td></tr>";
            $str_menu_users_list = "<tr><td colspan=\"4\">Sorry, No Reocrd Found!</td></tr>";
        }
        
       
         
        $str_content = "
           
        <article>
            <header>
                    <h1>".$str_page_heading."</h1>
            </header>

            <section>
               
                <fieldset style=\"width:80%;font-size:14px;margin:20px;border:1px solid #cccccc;padding:10px;\" >    
                        <legend>&nbsp;<b>Menu Group Listing</b>&nbsp;</legend>
                         
                    <table style=\"width:95%;font-size:14px;text-align:center;\" class=\"datagrid\">
                            
                                <tr>
                                    
                                    <th>No</th>
                                    <th>Group Name</th>
                                    <th>No. Of Users</th>
                                                 
                                </tr>
                                ".$str_group_list."
                                
                        </table>    
                    </fieldset> 
                    
                    <fieldset style=\"width:80%;font-size:14px;margin:20px;border:1px solid #cccccc;padding:10px;\" >    
                        <legend>&nbsp;<b>Menu User Listing</b>&nbsp;</legend>
                         
                    <table style=\"width:95%;font-size:14px;text-align:center;\" class=\"datagrid\">
                            
                                <tr>
                                    
                                    <th>No</th>
                                    <th>Staff No.</th>
                                    <th>Name</th>
                                    <th>User Group</th>             
                                </tr>
                                ".$str_menu_users_list."
                                
                        </table>    
                    </fieldset>  
            </section>


        </article>
        
        ";
        
        return $str_content;
    }
    
    public function getUserGroupByMenuListId($menu_list_id){
        
        
        $var_results =  DataLoader4::getData("GetUserGroupByMenuListId",array($menu_list_id));
        $str_rows = "<tr><td colspan=\"3\">Sorry, No Reocrd Found!</td></tr>";
        if(is_array($var_results)) {

            if(count($var_results)>0) {
                    $str_rows = "";
                    for($i=0;$i<count($var_results);$i++) {
                           
                             
                            $rownumber = $i+1;                              
                            $str_rows .= "
                                    
                                    <tr>
                                    <td>".$rownumber."</td>
                                    <td style=\"text-align:left;\" >".$var_results[$i]['DESCRIPTION']."</td>
                                    <td style=\"text-align:left;\" >".$var_results[$i]['TOTAL_USER']."</td>
                                    
                                    </tr>
                                    ";
                    }

                    return $str_rows;
            }
            else {
                    return $str_rows;
            }
        }
        else {
                return $str_rows;
        }
    }
    
    public function getUserMenuByMenuListId($menu_list_id){
        
        
        $var_results =  DataLoader4::getData("GetUserMenuByMenuListId",array($menu_list_id));
        $str_rows = "<tr><td colspan=\"4\">Sorry, No Reocrd Found!</td></tr>";
        if(is_array($var_results)) {

            if(count($var_results)>0) {
                    $str_rows = "";
                    for($i=0;$i<count($var_results);$i++) {
                           
                             
                            $rownumber = $i+1;                           
                            $str_rows .= "
                                    
                                    <tr>
                                    <td>".$rownumber."</td>
                                    <td  style=\"text-align:left;\" >".$var_results[$i]['STAFF_NO']."</td>
                                    <td  style=\"text-align:left;\" >".$var_results[$i]['NAME']."</td>
                                    <td>".$var_results[$i]['DESCRIPTION']."</td>
                                    </tr>
                                    ";
                    }

                    return $str_rows;
            }
            else {
                    return $str_rows;
            }
        }
        else {
                return $str_rows;
        }
    }
}


?>
