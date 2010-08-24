<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile('js/admin/NavPanelManager.js');
        $this->view->headScript()->appendFile('js/admin/main.js');
        $this->view->headScript()->appendFile('js/tom/contrib/utils/DomBuilder.js');
        $this->view->headScript()->appendFile('js/tom/contrib/utils/json.js');
        $this->view->headScript()->appendFile('js/tom/gui_elements/selectors/ExplorableTable.js');
        $this->view->headScript()->appendFile('js/tom/gui_elements/other/InteractiveTableHeadingsRow.js');
        $this->view->headScript()->appendFile('js/tom/utils/utils.js');
        $this->view->headScript()->appendFile('js/tom/utils/utilsDOM.js');
        $this->view->headScript()->appendFile('js/tom/utils/utilsObject.js');
        $this->view->headScript()->appendFile('js/tom/utils/utilsValidator.js');

        $this->view->headLink()->appendStylesheet('css/admin/style.css');
        $this->view->headLink()->appendStylesheet('css/admin/explorable_table.css');

        $this->view->rootCategoryNameById = $this->_getRootCategoryNameById();
    }

    // Private functions. //////////////////////////////////////////////////////////////////////

    /*
     *
     */
    function _getRootCategoryNameById()
    {
        // TODO
        // ----
        // Find a better way to get a database connection.
        // Username and password should only be entered once in code, and not here.
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'zend_user',
            'password' => 'zend@dpi' ,
            'dbname'   => 'time_and_effort'
        ));

        $rows = $db->fetchAll(
            'SELECT `id`, `name`, `sqlWhereCondition`, `sqlJoinClause`
             FROM `adminActivityCategory`
             WHERE `idParent` IS NULL'
        );

        $rootCategoryNameById = array();

        foreach ($rows as $row) {
            if ($row['sqlWhereCondition'] != '' || $row['sqlJoinClause'] != '') {
                throw new Exception(
                    'Root rows of the `adminActivityCategory` table (those with `idParent`=NULL)' .
                    ' should have blank values for `sqlWhereCondition` and `sqlJoinClause`.'
                );
            }

            $rootCategoryNameById[$row['id']] = $row['name'];
        }

        return $rootCategoryNameById;
    }
}

