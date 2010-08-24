<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected static $config;

    public function __construct($application)
    {
        parent::__construct($application);

        $documentType = new Zend_View_Helper_Doctype();
        $documentType->doctype('XHTML1_STRICT');

        // Load Config
        self::loadConfig();

        // Set registry variables
        Zend_Registry::set("db", self::initDatabase());
        Zend_Registry::set("ldap", self::initLdapAuth());

        // Temporary
        Zend_Registry::set("idStaff", "23002815");
    }

    protected static function loadConfig()
    {
        self::$config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini',
                APPLICATION_ENV
        );
    }

    // Setup Database Factory
    public static function initDatabase()
    {
        // Create a config object to hold what is defined in the application.ini file
        $config = self::$config;

        // Create the db factory instance to be used throughout the application
        try {
            $db = Zend_Db::factory($config->resources->db);
            Zend_Db_Table_Abstract::setDefaultAdapter($db);
        } catch (Zend_Db_Adapter_Exception $e) {
            // perhaps a failed login credential, or perhaps the RDBMS is not running
            echo 'Caught exception: ', $e->getMessage(), "\n";
        } catch (Zend_Exception $e) {
            // perhaps factory() failed to load the specified Adapter class
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return $db;
    }

    // Setup LDAP Authenication Adapter
    public static function initLdapAuth()
    {

        $config = self::$config;

        try {
            $ldap = new Zend_Ldap($config->ldap->toArray());
        } catch (Zend_Ldap_Exception $e) {
            print_r($e->getMessage());
        }

        return $ldap;
    }

}

