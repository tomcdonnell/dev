<?php

class Application_Model_LinkActivityStaffMapper extends Application_Model_BaseMapper
{

    public function __construct()
    {
        $this->_dbTableModel = 'Application_Model_DbTable_LinkActivtyStaff';
    }

    public function save(Application_Model_LinkActivityStaff $linkActivityStaff)
    {
        $data = array(
            'updated' => $linkActivityStaff->getUpdated(),
            'idActivity' => $linkActivityStaff->getIdActivity(),
            'idStaff' => $linkActivityStaff->getIdStaff(),
            'id' => $linkActivityStaff->getId(),
        );
        if (null === ($id = $linkActivityStaff->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_LinkActivityStaff $linkActivityStaff)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $linkActivityStaff->setUpdated($row->updated)
            ->setIdActivity($row->idActivity)
            ->setIdStaff($row->idStaff)
            ->setId($row->id);
    }

    protected function buildResultSet($resultSet)
    {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_LinkActivityStaff();
            $entry->setUpdated($row->updated)
                ->setIdActivity($row->idActivity)
                ->setIdStaff($row->idStaff)
                ->setId($row->id);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        return $this->buildResultSet($this->getDbTable()->fetchAll());
    }

    public function fetchLinkActivityStaffByStaffId($idStaff)
    {
        $params = array(
            "idStaff" => $idStaff
        );

        return $this->buildResultSet($this->getDbTable()->fetchAll($params));
    }

}

