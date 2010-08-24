<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

/*
 *
 */
class LookupTableFiller
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
    public static function fillAll($db, $rows)
    {
        $columnNameSrcByTableName = array
        (
            'branch'        => 'ClientRepBranch'  ,
            'department'    => 'ClientRepDept'    ,
            'division'      => 'ClientRepDivision',
            'jobTitle'      => 'ClientRepJobTitle',
            'kitbOutput'    => 'KITBOutput'       ,
            'kitbSupplier'  => 'KITBSuppliers'    ,
            'misCostCentre' => 'MISCostCentre'    ,
            'stakeholder'   => 'OtherStakeholders',
        );

        foreach (self::$_tableNames as $tableName) {
            $columnNameSrc =
            (
                (array_key_exists($tableName, $columnNameSrcByTableName))?
                $columnNameSrcByTableName[$tableName]: null
            );

            self::fillTable($db, $tableName, $rows, $columnNameSrc);
        }
    }

    /*
     *
     */
    public static function fillTable(
        $db, $tableName, $rows, $columnNameSrc = null, $columnNameDst = 'name'
    )
    {
        if ($columnNameSrc === null) {
            $columnNameSrc    = $tableName;
            $columnNameSrc[0] = strtoupper($columnNameSrc[0]);
        }

        $values               = self::_getColumnUniqueAndSorted($columnNameSrc, $rows);
        $existingValuesAsKeys = self::_getExistingColumnValuesAsKeys($db, $columnNameSrc);

        foreach ($values as $value) {
            if (!array_key_exists($value, $existingValuesAsKeys)) {
                $db->insert($tableName, array($columnNameDst => $value));
            }
        }
    }

    // Private functions. //////////////////////////////////////////////////////////////////////

    /*
     *
     */
    private static function _getExistingColumnValuesAsKeys($db, $columnNameSrc)
    {
        $rawValues = $db->fetchCol("SELECT `$columnNameSrc` FROM `" . self::$_tableNameSrc . "`");

        if (in_array($columnNameSrc, self::$_namesOfColumnsToSplitOnCommas)) {
            $values = array();

            foreach ($rawValues as $rawValue) {
                $values = array_merge($values, explode(',', $rawValue));
            }
        }
        else {
            $values = $rawValues;
        }

        return array_fill_keys($values, null);
    }

    /*
     *
     */
    private static function _getColumnUniqueAndSorted($columnNameSrc, $rows)
    {
        $valuesAsKeys = array();

        foreach ($rows as $row) {
            if (!array_key_exists($columnNameSrc, $row))
            {
                throw new Exception("Column name '$columnNameSrc' not found in row.");
            }

            $values =
            (
                (in_array($columnNameSrc, self::$_namesOfColumnsToSplitOnCommas))?
                explode(',', $row[$columnNameSrc]): array($row[$columnNameSrc])
            );

            foreach ($values as $value)
            {
                if ($value != '') {
                    $valuesAsKeys[$value] = null;
                }
            }
        }

        $uniqueValues = array_keys($valuesAsKeys);

        sort($uniqueValues);

        return $uniqueValues;
    }

    // Private variables. //////////////////////////////////////////////////////////////////////

    private static $_tableNames = array(
        'approvalStatus',
        'branch'        ,
        'department'    ,
        'division'      ,
        'jobTitle'      ,
        'kitbOutput'    ,
        'kitbSupplier'  ,
        'lifeCycleStage',
        'misCostCentre' ,
        'stakeholder'
    );

    private static $_namesOfColumnsToSplitOnCommas = array(
        'KITBSuppliers',
        'OtherStakeholders'
    );

    private static $_tableNameSrc = '20100803_activities_export_from_msaccess';
}
