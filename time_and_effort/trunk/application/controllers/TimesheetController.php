<?php
/*
 * vim: ts=4 sw=4 et nowrap go+=b
 */

class TimesheetController extends Zend_Controller_Action
{
    protected $sqlDate = null;
    protected $taskTimeLogMapper = null;
    protected $activityMapper = null;
    protected $taskMapper = null;
    protected $ajaxContent = null;

    public function init()
    {
        /* Initialize action controller here */

        $this->ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->ajaxContext->initContext();

        $date = new Zend_Date();

        $this->view->params = $this->getRequest()->getParams();
        $this->view->idStaff = Zend_Registry::get("idStaff");

        ($this->getRequest()->getParam('y') ? $date->setYear($this->getRequest()->getParam('y')) : "");
        ($this->getRequest()->getParam('m') ? $date->setMonth($this->getRequest()->getParam('m')) : "");
        ($this->getRequest()->getParam('d') ? $date->setDay($this->getRequest()->getParam('d')) : "");

        $this->view->date = $date->toString('EEEE, d MMMM YYYY');
        $this->view->sqlDate = $date->toString('YYYY-MM-dd');

        $this->taskTimeLogMapper = new Application_Model_TasktimelogMapper();
        $this->activityMapper = new Application_Model_ActivityMapper();
        $this->taskMapper = new Application_Model_TaskMapper();

        $this->view->headTitle('DPI Time & Effort System');
    }

    public function indexAction()
    {
        // action body
    }

    public function weekViewAction()
    {
        // action body
        $this->view->headScript()->appendFile('/js/timesheetWeek.js');
        $this->view->headLink()->appendStylesheet('/css/timesheetWeek.css');
    }

    public function dayViewAction()
    {
        // action body
        $this->view->headScript()->appendFile('/js/timesheetDay.js');
        $this->view->headLink()->appendStylesheet('/css/timesheetDay.css');

        $timeLogs = $this->taskTimeLogMapper
                ->fetchByStaffByDate(Zend_Registry::get("idStaff"), $this->view->sqlDate);

        $timeLogData = array();
        foreach ($timeLogs as $timelog) {
            $task = $this->taskMapper->find($timelog->idTask);
            $activity = $this->activityMapper->find($task->idActivity);

            $timeLogData[] = array(
                "timelog" => $timelog,
                "taskTitle" => $task->taskTitle,
                "activityTitle" => $activity->activityTitle,
                "activityNo" => $activity->activityNo,
                "activityId" => $activity->id
            );
        }

        $this->view->timeLogs = $timeLogData;
    }

    public function saveTimeLogAction()
    {
        // action body
        $taskTimeLog = new Application_Model_Tasktimelog();
        $taskTimeLog->setId($this->getRequest()->getParam("id"));
        $taskTimeLog->setDate($this->getRequest()->getParam("date"));
        $taskTimeLog->setIdStaff($this->getRequest()->getParam("idStaff"));
        $taskTimeLog->setIdTask($this->getRequest()->getParam("idTask"));
        $taskTimeLog->setDate($this->getRequest()->getParam("date"));
        $taskTimeLog->setDeleted($this->getRequest()->getParam("deleted"));
        $taskTimeLog->setHours($this->getRequest()->getParam("hours"));
        $taskTimeLog->setComment($this->getRequest()->getParam("comment"));

        $savedTimeLog = $this->taskTimeLogMapper->save($taskTimeLog);

        $this->view->saveResult = array("success" => true, "timelog" => $savedTimeLog);
    }

    public function setDeletedAction()
    {
        // action body
        $taskTimeLog = $this->taskTimeLogMapper->find($this->getRequest()->getParam("taskId"));
        $taskTimeLog->setDeleted((integer) $this->getRequest()->getParam("deleted"));
        $this->taskTimeLogMapper->save($taskTimeLog);

        $this->view->saveResult = array("success" => true, "timelog" => $taskTimeLog);
    }
}

