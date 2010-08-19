<?php
/*
 * vim: ts=4 sw=4 et wrap co=100 go-=b
 */

//require_once dirname(__FILE__) . '/../../../library/Zend/Db/Adapter/Pdo/Mysql.php';
require_once dirname(__FILE__) . '/../../lib/zend_framework/library-1.10.7/Zend/Db/Adapter/Pdo/Mysql.php';
require_once dirname(__FILE__) . '/AdminActivityDataRowsRetriever.php';

/*
 *
 */
class AdminActivityCategoriesRetriever
{
    private $staffMapper;

    // Public functions. ///////////////////////////////////////////////////////////////////////

    /*
     *
     */
    public function __construct()
    {
/*
        $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'zend_user',
            'password' => 'zend@dpi' ,
            'dbname'   => 'time_and_effort'
        ));
*/
        $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'tom',
            'password' => 'igaiasma' ,
            'dbname'   => 'time_and_effort'
        ));
    }

    /*
     *
     */
    public function getChildCategoryInfoById($idParent)
    {
        $sqlSelectClause  = 'SELECT `id`, `name` FROM `adminactivitycategory`';
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
}

