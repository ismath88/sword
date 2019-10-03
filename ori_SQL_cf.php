<?php

/*
 * queries for customer feedback
 * 
 * @ author Ismath khan
 */

class ori_SQL_cf {

    public static function sql($str_sqlID) {
        $ary_sql = array(
            "VIEWTBLCODE" => "select * from swd_system_code",
            "CTWOLIST" => " select tab1.id as ctwoid,to_char(tab1.created_date,'DD-MON-YYYY HH:MI AM') as createddate,tab2.docket_number as ttno,tab2.building as exch,tab2.source_system as source,tab1.service_number as serviceno,
tab7.name as teamname,tab1.customer_name as custname, ROUND( TO_NUMBER(CAST(SYSTIMESTAMP AS DATE) -CAST(tab1.created_date AS DATE)) * 24) as aging,
tab2.priority,tab2.cf_status as status,tab2.rating,tab2.swift_source_system,tab2.docket_number,tab1.swd_cf_rating_id as cf_ratingid,tab3.id as did,tab4.id as wo_id,tab3.status as doc_status,tab6.d_access_type from swd_cf_ctwo tab1
inner join swd_cf_rating tab2 on tab1.swd_cf_rating_id = tab2.id inner join docket tab3 on tab3.doc_number = tab2.docket_number inner join work_order tab4 on tab4.docket_id = tab3.id
inner join service_profile tab5 on tab3.customer_id = tab5.customer_id inner join network_profile tab6 on tab6.service_id = tab5.id inner join team tab7 on tab2.team = tab7.id where tab2.rating between 1 and 2",
            "CTWOLIST_SUPVSR" => " select tab1.id as ctwoid,to_char(tab1.created_date,'DD-MON-YYYY HH:MI AM') as createddate,tab2.docket_number as ttno,tab2.building as exch,tab2.source_system as source,tab1.service_number as serviceno,
tab7.name as teamname,tab1.customer_name as custname, ROUND( TO_NUMBER(CAST(SYSTIMESTAMP AS DATE) -CAST(tab1.created_date AS DATE)) * 24) as aging,
tab2.priority,tab2.cf_status as status,tab2.rating,tab2.swift_source_system,tab2.docket_number,tab1.swd_cf_rating_id as cf_ratingid,tab3.id as did,tab4.id as wo_id,tab3.status as doc_status,tab6.d_access_type from swd_cf_ctwo tab1
inner join swd_cf_rating tab2 on tab1.swd_cf_rating_id = tab2.id inner join docket tab3 on tab3.doc_number = tab2.docket_number inner join work_order tab4 on tab4.docket_id = tab3.id
inner join service_profile tab5 on tab3.customer_id = tab5.customer_id inner join network_profile tab6 on tab6.service_id = tab5.id inner join team tab7 on tab2.team = tab7.id where tab2.rating between 1 and 2 and tab2.supervisor_staff_no=<PARAM1>",
            "CHECK_SUPVSR_NO" => "select supervisor_staff_no  from swd_cf_rating where supervisor_staff_no =<PARAM1>",
            "REMARKS" => "select remarks from swd_cf_ctwo_remarks where ctwo_id =<PARAM1>",
            "OWNERWG" => "select * from swd_system_code where name='owner workgroup'",
            "ROOTCC" => "select * from swd_system_code where name='root cause category'",
            "RESCODE" => "select * from swd_system_code where name='resolution code'",
            "CTWOUPDATE" => "select * from swd_cf_ctwo where id='<PARAM1>'",
            "CUSTDETAIL" => "select tab1.name as custname,tab1.contact_name,tab1.contact_no as contactno,tab1.contact_mobile_no as mobileno,tab1.address as address,tab1.segment_group as segmgroup,tab1.category as category,tab1.login_id as loginid,tab2.product,tab2.service_affecting as serviceaff,
tab1.service_no as serviceno
from customer_profile tab1 inner join service_profile tab2 on tab1.id = tab2.customer_id
 where tab1.service_no =<PARAM1>",
            "FL_CUSTDETAIL" => "select tab1.cust_name as custname,tab1.cust_contact_name as contact_name ,tab1.cust_contact_num as contactno,tab1.cust_contact_num_mobile as mobileno,(tab1.cust_street_type ||  ' ' || tab1.cust_street_address || ' ' || tab1.cust_street_address || ' ' ||tab1.cust_postal_code || ' ' ||tab1.cust_city || ' ' ||tab1.cust_state || ' ' ||tab1.cust_country) as address,tab1.cust_segment_group as segmgroup,tab2.fsi_product as product,
tab2.fsi_svc_number as serviceno from fl_customer_info tab1 inner join fl_service_info tab2 on tab1.cust_order_number = tab2.fsi_order_number
 where tab2.fsi_svc_number =<PARAM1>",
            "TICKETINFO" => "select doc_number as ttno,ctt_id as cttid,activity_type as acttype,diagnosis,created_date,status as curjobstatus,priority,
repeat_rpt_counter as rptcounter,reschedule_datetime as resdle_date,reschedule_count as reapt_count,appointment_time as apt_time,activity_id,activity_planned_start as act_plnstart,activity_planned_end as act_plnend from docket
where doc_number =<PARAM1>",
            "NETWORKINFO" => "select cabinet_id,cabinet_type,dp_pair,dslam_id,dslam_type,port_id,vci_id,adsl_pair_no,adsl_in_out,len,bar_pair,hbar_pair,card_type,nis_service_id,nis_status,d_equip_name,d_speed,d_access_type from network_profile
where id='18'",
            "HISTORY" => "select tab1.id as ctwoid,to_char(tab1.created_date,'DD-MON-YYYY HH:MI AM') as datecreated,to_char(tab1.closed_date,'DD-MON-YYYY HH:MI AM') as dateclosed,tab1.resolution_code as reslcode,tab1.attend_by as attendby,tab2.rating as csatrating from swd_cf_ctwo tab1 inner join swd_cf_rating tab2 on tab1.swd_cf_rating_id = tab2.id where tab1.id=<PARAM1>",
            "AUDITTRIAL" => "select tab1.docket_number as ttno,tab3.name as teamname,to_char(tab2.created_date,'DD-MON-YYYY HH:MI AM') as datetime,tab2.ctwo_status as prevstatus,tab1.cf_status as newstatus from swd_cf_rating tab1 inner join swd_cf_ctwo tab2 on tab1.id = tab2.swd_cf_rating_id inner join team tab3 on tab1.team = tab3.id",
            "PERFORMANCE" => "select tab2.name as teamname,count(tab2.name) as workorderno,count(tab1.rating) as feedback,TRUNC(((count(tab1.rating)/count(tab2.name))) * 100,2) as fbpercent,
 TRUNC(DECODE(count(tab1.rating),0,NULL,count(tab2.name)/count(tab1.rating)),2) as avg,min(tab1.rating)as lowest,max(tab1.rating)as highest from swd_cf_rating tab1,team tab2
where tab1.team = tab2.id
group by tab2.name,tab1.team"
        );
        return $ary_sql[$str_sqlID];
    }

}
