<?php

class TaskController extends Zend_Controller_Action
{
    protected $taskMapper = null;

    public function init()
    {
        /* Initialize action controller here */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->initContext();

        $this->taskMapper = new Application_Model_TaskMapper();
    }

    public function indexAction()
    {
        // action body
    }

    public function getAllTasksAction()
    {
        // action body
        $this->view->taskList = $this->taskMapper->fetchAll();
    }

    public function getTasksByActivityIdAction()
    {
        // action body

        $test = $this->getRequest()->getParam("ghethtrsh");

        $activityId = $this->getRequest()->getParam("idActivity");
        $this->view->taskList = $this->taskMapper->fetchByActivityId($activityId);
    }
}
