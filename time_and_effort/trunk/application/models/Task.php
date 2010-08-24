<?php

class Application_Model_Task extends Application_Model_Base
{

    public $id;
    public $updated;
    public $deleted;
    public $taskTitle;
    public $idActivity;
    public $idTaskType;
    public $desc;
    protected $activityMapper;

    public function __construct(array $options = null)
    {
        parent::__construct($options);

        $this->activityMapper = new Application_Model_ActivityMapper();
    }

    public function getActivityTitle()
    {
        return $this->activityMapper->find($this->idActivity)->getActivityTitle();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setTaskTitle($taskTitle)
    {
        $this->taskTitle = $taskTitle;
        return $this;
    }

    public function getTaskTitle()
    {
        return $this->taskTitle;
    }

    public function setIdActivity($actId)
    {
        $this->idActivity = $actId;
        return $this;
    }

    public function getIdActivity()
    {
        return $this->idActivity;
    }

    public function setIdTaskType($taskTypeId)
    {
        $this->idTaskType = $taskTypeId;
        return $this;
    }

    public function getIdTaskType()
    {
        return $this->idTaskType;
    }

    public function setDesc($desc)
    {
        $this->desc = (string) $desc;
        return $this;
    }

    public function getDesc()
    {
        return $this->desc;
    }

}

