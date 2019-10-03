<?php

class SampleDB {

    public static function sql($str_sqlID) {
        $ary_sql = array(
            "AllPTT" => "SELECT * FROM AREA WHERE UPPER(TYPE) = 'PTT' AND ROWNUM < 10 ORDER BY DESCRIPTION",
            "ONLYPTT" => "SELECT * FROM AREA WHERE UPPER(TYPE) = 'PTT' AND ROWNUM < 10 ORDER BY DESCRIPTION",
            "ADMIN" => "SELECT * FROM AREA WHERE UPPER(TYPE) = 'PTT' AND ROWNUM < 10 ORDER BY DESCRIPTION"
        );
        return $ary_sql[$str_sqlID];
    }

}

?>