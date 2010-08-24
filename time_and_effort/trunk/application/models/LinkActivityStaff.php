<?php

class Application_Model_LinkActivityStaff extends Application_Model_Base
{

    public $updated;
    public $idActivity;
    public $idStaff;
    public $id;

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setIdActivity($idActivity)
    {
        $this->idActivity = $idActivity;
        return $this;
    }

    public function getIdActivity()
    {
        return $this->idActivity;
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

