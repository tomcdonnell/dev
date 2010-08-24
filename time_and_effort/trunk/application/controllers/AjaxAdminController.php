<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

require_once dirname(__FILE__) . '/../views/helpers/AdminActivityCategoriesRetriever.php';
require_once dirname(__FILE__) . '/../views/helpers/AdminActivityDataRowsRetriever.php';

class AjaxAdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $this->view->ajaxResponse = array('Test' => 'This is a test.');
    }

    public function browseAction()
    {
        if (!array_key_exists('idCategory', $_POST)) {
            throw new Exception('Expected key not present in $_POST array.');
        }

        switch ($_POST['action'])
        {
            case 'getColumnHeadings':
                $retriever   = new AdminActivityDataRowsRetriever();
                $returnArray = $retriever->getColumnHeadings($_POST['idCategory']);
                break;

            case 'getChildCategoriesInfo':
                $retriever   = new AdminActivityCategoriesRetriever();
                $returnArray = $retriever->getChildCategoryInfoById($_POST['idCategory']);
                break;

            case 'getDataRowsForCategory':
                $retriever   = new AdminActivityDataRowsRetriever();
                $returnArray = $retriever->getDataRowsForCategory($_POST['idCategory']);
                break;

            default:
                throw new Exception("Unknown action '{$_POST['action']}'.");
        }

        $this->view->ajaxResponse = array('action' => $_POST['action'], 'reply' => $returnArray);
    }
}

/*******************************************END*OF*FILE********************************************/
?>
