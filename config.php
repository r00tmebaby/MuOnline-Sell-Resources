<?php
////////////////////////////////////////////////////
/////|---  Created for DarksTeam Users  ---|////////
///|-- Sell Resources For Credits From Game --|/////
///|---<   https://DarksTeam.net    > ---|//////////
//////////|-- Dev @r00tme 2017 --|//////////////////
////////////////////////////////////////////////////
$option['sql_host']       = "r00tme-pc";          // Sql server host: 127.0.0.1,localhost,Your Computer Name, Instance
$option['sql_user']       = 'sa';                 // Sql server user: sa
$option['sql_pass']       = '12345';              // Sql server password
$option['sql_dbs']        = 'password';           // Mu online database: default = MuOnline

////////////////////////////////////////////////////
$option['php_5.3+']       = 1;                    // PHP version switch, 0 = using mssql_query, 1= using sqlsrv_query (sqlsrv drivers are required for the used php version)
$option['web_session']    = "Drakon";             // Web Session
$option['mu_version']     = 1;                    // 0 - Season 1(97-99), 1 = Season 2+ up to 12
$option['invent_ware']    = 0;                    // 0 - Inventory Only, 1= Warehouse Only     
$option['credits_tbl']    = "memb_credits";       // Credits Table
$option['credits_col']    = "credits";            // Credits Column
$option['credits_usr']    = "memb___id";          // Credits User

////////////////////////////////////////////////////
$option['exhange']        = array(5,12,150);      // Credits per resource, make sure you have exact total numbers as resources for exchange 
$option['res']            = array                 // Resource / Resource Name 
       (  
// Stone Season 1 Code: D508  | Stone Season 2+ Code: 1508, so make sure you are typing a valid hex codes related to your MuOnline Server	   
        "1508" => "stone",                        
		"1500" => "rena", 
        "1A20" => "Box of Treasure"		
		);                                        

////////////////////////////////////////////////////
$option['enc_key']        = "@r00tme";           // Form Fields Encryption / PHP up to 5.3 uses extension mcrypt / PHP up to 7.1.9 uses openssl_random_pseudo_bytes, so make sure they are uncommented in php.ini

//////////////////////////////////////////////////// 
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////////////////////////////////////////////////////

////>>>>> AS YOU MAY BRAKE UP THE CODE <<<</////////

////////////////////////////////////////////////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////////////////////////////////////////////////////
//////////////////////////////////////////////////// 
////////////////////////////////////////////////////

if (!class_exists('mssqlQuery')) { 
    class mssqlQuery 
    { 
        private $data = array(); 
        private $rowsCount = 0; 
        private $fieldsCount = null; 

        public function __construct($resource) 
        { 
            if ($resource) { 
                while ($data = sqlsrv_fetch_array($resource)) { 
                    $this->addData($data); 
                } 

                sqlsrv_free_stmt($resource); 
            } 
        } 

        public function getRowsCount() 
        { 
            return $this->rowsCount; 
        } 

        public function getFieldsCount() 
        { 
            if ($this->fieldsCount === null) { 
                $this->fieldsCount = 0; 
                $data = reset($this->data); 

                if ($data) { 
                    foreach ($data as $key => $value) { 
                        if (is_numeric($key)) { 
                            $this->fieldsCount++; 
                        } 
                    } 
                } 
            } 

            return $this->fieldsCount; 
        } 

        private function addData($data) 
        { 
            $this->rowsCount++; 
            $this->data[] = $data; 
        } 

        public function getData() 
        { 
            return $this->data; 
        } 

        public function shiftData($resultType = SQLSRV_FETCH_BOTH) 
        { 
            $data = array_shift($this->data); 

            if (!$data) { 
                return false; 
            } 

            if ($resultType == SQLSRV_FETCH_NUMERIC) { 
                foreach ($data as $key => $value) { 
                    if (!is_numeric($key)) { 
                        unset($data[$key]); 
                    } 
                } 
            } else { 
                if ($resultType == SQLSRV_FETCH_ASSOC) { 
                    foreach ($data as $key => $value) { 
                        if (is_numeric($key)) { 
                            unset($data[$key]); 
                        } 
                    } 
                } 
            } 

            return $data; 
        } 
    } 
} 


if (!function_exists('mssql_connect')) { 
    function mssql_connect($servername, $username, $password, $newLink = false) 
    { 
        if (empty($GLOBALS['_sqlsrvConnection'])) { 
            $connectionInfo = array( 
                "CharacterSet" => "UTF-8", 
                "UID" => $username, 
                "PWD" => $password, 
                "ReturnDatesAsStrings" => true 
            ); 

            $GLOBALS['_sqlsrvConnection'] = sqlsrv_connect($servername, $connectionInfo); 

            if ($GLOBALS['_sqlsrvConnection'] === false) { 
                foreach (sqlsrv_errors() as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $GLOBALS['_sqlsrvConnection']; 
    } 
} 

if (!function_exists('mssql_pconnect')) { 
    function mssql_pconnect($servername, $username, $password, $newLink = false) 
    { 
        if (empty($GLOBALS['_sqlsrvConnection'])) { 
            $connectionInfo = array( 
                "CharacterSet" => "UTF-8", 
                "UID" => $username, 
                "PWD" => $password, 
                "ReturnDatesAsStrings" => true 
            ); 

            $GLOBALS['_sqlsrvConnection'] = sqlsrv_connect($servername, $connectionInfo); 

            if ($GLOBALS['_sqlsrvConnection'] === false) { 
                foreach (sqlsrv_errors() as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $GLOBALS['_sqlsrvConnection']; 
    } 
} 

if (!function_exists('mssql_close')) { 
    function mssql_close($linkIdentifier = null) 
    { 
        sqlsrv_close($GLOBALS['_sqlsrvConnection']); 
        $GLOBALS['_sqlsrvConnection'] = null; 
    } 
} 

if (!function_exists('mssql_select_db')) { 
    function mssql_select_db($databaseName, $linkIdentifier = null) 
    { 
        $query = "USE " . $databaseName; 

        $resource = sqlsrv_query($GLOBALS['_sqlsrvConnection'], $query); 

        if ($resource === false) { 
            if (($errors = sqlsrv_errors()) != null) { 
                foreach ($errors as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $resource; 
    } 
} 

if (!function_exists('mssql_query')) { 
    function mssql_query($query, $linkIdentifier = null, $batchSize = 0) 
    { 
        if (preg_match('/^\s*exec/i', $query)) { 
            $query = 'SET NOCOUNT ON;' . $query; 
        } 

        $resource = sqlsrv_query($GLOBALS['_sqlsrvConnection'], $query); 

        if ($resource === false) { 
            if (($errors = sqlsrv_errors()) != null) { 
                foreach ($errors as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return new mssqlQuery($resource); 
    } 
} 

if (!function_exists('mssql_fetch_array')) { 
    function mssql_fetch_array($mssqlQuery, $resultType = SQLSRV_FETCH_BOTH) 
    { 
        if (!$mssqlQuery instanceof mssqlQuery) { 
            return null; 
        } 

        switch ($resultType) { 
            case 'MSSQL_BOTH' : 
                $resultType = SQLSRV_FETCH_BOTH; 
                break; 
            case 'MSSQL_NUM': 
                $resultType = SQLSRV_FETCH_NUMERIC; 
                break; 
            case 'MSSQL_ASSOC': 
                $resultType = SQLSRV_FETCH_ASSOC; 
                break; 
        } 

        return $mssqlQuery->shiftData($resultType); 
    } 
} 

if (!function_exists('mssql_num_rows')) { 
    function mssql_num_rows($mssqlQuery) 
    { 
        if (!$mssqlQuery instanceof mssqlQuery) { 
            return null; 
        } 

        return $mssqlQuery->getRowsCount(); 
    } 
} 


if (!function_exists('mssql_get_last_message')) { 
    function mssql_get_last_message() 
    { 
        preg_match('/^\[Microsoft.*SQL.*Server\](.*)$/i', sqlsrv_errors(SQLSRV_ERR_ALL), $matches); 
        return $matches[1]; 
    } 
} 


mssql_connect($option['sql_host'], $option['sql_user'], $option['sql_pass']);
mssql_select_db($option['sql_dbs']);

?>                                                                                                                                                                                                                                                                                                                                        