<?php
/**
 * vim: ts=4 sw=4 et wrap co=100
 *
 * Time and Effort
 *
 * Import data from $TABLE_IMPORT into other tables in the `time_and_effort` MySQL database.
 */

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . (
    '/../../application/views/helpers/AdminActivityCategoriesCreator.php'
);

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);

// Global constants. ///////////////////////////////////////////////////////////////////////////////

define('TABLE_NAME', 'adminActivityCategory');

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try {
    $db = new Zend_Db_Adapter_Pdo_Mysql(array(
        'host'     => 'localhost',
        'username' => 'zend_user',
        'password' => 'zend@dpi' ,
        'dbname'   => 'time_and_effort'
    ));

    $creator = new AdminActivityCategoriesCreator();

    echo 'Filling table `' . TABLE_NAME . '`...';
    $creator->fillSqlTable(TABLE_NAME);
    echo "done.\n";
}
catch (Exception $e) {
   echo $e;
}

