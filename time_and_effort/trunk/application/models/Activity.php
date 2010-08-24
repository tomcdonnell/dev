<?php

class Application_Model_Activity extends Application_Model_Base
{
    public $activityTitle;
    public $activityNo;
    public $id;

    public function setActivityTitle($title)
    {
        $this->activityTitle = (string) $title;
        return $this;
    }

    public function getActivityTitle()
    {
        return $this->activityTitle;
    }

    public function setActivityNo($no)
    {
        $this->activityNo = (string) $no;
        return $this;
    }

    public function getActivityNo()
    {
        return $this->activityNo;
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
}

