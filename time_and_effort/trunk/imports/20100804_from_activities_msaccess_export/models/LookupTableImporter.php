<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

/*
 *
 */
class LookupTableImporter
{
    /*
     *
     */
    public function __construct()
    {
        throw new Exception('This class is not intended to be instantiated.');
    }

    // Public functions. ///////////////////////////////////////////////////////////////////////

    /*
     *
     */
    public static function importValues($db, $tableName, $values)
    {
        foreach ($values as $value)
        {
            if (self::valueExistsInTable($db, $tableName, $value))
            {
                continue;
            }

            $db->insert($tableName, array('name' => $value);
        }
    }

    // Private functions. //////////////////////////////////////////////////////////////////////


    // Private variables. //////////////////////////////////////////////////////////////////////

}
