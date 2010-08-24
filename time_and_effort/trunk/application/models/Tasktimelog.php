<?php

class Application_Model_Tasktimelog extends Application_Model_Base
{
    public $id;
    public $updated;
    public $deleted;
    public $date;
    public $idStaff;
    public $idTask;
    public $hours;
    public $comment;

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

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setIdStaff($idStaff)
    {
        $this->idStaff = $idStaff;
        return $this;
    }

    public function getIdStaff()
    {
        return $this->idStaff;
    }

    public function setIdTask($idTask)
    {
        $this->idTask = $idTask;
        return $this;
    }

    public function getIdTask()
    {
        return $this->idTask;
    }

    public function setHours($hours)
    {
        $this->hours = $hours;
        return $this;
    }

    public function getHours()
    {
        return $this->hours;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

}

