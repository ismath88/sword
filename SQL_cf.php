<?php

/*
 * queries for customer feedback
 * 
 * @ author Ismath khan
 */

class SQL_cf {

    public static function sql($str_sqlID) {
        $ary_sql = array(
            "VIEWTBLCODE" => "select * from swd_system_code",
            "CTWOLIST" => "SELECT
F_GET_SWD_NETWORKTYPE(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS
NETWORK_TYPE,
F_GET_SWD_ORDERID(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS WORK_ORDER_ID,
F_GET_SWD_PRIORITY(SWD_CF_RATING_ID) AS PRIORITY,
F_GET_SWD_RATING(SWD_CF_RATING_ID) AS RATING,
F_GET_SWD_SOURCE(SWD_CF_RATING_ID) AS SOURCE_SYSTEM,
F_GET_SWD_DOC_SOURCE(SWD_CF_RATING_ID) AS SWIFT_SOURCE_SYSTEM,
SWD_CF_RATING_ID,
F_GET_SWD_DOCNUM(SWD_CF_RATING_ID) AS DOCKET_NUMBER,
F_GET_SWD_DOCID(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS DOC_ID,
F_GET_STAFF_SWD(F_GET_SWD_TEAM(SWD_CF_RATING_ID)) AS TEAM_NAME,
ID AS CTWO_ID,CLOSED_DATE, TO_CHAR(CREATED_DATE,'DD-MON-YYYY HH:MI AM') AS CREATED_DATE, RESOLUTION_CODE, OWNER_WORKGROUP,
ROOT_CAUSE_CAT, ATTEND_BY, CTWO_STATUS, CUSTOMER_NAME,ROUND( TO_NUMBER(CAST(SYSTIMESTAMP AS DATE) -CAST(CREATED_DATE AS DATE)) * 24) as AGING,
CUSTOMER_CONTACT_NO, SKILL_SET_NEED, SUPERVISOR_STAFF_NO,
SERVICE_NUMBER, BUILDING FROM SWD_CF_CTWO",
            "CTWOLIST_SUPVSR" => "SELECT
F_GET_SWD_NETWORKTYPE(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS
NETWORK_TYPE,
F_GET_SWD_ORDERID(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS WORK_ORDER_ID,
F_GET_SWD_PRIORITY(SWD_CF_RATING_ID) AS PRIORITY,
F_GET_SWD_RATING(SWD_CF_RATING_ID) AS RATING,
F_GET_SWD_SOURCE(SWD_CF_RATING_ID) AS SOURCE_SYSTEM,
F_GET_SWD_DOC_SOURCE(SWD_CF_RATING_ID) AS SWIFT_SOURCE_SYSTEM,
SWD_CF_RATING_ID,
F_GET_SWD_DOCNUM(SWD_CF_RATING_ID) AS DOCKET_NUMBER,
F_GET_SWD_DOCID(F_GET_SWD_DOCNUM(SWD_CF_RATING_ID)) AS DOC_ID,
F_GET_STAFF_SWD(F_GET_SWD_TEAM(SWD_CF_RATING_ID)) AS TEAM_NAME,
ID AS CTWO_ID,CLOSED_DATE, TO_CHAR(CREATED_DATE,'DD-MON-YYYY HH:MI AM') AS CREATED_DATE, RESOLUTION_CODE, OWNER_WORKGROUP,
ROOT_CAUSE_CAT, ATTEND_BY, CTWO_STATUS, CUSTOMER_NAME,ROUND( TO_NUMBER(CAST(SYSTIMESTAMP AS DATE) -CAST(CREATED_DATE AS DATE)) * 24) as AGING,
CUSTOMER_CONTACT_NO, SKILL_SET_NEED, SUPERVISOR_STAFF_NO,
SERVICE_NUMBER, BUILDING FROM SWD_CF_CTWO  WHERE supervisor_staff_no=<PARAM1>",
            "DOCKET" => "select id,status from docket where doc_number =<PARAM1>",
            "CHECK_SUPVSR_NO" => "select supervisor_staff_no  from swd_cf_ctwo where supervisor_staff_no =<PARAM1>",
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
            "CF_SMS_MSG" => "select to_char(date_sms,'DD-MON-YYYY HH:MI AM') as sms_date, message,
(case when msg_code='Invalid SMS' then 'Invalid Response' else 'Valid Response' end) as msg_code 
from  swd_cf_sms where docket_number=<PARAM1> AND (SMS_STATUS IN(7,2) OR (SMS_STATUS=9 AND MSG_CODE='Invalid SMS')) order by to_char(date_sms,'DD-MON-YYYY HH24:MI:SS AM') desc",
            "HISTORY" => "select t1.docket_number, t1.rating as rating, to_char(t2.created_date,'DD-MON-YYYY HH:MI:SS AM') as created_date,to_char(t2.closed_date,'DD-MON-YYYY HH:MI:SS AM') as closed_date,t2.resolution_code,t2.owner_workgroup,t2.root_cause_cat,t1.team as attend_by from
(select service_number, rating_id, docket_number, rating, team from swd_cf_rating where service_number=<PARAM1> and rating<7)t1,
(select service_number, swd_cf_rating_id, closed_date, created_date, resolution_code,owner_workgroup,root_cause_cat, attend_by from swd_cf_ctwo where service_number=<PARAM1>) t2
where t1.rating_id = t2.swd_cf_rating_id and t1.team = t2.attend_by and t1.service_number=t2.service_number
order by created_date desc",
            "AUDITTRAIL" => "select F_GET_SWD_DOCNUM(SWD_CF_RATING_ID) AS DOCKET_NUMBER,F_GET_STAFF_SWD(F_GET_SWD_TEAM(SWD_CF_RATING_ID)) as staffno,
TO_CHAR(DATE_INSERT,'DD-MON-YYYY HH:MI AM')AS DATE_INSERT, CTWO_STATUS
from SWD_CF_CTWO_HISTORY",
            "AUDITTRAIL_SUPVSR" => "select F_GET_SWD_DOCNUM(SWD_CF_RATING_ID) AS DOCKET_NUMBER,
TO_CHAR(CREATED_DATE,'DD-MON-YYY HH:MI AM') AS CREATED_DATE,
SUPERVISOR_STAFF_NO AS TEAM_NAME, TO_CHAR(CLOSED_DATE,'DD-MON-YYY HH:MI AM') AS CLOSED_DATE,TO_CHAR(DATE_INSERT,'DD-MON-YYYY HH:MI AM')AS DATE_INSERT, CTWO_STATUS, CUSTOMER_NAME
from SWD_CF_CTWO_HISTORY WHERE SUPERVISOR_STAFF_NO=<PARAM1>",
            "PERFORMANCE" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0'),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0'))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7'))),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')) as highest
from swd_cf_rating tab1
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            "PERFORMANCE_BYMONTHYEAR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM1>),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM1>))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>) as highest
from swd_cf_rating tab1 where to_char(insert_date,'mm/yyyy') = <PARAM1>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            "PERFORMANCE_BYYEAR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM1>),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM1>))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>) as highest
from swd_cf_rating tab1 where to_char(insert_date,'yyyy') = <PARAM1>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            "PERFORMANCE_BYWEEK" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy'))),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as highest
from swd_cf_rating tab1 where insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            "PERFORMANCE_SUPVSR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=<PARAM1>),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=<PARAM1>))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM1>) as highest
from swd_cf_rating tab1 where supervisor_staff_no=<PARAM1>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            
            "PERFORMANCE_SUPVSR_BYMONTHYEAR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as highest
from swd_cf_rating tab1 where to_char(insert_date,'mm/yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            "PERFORMANCE_SUPVSR_BYYEAR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>) as highest
from swd_cf_rating tab1 where to_char(insert_date,'yyyy') = <PARAM1>  and supervisor_staff_no=<PARAM2>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            
            "PERFORMANCE_SUPVSR_BYWEEK" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=<PARAM3> and  insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy'))),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')) as highest
from swd_cf_rating tab1 where supervisor_staff_no=<PARAM3> and insert_date between to_date(<PARAM1>,'dd/mm/yyyy') and to_date(<PARAM2>,'dd/mm/yyyy')
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID))",
            
            
            "PERFORMANCE_INDVL_STAFF" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no ) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no = tab2.supervisor_staff_no ),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no ) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no = tab2.supervisor_staff_no ))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no )),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7')  and supervisor_staff_no = tab2.supervisor_staff_no) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no ))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no ) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no = tab2.supervisor_staff_no ) as highest from swd_cf_rating tab1,resources tab2
where tab2.staff_no=<PARAM1> AND tab1.team = tab2.ic_no AND tab1.supervisor_staff_no = tab2.supervisor_staff_no
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)),tab2.supervisor_staff_no",
            
            "PERFORMANCE_INDVL_BYYEAR" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as highest
from swd_cf_rating tab1,resources tab2
where tab2.staff_no=<PARAM1> AND tab1.team = tab2.ic_no AND tab1.supervisor_staff_no = tab2.supervisor_staff_no AND to_char(insert_date,'yyyy') = <PARAM2>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)),tab2.supervisor_staff_no",
            
            "PERFORMANCE_INDVL_BYMONTH" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no)),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and to_char(insert_date,'mm/yyyy') = <PARAM2>  and supervisor_staff_no=tab2.supervisor_staff_no) as highest
from swd_cf_rating tab1,resources tab2
where tab2.staff_no=<PARAM1> AND tab1.team = tab2.ic_no AND tab1.supervisor_staff_no = tab2.supervisor_staff_no AND to_char(tab1.insert_date,'mm/yyyy')= <PARAM2>
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)),tab2.supervisor_staff_no",
            
            "PERFORMANCE_INDVL_BYWEEK" => "select F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)) as teamname, count(tab1.team) as workorderno,
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')) as feedback,
 TRUNC((DECODE((select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')),0,NULL,((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and cf_status <> '0' and supervisor_staff_no=tab2.supervisor_staff_no and  insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')))))* 100,2)as fbpercent,
TRUNC(DECODE(((select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy'))),0,NULL,((select sum(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')) /
(select count(*) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')))),2) as avg,
(select min(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')) as lowest,
(select max(rating) from swd_cf_rating where team=tab1.team and (cf_status='2' or cf_status='7') and supervisor_staff_no=tab2.supervisor_staff_no and insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')) as highest
from swd_cf_rating tab1,resources tab2
where tab2.staff_no=<PARAM1> AND tab1.team = tab2.ic_no AND tab1.supervisor_staff_no = tab2.supervisor_staff_no AND insert_date between to_date(<PARAM2>,'dd/mm/yyyy') and to_date(<PARAM3>,'dd/mm/yyyy')
group by tab1.team,F_GET_STAFF_SWD(F_GET_SWD_TEAM(RATING_ID)),tab2.supervisor_staff_no",
            
            
            
            
            "SMSTEXT" => "select to_char(date_create,'DD-MON-YYYY HH:MI AM') as date_create,userid,message,sms_code from swd_sms_text",
            "LASTSMS_CODE" => "select sms_code from swd_sms_text where rowid =(select max(rowid)from swd_sms_text)",
            "CHECKFLAGSMS" => "select * from swd_sms_text where flag_sms = <PARAM1>",
            
            "MOBILE_STAFF_NAME"=>"select staff_no,name from resources where staff_no=<PARAM1>"
        );
        return $ary_sql[$str_sqlID];
    }

}
