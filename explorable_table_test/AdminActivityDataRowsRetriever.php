<?php
/*
 * vim: ts=4 sw=4 et wrap co=100 go-=b
 */

//require_once dirname(__FILE__) . '/../../../library/Zend/Db/Adapter/Pdo/Mysql.php';
require_once dirname(__FILE__) . '/../../lib/zend_framework/library-1.10.7/Zend/Db/Adapter/Pdo/Mysql.php';

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

        $this->sqlColumnHeadingByDisplayHeading = array(
            'Status'               => '`approvalStatus`.`name` AS `approvalStatusName`',
            'Output - Cost Centre' => '`misCostCentre`.`name` AS `misCostCentreName`'  ,
            'Act. No.'             => '`activity`.`activityNo`'                        ,
            'Activity Title'       => '`activity`.`activityTitle`'                     ,
            'Due Date'             => (
                'IF(`activity`.`dateDue` IS NULL, "", `activity`.`dateDue`) AS `dateDue`'
            ),
            'Manager'              => (
                'IF(`activity`.`idStaff_leadTeamManager` IS NULL, "", CONCAT(' .
                '   `staff_leadTeamManager`.`first_name`, " ",' .
                '   `staff_leadTeamManager`.`last_name`' .
                ')) AS `leadTeamManagerNameFull`'
            )
        );

        $this->defaultSqlJoinClauses = array(
            'LEFT JOIN `approvalStatus` ON (`activity`.`idApprovalStatus`=`approvalStatus`.`id`)',
            'LEFT JOIN `misCostCentre` ON (`activity`.`idMisCostCentre`=`misCostCentre`.`id`)',
            'LEFT JOIN `staff` AS `staff_leadTeamManager` ON (' .
                '`activity`.`idStaff_leadTeamManager`=`staff_leadTeamManager`.`identifier`' .
            ')'
        );
    }

    // Getters. ------------------------------------------------------------------------------//

    /*
     *
     */
    public function getColumnHeadings()
    {
        return array_keys($this->sqlColumnHeadingByDisplayHeading);
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
        $sqlJoinClauses     = array_unique(
            array_merge($this->defaultSqlJoinClauses, $sqlQueryAdditions['sqlJoinClauses'])
        );

        // TODO
        // ----
        // Even though the sql where conditions and join clauses come from an internal table, they
        // ought to be checked somehow before being inserted into an sql query to guard against the
        // possibility of an sql-injection attack.  Not sure how best to do that though.
        return (
            'SELECT DISTINCT ' .
            implode(',', array_values($this->sqlColumnHeadingByDisplayHeading)) . "\n" .
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
             FROM `adminActivityCategory`
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

    // Private variables. //////////////////////////////////////////////////////////////////////

    private $db                               = null;
    private $sqlColumnHeadingByDisplayHeading = null;
    private $defaultSqlJoinClauses            = null;
}

