<?php

class ActivityController extends Zend_Controller_Action
{
    protected $activityMapper = null;
    protected $idStaff;

    public function init()
    {
        /* Initialize action controller here */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->initContext();

        $this->idStaff = Zend_Registry::get("idStaff");
        $this->activityMapper = new Application_Model_ActivityMapper();
    }

    public function indexAction()
    {
        // action body
    }

    public function getAllActivitiesAction()
    {
        // action body
        $this->view->activityList = $this->activityMapper->fetchAll();
    }

    public function getActivityByStaffIdAction()
    {
        // action body
        $this->view->activityList = $this->activityMapper->fetchByStaffId($this->idStaff);
    }

}

