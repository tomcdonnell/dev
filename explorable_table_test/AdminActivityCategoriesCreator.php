<?php
/*
 * vim: ts=4 sw=4 et wrap co=100 go-=b
 */

//require_once dirname(__FILE__) . '/../../../library/Zend/Db/Adapter/Pdo/Mysql.php';
require_once dirname(__FILE__) . '/../../lib/zend_framework/library-1.10.7/Zend/Db/Adapter/Pdo/Mysql.php';

/*
 *
 */
class AdminActivityCategoriesCreator
{
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

    /*
     *
     */
    public function fillSqlTable($tableName)
    {
        $this->_fillCategoriesToSqlTable($tableName, $this->getAsArray());
    }

    // Private functions. //////////////////////////////////////////////////////////////////////

    /*
     *
     */
    private function getAsArray()
    {
        return array(
            'By Approval Status'   => self::_createApprovalStatusCategories(),
            'By Assigned Employee' => self::_createSelectEmployeeCategories(),
            'By Client'            => self::_createSelectClientCategories()  ,
            'By KITB Output'       => self::_createKitbOutputCategories()    ,
            'By Manager'           => self::_createSelectManagerCategories() ,
            'By Due Date'          => self::_createDatesCategories('`activity`.`dateDue`'),
            'By End Date'          => self::_createDatesCategories(
                '`activity`.`concept_dateFinish`'
            )
        );
    }

    /*
     *
     */
    private function _fillCategoriesToSqlTable(
        $tableName, $categoryInfoByName, $idParentNode = null
    )
    {
        foreach ($categoryInfoByName as $name => $categoryInfo)
        {
            $this->db->insert(
                $tableName, array(
                    'idParent'          => $idParentNode                     ,
                    'name'              => $name                             ,
                    'sqlWhereCondition' => $categoryInfo['sqlWhereCondition'],
                    'sqlJoinClause'     => $categoryInfo['sqlJoinClause'    ],
                    'sortOrder'         => $categoryInfo['sortOrder'        ]
                )
            );

            $idNode = $this->db->lastInsertId();

            $this->_fillCategoriesToSqlTable($tableName, $categoryInfo['children'], $idNode);
        }
    }

    /*
     *
     */
    private function _createApprovalStatusCategories()
    {
        $sqlJoinClause = (
            'LEFT JOIN `approvalStatus` ON (`activity`.`idApprovalStatus`=`approvalStatus`.`id`)'
        );

        $alphabetSplitCategories = $this->_getAlphabetSplitCategories
        (
            'Activity Title', '`activity`.`activityTitle`'
        );

        $approvalStatusCategoryInfoByName = array(
            'Approved' => array(
                'children'          => $alphabetSplitCategories,
                'sqlJoinClause'     => $sqlJoinClause          ,
                'sqlWhereCondition' => '`activity`.`idApprovalStatus`=1'
            ),
            'Rejected' => array(
                'children'          => $alphabetSplitCategories,
                'sqlJoinClause'     => $sqlJoinClause          ,
                'sqlWhereCondition' => '`activity`.`idApprovalStatus`=2'
            ),
            'Closed'   => array(
                'children'          => $alphabetSplitCategories,
                'sqlJoinClause'     => $sqlJoinClause          ,
                'sqlWhereCondition' => '`activity`.`idApprovalStatus`=2'
            )
        );

        $this->_addSortOrderToCategories($approvalStatusCategoryInfoByName);

        return array(
            'children'          => $approvalStatusCategoryInfoByName,
            'sortOrder'         => '0'                              ,
            'sqlJoinClause'     => ''                               ,
            'sqlWhereCondition' => ''
        );
    }

    /*
     *
     */
    private function _createSelectEmployeeCategories()
    {
        $branchNamesByDivisionName  = $this->_getBranchNamesByDivisionNameFromStaffTable();
        $divisionCategoryInfoByName = array();
        $sqlJoinClause              = (
            'JOIN `link_activity_staff` ON (' .
                '`activity`.`id`=`link_activity_staff`.`idActivity`' .
            ")\n" .
            'JOIN `staff` ON (' .
                '`link_activity_staff`.`idStaff`=`staff`.`identifier`' .
            ")\n"
        );

        foreach ($branchNamesByDivisionName as $divisionName => $branchNames) {
            $branchCategoryInfoByName = array();

            foreach ($branchNames as $branchName) {
                $branchCategoryInfoByName[$branchName] = array(
                    'sqlJoinClause'     => $sqlJoinClause,
                    'sortOrder'         => '0'           ,
                    'sqlWhereCondition' => (
                        '`staff`.`branch`="' . $branchName . '"'
                    ),
                    'children'          => $this->_getAlphabetSplitCategories(
                        'Employee First Name', '`staff`.`first_name`'
                    )
                );
            }

            $divisionCategoryInfoByName[$divisionName] = array(
                'children'          => $branchCategoryInfoByName,
                'sqlJoinClause'     => $sqlJoinClause           ,
                'sqlWhereCondition' => '`staff`.`division`="' . $divisionName . '"'
            );
        }

        $this->_addSortOrderToCategories($divisionCategoryInfoByName);

        return array(
            'children'          => $divisionCategoryInfoByName,
            'sortOrder'         => '0'                        ,
            'sqlJoinClause'     => ''                         ,
            'sqlWhereCondition' => ''
        );
    }

    /*
     *
     */
    private function _createSelectClientCategories()
    {
        $clientCategoryInfoByName = array(
            'Agriculture Research & Development' => array(
                'children'          => array(),
                'sqlJoinClause'     => ''     ,
                'sqlWhereCondition' => ''
            ),
            'Agriculture & Fisheries Services' => array(
                'children'          => array(),
                'sqlJoinClause'     => ''     ,
                'sqlWhereCondition' => ''
            ),
            'Agriculture & Natural Resources Policy Division' => array(
                'children'          => array(),
                'sqlJoinClause'     => ''     ,
                'sqlWhereCondition' => ''
            )
        );

        $this->_addSortOrderToCategories($clientCategoryInfoByName);

        return array(
            'children'          => $clientCategoryInfoByName,
            'sortOrder'         => '0'                      ,
            'sqlJoinClause'     => ''                       ,
            'sqlWhereCondition' => ''
        );
    }

    /*
     *
     */
    private function _createDatesCategories($sqlDateColumnName)
    {
        $yearCategoryInfoByName = array();

        foreach (range(2008, 2020) as $year) {
            $yearCategoryInfoByName[$year] = array(
                'sqlWhereCondition' => "YEAR($sqlDateColumnName)='$year'",
                'sqlJoinClause'     => ''                                ,
                'children'          => self::_getMonthCategoryInfoByName($sqlDateColumnName)
            );
        }

        $this->_addSortOrderToCategories($yearCategoryInfoByName);

        return array(
            'children'          => $yearCategoryInfoByName,
            'sortOrder'         => '0'                    ,
            'sqlJoinClause'     => ''                     ,
            'sqlWhereCondition' => ''
        );
    }

    /*
     *
     */
    private function _createKitbOutputCategories()
    {
        $kitbOutputNamesById          = $this->_getKitbOutputNamesById();
        $kitbOutputCategoryInfoByName = array();

        foreach ($kitbOutputNamesById as $idKitbOutput => $kitbOutputName) {
            $kitbOutputCategoryInfoByName[$kitbOutputName] = array(
                'children'          => array()                          ,
                'sqlWhereCondition' => "`kitbOutput`.`id`=$idKitbOutput",
                'sqlJoinClause'     => (
                    'JOIN `kitbOutput` ON (`activity`.`idKitbOutput`=`kitbOutput`.`id`)'
                ),
            );
        }

        $this->_addSortOrderToCategories($kitbOutputCategoryInfoByName);

        return array(
            'children'          => $kitbOutputCategoryInfoByName,
            'sortOrder'         => '0'                          ,
            'sqlJoinClause'     => ''                           ,
            'sqlWhereCondition' => ''
        );
    }

    /*
     *
     */
    private function _createSelectManagerCategories()
    {
        $categoryInfoByName = array(
            'sqlWhereCondition' => '' ,
            'sqlJoinClause'     => '' ,
            'sortOrder'         => '0',
            'children'          => array(
                'BioSciences Research' => array(
                    'sqlWhereCondition' => '',
                    'sqlJoinClause'     => '',
                    'children'          => array(
                        'Bio-protection Platform' => array(
                            'sqlWhereCondition' => '',
                            'sqlJoinClause'     => '',
                            'children'          => array()
                        ),
                        'Biosciences Platform' => array(
                            'sqlWhereCondition' => '',
                            'sqlJoinClause'     => '',
                            'children'          => array()
                        )
                    )
                ),
                'Biosecurity Victoria' => array(
                    'sqlWhereCondition' => '',
                    'sqlJoinClause'     => '',
                    'children'          => array(
                        'Animal Standards' => array(
                            'sqlWhereCondition' => '',
                            'sqlJoinClause'     => '',
                            'children'          => array()
                        ),
                        'Bureau Animal Welfare' => array(
                            'sqlWhereCondition' => '',
                            'sqlJoinClause'     => '',
                            'children'          => array()
                        )
                    )
                )
            )
        ); 

        $this->_addSortOrderToCategories($categoryInfoByName['children']);

        return $categoryInfoByName;
    }

    /*
     *
     */
    private function _getMonthCategoryInfoByName($sqlDateColumnName)
    {
        $monthNameByIndex = array(
            1 => 'January'  ,  2 => 'February',  3 => 'March'   ,  4 => 'April'   ,
            5 => 'May'      ,  6 => 'June'    ,  7 => 'July'    ,  8 => 'August'  ,
            9 => 'September', 10 => 'October' , 11 => 'November', 12 => 'December'
        );

        $categoryInfoByName = array();

        foreach ($monthNameByIndex as $index => $monthName) {
            $categoryInfoByName[$monthName] = array(
                'sqlWhereCondition' => "MONTH($sqlDateColumnName)='$index'",
                'sqlJoinClause'     => ''                                  ,
                'sortOrder'         => (string)($index - 1)                ,
                'children'          => array()
            );
        }

        return $categoryInfoByName;
    }

    /*
     *
     */
    private function _getBranchNamesByDivisionNameFromStaffTable()
    {
        $rows = $this->db->query(
            'SELECT `division`, `branch`
             FROM `staff`
             GROUP BY `division`, `branch`'
        );

        $branchNamesByDivisionName = array();

        foreach ($rows as $row) {
            $divisionName = $row['division'];
            $branchName   = $row['branch'  ];

            if (!array_key_exists($divisionName, $branchNamesByDivisionName)) {
                $branchNamesByDivisionName[$divisionName] = array();
            }

            $branchNamesByDivisionName[$divisionName][] = $branchName;
        }

        return $branchNamesByDivisionName;
    }

    /*
     * Return categories 'A-K', and 'L-Z' each with subcategories for each letter.
     * The sql where condition associated with each category will restrict the data rows to those
     * for which the given $sqlColumnName starts with the letter from the category name.
     */
    private function _getAlphabetSplitCategories($splitFieldDisplayName, $sqlColumnName)
    {
        $atokCategories = array();
        $ltozCategories = array();

        // Must allow for possibility of string starting with
        // character with ASCII value less than ord('a').
        $atokCategories['<A'] = array(
            'sqlJoinClause'     => ''     ,
            'children'          => array(),
            'sqlWhereCondition' => "$sqlColumnName<'a'" 
        );

        for ($ascii = ord('A'); $ascii <= ord('Z'); ++$ascii) {
            $cUpper   = chr($ascii);
            $cLower   = strtolower($cUpper);
            $category = array(
                'sqlJoinClause'     => ''     ,
                'children'          => array(),
                'sqlWhereCondition' => (
                    "$sqlColumnName LIKE '$cUpper%' OR $sqlColumnName LIKE '$cLower%'"
                )
            );

            switch ($cUpper < 'L') {
                case true : $atokCategories[$cUpper] = $category; break;
                case false: $ltozCategories[$cUpper] = $category; break;
            }
        }

        // Must allow for possibility of string starting with
        // character with ASCII value greater than ord('Z').
        $ltozCategories['>Z'] = array(
            'sqlJoinClause'     => ''     ,
            'children'          => array(),
            'sqlWhereCondition' => "$sqlColumnName>'Z'" 
        );

        return array(
            "$splitFieldDisplayName: A-K" => array(
                'sqlWhereCondition' => "$sqlColumnName<'L'",
                'sqlJoinClause'     => ''                  ,
                'children'          => $atokCategories
            ),
            "$splitFieldDisplayName: L-Z" => array(
                'sqlWhereCondition' => "$sqlColumnName>='L'",
                'sqlJoinClause'     => ''                   ,
                'children'          => $ltozCategories
            )
        );
    }

    /*
     *
     */
    private function _addSortOrderToCategories(&$categoryInfoByName)
    {
        $sortOrderNo = 0;

        foreach ($categoryInfoByName as $name => &$categoryInfo) {
            if (!array_key_exists('sortOrder', $categoryInfo)) {
                $categoryInfo['sortOrder'] = $sortOrderNo++;
            }

            if (count($categoryInfo['children']) > 0) {
                $this->_addSortOrderToCategories($categoryInfo['children']);
            }
        }
    }

    /*
     *
     */
    private function _getKitbOutputNamesById()
    {
        return $this->db->fetchPairs(
            'SELECT `id`, `name`
             FROM `kitbOutput`'
        );
    }

    // Private variables. //////////////////////////////////////////////////////////////////////

    private $db = null;
}

