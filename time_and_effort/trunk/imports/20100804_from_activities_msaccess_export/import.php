<?php
/**
 * vim: ts=4 sw=4 et wrap co=100
 *
 * Time and Effort
 *
 * Import data from $TABLE_IMPORT into other tables in the `time_and_effort` MySQL database.
 */

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../library/Zend/Db/Adapter/Pdo/Mysql.php';
require_once dirname(__FILE__) . '/models/DbTable.php';
require_once dirname(__FILE__) . '/models/LookupTableFiller.php';
require_once dirname(__FILE__) . '/models/ImporterFromMsAccessActivities.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);

// Global constants. ///////////////////////////////////////////////////////////////////////////////

define('TABLE_IMPORT', '20100803_activities_export_from_msaccess');

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try {
    $db = new Zend_Db_Adapter_Pdo_Mysql(array(
        'host'     => 'localhost',
        'username' => 'zend_user',
        'password' => 'zend@dpi' ,
        'dbname'   => 'time_and_effort'
    ));

    echo 'Getting all rows from `' . TABLE_IMPORT . '`...';
    $rows = $db->fetchAll('SELECT * FROM `' . TABLE_IMPORT . '`');
    echo "done.\nGot ", count($rows), " rows.\n";

    ImporterFromMsAccessActivities::importRows($db, $rows);
}
catch (Exception $e) {
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function replacePipesWithCommas(&$rows)
{
    foreach ($rows as &$row) {
        foreach ($row as $key => &$value) {
            $value = trim(str_replace('|', ',', $value));
        }

        $db->update(TABLE_IMPORT, $row, array("`id`='{$row['id']}'"));
    }
}

