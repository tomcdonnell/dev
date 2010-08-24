<?php
/*
 * vim: ts=4 sw=4 et wrap co=100 go+=b
 */

/*
 *
 */
class AdminActivityDataRowsRetriever
{
    private $staffMapper;

    // Public functions. ///////////////////////////////////////////////////////////////////////

    /*
     *
     */
    public function __construct()
    {
        $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'zend_user',
            'password' => 'zend@dpi' ,
            'dbname'   => 'time_and_effort'
        ));
    }

    // Getters. ------------------------------------------------------------------------------//

    /*
     * @param $idCategoryRoot
     *    An id from a row of table self::ACTIVITY_CATEGORY_TABLE_NAME for which `idParent` IS NULL.
     */
    public function getColumnHeadings($idCategoryRoot)
    {
        $categoryInfo = self::_getRootCategoryInfo($idCategoryRoot);

        return array_keys($categoryInfo['sqlColumnDescByDisplayHeading']);
    }

    /*
     *
     */
    public function getDataRowsForCategory($idCategory)
    {
        return $this->db->fetchAll(
            $this->_getGetDataRowsForCategorySql($idCategory), array(), Zend_Db::FETCH_NUM
        );
    }

    /*
     *
     */
    public function getN_dataRowsForCategory($idCategory)
    {
        $count = $this->db->fetchOne(
            'SELECT COUNT(*) AS `count`
             FROM (' .
                $this->_getGetDataRowsForCategorySql($idCategory) .
            ') AS `dummy`',
            array(), Zend_Db::FETCH_NUM
        );

        return (int)$count;
    }

    // Private functions. //////////////////////////////////////////////////////////////////////

    /*
     *
     */
    private function _getGetDataRowsForCategorySql($idCategory)
    {
        $sqlQueryAdditions  = $this->_getSqlAdditionsToGetDataRowsQueryForCategory($idCategory);
        $sqlWhereConditions = $sqlQueryAdditions['sqlWhereConditions'];
        $idCategoryRoot     = $this->_getIdCategoryRootFromIdCategory($idCategory);
        $categoryInfo       = self::_getRootCategoryInfo($idCategoryRoot);
        $sqlJoinClauses     = array_unique(
            array_merge(
                $categoryInfo['defaultSqlJoinClauses'],
                $sqlQueryAdditions['sqlJoinClauses']
            )
        );

        // TODO
        // ----
        // Even though the sql where conditions and join clauses come from an internal table, they
        // ought to be checked somehow before being inserted into an sql query to guard against the
        // possibility of an sql-injection attack.  Not sure how best to do that though.
        return (
            'SELECT DISTINCT ' .
            implode(',', array_values($categoryInfo['sqlColumnDescByDisplayHeading'])) . "\n" .
            "FROM `activity`\n" .
            implode("\n", $sqlJoinClauses) . "\n" .
            "WHERE\n" .
            implode("\nAND ", $sqlWhereConditions) . "\n" .
            'ORDER BY `activityTitle` ASC'
        );
    }

    /*
     *
     */
    private function _getSqlAdditionsToGetDataRowsQueryForCategory(
        $idCategory, $recursiveParams = null
    )
    {
        if ($recursiveParams === null) {
            $recursiveParams = array(
                'sqlWhereConditionsAsKeys' => array('1' => null), 
                'sqlJoinClausesAsKeys'     => array(           )
            );
        }

        $row = $this->db->fetchRow(
            'SELECT `idParent`, `sqlWhereCondition`, `sqlJoinClause`
             FROM `' . self::ACTIVITY_CATEGORY_TABLE_NAME . '`
             WHERE `id`=?', $idCategory
        );

        if ($row['sqlWhereCondition'] != '') {
            $recursiveParams['sqlWhereConditionsAsKeys'][$row['sqlWhereCondition']] = null;
        }

        if ($row['sqlJoinClause'    ] != '') {
            $recursiveParams['sqlJoinClausesAsKeys'    ][$row['sqlJoinClause'    ]] = null;
        }

        $sqlWhereConditions = array_keys($recursiveParams['sqlWhereConditionsAsKeys']);
        $sqlJoinClauses     = array_keys($recursiveParams['sqlJoinClausesAsKeys'    ]);

        // Enclose each sql where condition in brackets so that when
        // they are ANDed together, the result computes as expected.
        foreach ($sqlWhereConditions as &$sqlWhereCondition) {
            $sqlWhereCondition = "($sqlWhereCondition)";
        }

        return (
            ($row['idParent'] === null)? array(
                'sqlWhereConditions' => $sqlWhereConditions,
                'sqlJoinClauses'     => $sqlJoinClauses
            ):
            $this->_getSqlAdditionsToGetDataRowsQueryForCategory($row['idParent'], $recursiveParams)
        );
    }

    /*
     *
     */
    private function _getIdCategoryRootFromIdCategory($idCategory)
    {
        $idCategoryParent = $this->db->fetchOne(
            'SELECT `idParent`
             FROM `' . self::ACTIVITY_CATEGORY_TABLE_NAME . '`
             WHERE `id`=?', $idCategory
        );

        return (
            ($idCategoryParent === null)? $idCategory:
            $this->_getIdCategoryRootFromIdCategory($idCategoryParent)
        );
    }

    /*
     * @param $idCategoryRoot
     *     The `id` of a row of self::ACTIVITY_CATEGORY_TABLE_NAME for which `idParent` IS NULL.
     */
    private static function _getRootCategoryInfo($idCategoryRoot)
    {
        // NOTE
        // ----
        // These constants should be updated whenever a relevant change is made to the contents
        // of the self::ACTIVITY_CATEGORY_TABLE_NAME sql table.  Each id listed below should
        // correspond to a row in that table for which `idParent` IS NULL.
        $ID_CATEGORY_ROOT_BY_APPROVAL_STATUS   =    1;
        $ID_CATEGORY_ROOT_BY_ASSIGNED_EMPLOYEE =   95;
        $ID_CATEGORY_ROOT_BY_CLIENT            = 2838;
        $ID_CATEGORY_ROOT_BY_DUE_DATE          = 2857;
        $ID_CATEGORY_ROOT_BY_END_DATE          = 3027;
        $ID_CATEGORY_ROOT_BY_KITB_OUTPUT       = 2842;
        $ID_CATEGORY_ROOT_BY_MANAGER           = 2850;

        // CATEGORY INFO
        // -------------
        //
        // This array defines what columns from the `activity` table (and joined tables) are
        // displayed when data rows are retrieved using the activity browsing interface of the
        // administrator's section.
        //
        // Format:
        // array(
        //     <int idCategoryRoot> => array(
        //         'sqlColumnDescByDisplayHeading' => array(
        //             <string displayHeading> => <string sqlColumnDesc>
        //             ...
        //         ),
        //         'defaultSqlJoinClauses' => array(
        //             <string sqlJoinClause>,
        //             ...
        //         )
        //     ),
        //     ...
        // )
        //
        // NOTES
        // -----
        //  * The purpose of the join clauses is to ensure that all column descriptions are
        //    available in the query result.
        //  * Each sqlColumnDescription must return a string always.  If the expression may return
        //    NULL in some cases, use a MySQL conditional (eg. IF(<exp> IS NULL, "", <exp>)) to
        //    ensure that a string is returned in all cases.
        switch ($idCategoryRoot) {
            case $ID_CATEGORY_ROOT_BY_APPROVAL_STATUS  : // Fall through.
            case $ID_CATEGORY_ROOT_BY_ASSIGNED_EMPLOYEE: // Fall through.
            case $ID_CATEGORY_ROOT_BY_DUE_DATE         : // Fall through.
            case $ID_CATEGORY_ROOT_BY_KITB_OUTPUT      : // Fall through.
            case $ID_CATEGORY_ROOT_BY_MANAGER          :
                $returnArray = array(
                    'sqlColumnDescByDisplayHeading' => array(
                        'Act. No.'             => 'activity.activityNo'                      ,
                        'Activity Title'       => 'activity.activityTitle'                   ,
                        'Status'               => 'approvalStatus.name AS approvalStatusName',
                        'Output - Cost Centre' => 'misCostCentre.name AS misCostCentreName'  ,
                        'Due Date'             => (
                            'IF(activity.dateDue IS NULL, "", activity.dateDue) AS dateDue'
                        ),
                        'Manager'              => (
                            'IF(activity.idStaff_leadTeamManager IS NULL, "", CONCAT(' .
                            '   staff_leadTeamManager.first_name, " ",' .
                            '   staff_leadTeamManager.last_name' .
                            ')) AS leadTeamManagerNameFull'
                        )
                    ),
                    'defaultSqlJoinClauses' => array(
                        'LEFT JOIN approvalStatus ON' .
                        ' (activity.idApprovalStatus=approvalStatus.id)',
                        'LEFT JOIN misCostCentre ON' .
                        ' (activity.idMisCostCentre=misCostCentre.id)',
                        'LEFT JOIN staff AS staff_leadTeamManager ON' .
                        ' (activity.idStaff_leadTeamManager=staff_leadTeamManager.identifier)'
                    )
                );
                break;
            case $ID_CATEGORY_ROOT_BY_CLIENT:
                $returnArray = array(
                    'sqlColumnDescByDisplayHeading' => array(
                        'Act. No.'             => 'activity.activityNo'                      ,
                        'Activity Title'       => 'activity.activityTitle'                   ,
                        'Status'               => 'approvalStatus.name AS approvalStatusName',
                        'Client'               => (
                            'IF(client.name IS NULL, "", client.name) AS `clientName`'
                        ),
                        'Due Date'             => (
                            'IF(activity.dateDue IS NULL, "", activity.dateDue) AS dateDue'
                        ),
                        'Manager'              => (
                            'IF(activity.idStaff_leadTeamManager IS NULL, "", CONCAT(' .
                            '   staff_leadTeamManager.first_name, " ",' .
                            '   staff_leadTeamManager.last_name' .
                            ')) AS leadTeamManagerNameFull'
                        )
                    ),
                    'defaultSqlJoinClauses' => array(
                        'LEFT JOIN approvalStatus ON' .
                        ' (activity.idApprovalStatus=approvalStatus.id)',
                        'LEFT JOIN client ON' .
                        ' (activity.idClient_sponsor=client.id)',
                        'LEFT JOIN staff AS staff_leadTeamManager ON' .
                        ' (activity.idStaff_leadTeamManager=staff_leadTeamManager.identifier)'
                    )
                );
                break;
            case $ID_CATEGORY_ROOT_BY_END_DATE:
                $returnArray = array(
                    'sqlColumnDescByDisplayHeading' => array(
                        'Act. No.'             => 'activity.activityNo'                      ,
                        'Activity Title'       => 'activity.activityTitle'                   ,
                        'Status'               => 'approvalStatus.name AS approvalStatusName',
                        'Output - Cost Centre' => 'misCostCentre.name AS misCostCentreName'  ,
                        'Completion Date'      => (
                            'IF(' .
                                'activity.concept_dateFinish IS NULL, "", ' .
                                'activity.concept_dateFinish' .
                            ') AS concept_dateFinish'
                        ),
                        'Manager'              => (
                            'IF(activity.idStaff_leadTeamManager IS NULL, "", CONCAT(' .
                            '   staff_leadTeamManager.first_name, " ",' .
                            '   staff_leadTeamManager.last_name' .
                            ')) AS leadTeamManagerNameFull'
                        )
                    ),
                    'defaultSqlJoinClauses' => array(
                        'LEFT JOIN approvalStatus ON' .
                        ' (activity.idApprovalStatus=approvalStatus.id)',
                        'LEFT JOIN misCostCentre ON' .
                        ' (activity.idMisCostCentre=misCostCentre.id)',
                        'LEFT JOIN staff AS staff_leadTeamManager ON' .
                        ' (activity.idStaff_leadTeamManager=staff_leadTeamManager.identifier)'
                    )
                );
                break;
            default:
                throw new Exception(
                    "No column headings found for idCategoryRoot '$idCategoryRoot'. " .
                    ' Have you updated the `' . self::ACTIVITY_CATEGORY_TABLE_NAME . '` table' .
                    ' without updating the hard-coded column headings list for each root category?'
                );
        }

        return $returnArray;
    }

    // Private variables. //////////////////////////////////////////////////////////////////////

    private $db = null;

    // Class constants. ////////////////////////////////////////////////////////////////////////

    const ACTIVITY_CATEGORY_TABLE_NAME = 'adminActivityCategory';
}

