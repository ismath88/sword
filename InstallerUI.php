<?php
	
	class InstallerUI extends Dashboard {



    protected $headerTitle = "WorkGroup Organization Management";



    public function __toString() {

        if (util::fetch('optbox') != '') {

            return DashboardCommonUI::getAreaNm(util::fetch('optbox'), util::fetch('val'));

        } else {

            return parent::__toString();

        }

    }

    

    protected function footer() {

        return "<div style=\"text-align:right\"><a  href='javascript:void(0);' onclick=\"window.open('?clsid=WorkGroupOrganizationMgmtAdd','popup','width=1000,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0')\">WorkGroup Organization Mgmt Add</a></div>";

    }



    protected function gridData() {

        try {



            $var_results = db::callSQL('WorkGroupOrganizationMgmt_sql', 'WorkGroupOrganizationMgmtRecords');

           

            for ($i = 0; $i < count($var_results); $i++) {

                $orgUnitId = $var_results[$i]["ORG_UNIT_ID"];

                $workGroupId = $var_results[$i]["WORKGROUP_ID"];

               

                $var_results[$i]["ACTION"] = "<div id=\"DeviceDelete\" title=\"Confirmation\" style=\"display:none\">Are you sure you want to delete this? </div>

                  

                   

                     <select name='status' onchange = 'javascript:status(this,\"".$orgUnitId."\",\"".$workGroupId."\")'>  

                                          <option value = '' >Select</option>

                                         <option value = '1'>Edit WorkGroup Org Mgmt</option> 

                                         <option value = '2' >Delete WorkGroup Org Mgmt</option>

                                </select>

               ";

            }







            if (!is_array($var_results)) {

                throw new errors($var_results);

            } else {







                $var_results["Columns"] = array(

                    "WORKGROUP_ID" => "Work Group Id",

                    "ORG_UNIT_DESC" => "Org Unit Desc",

                   

                    "ACTION" => "Action",

                );



                return $var_results;

            }

        } catch (errors $e) {

            return $e->message();

        }

    }



    protected function userdefined_js() {

        return

                DashboardCommonUI::js_dateBox() .

                DashboardCommonUI::js_PTTZoneBuild(__CLASS__) .

                "



    

function status(obj,element1,element2) {

        var val =obj.value;

        var pagename = '';        

        if(val=='1'){

            pagename='WorkGroupOrganizationMgmtEdit&orgUnitId='+element1+'&workGroupId='+element2;

			window.open('../fw/?clsid='+pagename,'popup','width=800,height=3500,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=50,top=0')

        }else if(val=='2'){

			 deleteConfirmation(element2);       

        }else{

			alert('Please Select');

		}



    }







 

function deleteConfirmation(workGroupId) {

                                      

					

  $('#DeviceDelete').dialog({

                                    

   

    autoOpen: true,

    modal: true,

    buttons: {

        'Delete': function () {

           

            $.ajax({

             type: 'POST',

             url: '?clsid=WorkGroupOrganizationMgmtResponse&workGroupId='+workGroupId,

              

                success: function (resultMsg) {

                

               alert(resultMsg);

               

                },

                

               complete: function () {

                

               window.location.reload();

                 

                }

            });

        },

        'Cancel': function () {

            $(this).dialog('close');

        }

    }

});

             

                          }

                                



			";

    }



}
?>