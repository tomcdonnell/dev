<?php
/*
 * vim: ts=4 sw=4 et nowrap co=100 go-=b
 */

/*
 *
 */
class AdminActivityCategoriesRetriever_2
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
     *
    public function getChildCategoryInfoById($idParent)
    {
        $sqlSelectClause  = 'SELECT `id`, `name` FROM `adminActivityCategory`';
        $sqlOrderByClause = 'ORDER BY `sortOrder` ASC';

        $rows = (
            ($idParent === null)?
            $this->db->fetchAll("$sqlSelectClause WHERE `idParent` IS NULL $sqlOrderByClause"     ):
            $this->db->fetchAll("$sqlSelectClause WHERE `idParent`=? $sqlOrderByClause", $idParent)
        );

        $dRetriever = new AdminActivityDataRowsRetriever();

        // Add row count info and correct type of `id`.
        foreach ($rows as &$row) {
            $row['id'               ] = (int)$row['id'];
            $row['n_childCategories'] = $this->getN_childCategories($row['id']);
            $row['n_childDataRows'  ] = $dRetriever->getN_dataRowsForCategory($row['id']);
        }

        return $rows;
    }
     */

    /*
     *
     */
    public function getChildCategoryInfoById($ancestorCategoryTypesById)
    {
        $sqlWhereConditions[] = array();

        foreach ($ancestorCategoryIdsByType as $categoryType => $idCategory) {
            if (!array_key_exists($categoryType, $this->categoryInfoByType)) {
                throw new Exception("Unknown category type '$categoryType'.");
            }

            $categoryInfo = $this->categoryInfoByType[$categoryType];

            $sqlWhereConditions[] = $categoryInfo['sqlWhereCondition']
        }
    }

    /*
     *
     */
    public function getN_childCategories($idCategory)
    {
        $n_childNodes = $this->db->fetchOne(
            'SELECT COUNT(*) AS `count`
             FROM `adminActivityCategory`
             WHERE `idParent`=?', $idCategory
        );

        return (int)$n_childNodes;
    }

    // Private variables. //////////////////////////////////////////////////////////////////////

    private $db = null;

    private $rootCategoryInfoById = array(
        array(
            'name'         => 'By Approval Status',
            'categoryType' => 'approvalStatus'    ,
            'param'        => array()
        ),
        array(
            'name'         => 'By Assigned Employee',
            'categoryType' => 'dpiDivision'         ,
            'param'        => array(
                'sqlIdStaffColumnNameFull' => 'staff.id',
                'sqlJoinClauses'           => array(
                    'JOIN link_activity_staff ON (activity.id=link_activity_staff.idActivity)',
                    'JOIN staff ON (link_activity_staff.idStaff=staff.id)'
                )
            )
        ),
        array(
            'name'         => 'By Client',
            'categoryType' => 'dpiClient',
            'param'        => array()
        ),
        array(
            'name'         => 'By Due Date',
            'categoryType' => 'year'       ,
            'param'        => array(
                'sqlColumnNameFull' => 'activity.dateDue'
            )
        ),
        array(
            'name'         => 'By End Date',
            'categoryType' => 'year'       ,
            'param'        => array(
                'sqlColumnNameFull' => 'activity.concept_dateFinish'
            )
        ),
        array(
            'name'         => 'By KITB Output',
            'categoryType' => 'kitbOutput'    ,
            'param'        => array()
        ),
        array(
            'name'         => 'By Manager' ,
            'categoryType' => 'dpiDivision',
            'param'        => array(
                'sqlIdStaffColumnNameFull' => 'staff.id',
                'sqlJoinClauses'           => array(
                    'JOIN staff ON (activity.idStaff_leadTeamManager=staff.id)'
                )
            )
        ),
    );

    private $categoryInfoByType = array(
        'approvalStatus' => array(
            'categoryNameById'    => $this->_getApprovalStatusNameById(),
            'expectedParams'      => array()                            ,
            'sqlWhereCondition'   => 'approvalStatus.id=__CATEGORY_ID__',
            'sqlJoinClauses'      => array(
                'JOIN approvalStatus ON (activity.idApprovalStatus=approvalStatus.id)'
            ),
            'subCategoryTypeInfo' => array(
                'name'   => 'alphabetSplit',
                'params' => array(
                    'sqlColumnNameFull' => 'activity.activityTitle',
                    'sqlJoinClause'     => ''
                )
            )
        ),
        'alphabetSplit' => array(
            'expectedParams'      => array(
                'sqlColumnNameFull', 'sqlJoinClauses', 'displayColumnName'
            ),
            'categoryNameById'    => array(
                '__PARAM_displayColumnName__ - A-K',
                '__PARAM_displayColumnName__ - L-Z'
            ),
            'sqlWhereCondition'   => (
                'CASE __CATEGORY_ID__' .
                ' WHEN "0" THEN (__PARAM_sqlColumnNameFull__<"L")' .
                ' WHEN "1" THEN (__PARAM_sqlColumnNameFull__>="L")' .
                ' ELSE "ERROR: UNEXPECTED CATEGORY ID"'
            ),
            'sqlJoinClauses'      => '__PARAM_sqlJoinClauses__',
            'subCategoryTypeInfo' => array(
                'name'   => 'alphabetLettersAK',
                'params' => array(
                    'sqlColumnNameFull' => '__PARAM_sqlColumnNameFull__'
                )
            ) 
        ),
        'alphabetLetttersAK' => array(
            'categoryNameById'    => array('a', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'),
            'expectedParams'      => array('sqlColumnNameFull', 'sqlJoinClauses'                ),
            'sqlWhereCondition'   => (
                '__PARAM_sqlColumnNameFull__ LIKE "__CATEGORY_NAME__%" OR ' .
                '__PARAM_sqlColumnNameFull__ LIKE CONCAT(LOWER(__CATEGORY_NAME__), "%")'
            ),
            'sqlJoinClauses'      => '__PARAM_sqlJoinClauses__',
            'subcategoryTypeInfo' => null
        ),
        'dpiDivision' => array(
            'categoryNameById'    => $this->_getDpiDivisionByIdFromStaffTable()         ,
            'expectedParams'      => array('sqlIdStaffColumnNameFull', 'sqlJoinClauses'),
            'sqlWhereCondition'   => 'staff.division=__CATEGORY_NAME__'                 ,
            'sqlJoinClauses'      => array(
                'JOIN link_activity_staff ON (activity.id=link_activity_staff.idActivity)',
                'JOIN staff ON (link_activity_staff.idStaff=staff.id)'
            ),
            'subCategoryTypeInfo' => array(
                'name'   => 'dpiBranch',
                'params' => array(
                    'sqlIdStaffColumnNameFull' => '__PARAM_sqlIdStaffColumnNameFull__',
                    'sqlJoinClauses'           => '__PARAM_sqlJoinClauses__'
                )
            )
        ),
        'dpiBranch' => array(
            'categoryNameById'    => $this->_getDpiBranchByIdFromStaffTable()           ,
            'expectedParams'      => array('sqlIdStaffColumnNameFull', 'sqlJoinClauses'),
            'sqlWhereCondition'   => 'staff.branch=__CATEGORY_NAME__'                   ,
            'sqlJoinClauses'      => array(
                'JOIN link_activity_staff ON (activity.id=link_activity_staff.idActivity)',
                'JOIN staff ON (link_activity_staff.idStaff=staff.id)'
            ),
            'subCategoryTypeInfo' => array(
                'name'   => 'dpiSection',
                'params' => array(
                    'sqlIdStaffColumnNameFull' => '__PARAM_sqlIdStaffColumnNameFull__',
                    'sqlJoinClauses'           => '__PARAM_sqlJoinClauses__'
                )
            )
        ),
        'dpiSection' => array(
            'categoryNameById'    => $this->_getDpiSectionByIdFromStaffTable(),
            'expectedParams'      => array('sqlIdStaffColumnNameFull', 'sqlJoinClauses'),
            'sqlWhereCondition'   => 'staff.section=__CATEGORY_NAME__'        ,
            'sqlJoinClauses'      => array(
                'JOIN link_activity_staff ON (activity.id=link_activity_staff.idActivity)',
                'JOIN staff ON (link_activity_staff.idStaff=staff.id)'
            ),
            'subCategoryTypeInfo' => array(
                'name'   => 'alphabetSplit',
                'params' => array(
                    'displayColumnName'        => 'Employee First Name',
                    'sqlIdStaffColumnNameFull' => 'staff.first_name'   ,
                    'sqlJoinClauses'           => '__PARAM_sqlJoinClauses__'
                )
            )
        ),
        'dpiClient' => array(
            'categoryNameById'    => $this->_getDpiClientById()         ,
            'expectedParams'      => array()                            ,
            'sqlWhereCondition'   => 'activity.idClient=__CATEGORY_ID__',
            'sqlJoinClauses'      => array()                            ,
            'subCategoryTypeInfo' => array(
                'name'   => 'alphabetSplit',
                'params' => array(
                    'displayColumnName' => 'Client Name',
                    'sqlColumnNameFull' => 'client.name',
                    'sqlJoinClauses'    => array(
                        'JOIN client ON (activity.idClient_sponsor=client.id)'
                    )
                )
            )
        ),
        'year' => array(
            'categoryNameById'    => $this->_getYearRangeFromActivityTable()              ,
            'expectedParams'      => array('sqlColumnNameFull')                           ,
            'sqlWhereCondition'   => 'YEAR(__PARAM_sqlColumnNameFull__)=__CATEGORY_NAME__',
            'sqlJoinClauses'      => array()                                              ,
            'subCategoryTypeInfo' => array(
                'name'   => 'month',
                'params' => array(
                    'sqlColumnNameFull' => '__PARAM_sqlColumnNameFull__',
                    'sqlJoinClauses'    => array()
                )
            )
        ),
        'month' => array(
            'categoryNameById'    => array(
                1 => 'January'  ,  2 => 'February',  3 => 'March'   ,  4 => 'April' ,
                5 => 'May'      ,  6 => 'June'    ,  7 => 'July'    ,  8 => 'August',
                9 => 'September', 10 => 'October' , 11 => 'November', 12 => 'December'
            ),
            'expectedParams'      => array('sqlColumnNameFull')                          ,
            'sqlWhereCondition'   => 'MONTH(__PARAM_sqlColumnNameFull__)=__CATEGORY_ID__',
            'sqlJoinClauses'      => array()                                             ,
            'subCategoryTypeInfo' => null
        ),
        'kitbOutput' => array(
            'categoryNameById'    => $this->_getKitbOutputById()            ,                
            'expectedParams'      => array()                                ,
            'sqlWhereCondition'   => 'activity.idKitbOutput=__CATEGORY_ID__',
            'sqlJoinClauses'      => array()                                ,
            'subCategoryTypeInfo' => array(
                'name'   => 'alphabetSplit',
                'params' => array(
                    'displayColumnName' => 'KITB Output Name',
                    'sqlColumnNameFull' => 'kitbOutput.name' ,
                    'sqlJoinClauses'    => array(
                        'JOIN kitbOutput ON (activity.idKitbOutput=kitbOutput.id)'
                    )
                )
            )
        )
    );
}

