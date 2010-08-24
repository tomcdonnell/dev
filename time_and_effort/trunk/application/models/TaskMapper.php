<?php

class Application_Model_TaskMapper extends Application_Model_BaseMapper
{

    public function __construct()
    {
        $this->_dbTableModel = 'Application_Model_DbTable_Task';
    }

    public function save(Application_Model_Task $task)
    {
        $data = array(
            'id' => $task->getId(),
            'updated' => $task->getUpdated(),
            'deleted' => $task->getDeleted(),
            'taskTitle' => $task->getTaskTitle(),
            'idActivity' => $task->getIdActivity(),
            'idTaskType' => $task->getIdTaskType(),
            'desc' => $task->getDesc()
        );

        if (null === ($id = $task->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id)
    {
        $task = new Application_Model_Task();

        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $task->setId($row->id)
            ->setUpdated($row->updated)
            ->setDeleted($row->deleted)
            ->setTaskTitle($row->taskTitle)
            ->setIdActivity($row->idActivity)
            ->setIdTaskType($row->idTaskType)
            ->setDesc($row->desc);

        return $task;
    }

    protected function buildResultSet($resultSet)
    {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Task();
            $entry->setId($row->id)
                ->setUpdated($row->updated)
                ->setDeleted($row->deleted)
                ->setTaskTitle($row->taskTitle)
                ->setIdActivity($row->idActivity)
                ->setIdTaskType($row->idTaskType)
                ->setDesc($row->desc);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        return $this->buildResultSet($this->getDbTable()->fetchAll());
    }

    public function fetchByActivityId($activityId)
    {
        $select = new Zend_Db_Table_Select($this->getDbTable());
        $select->setIntegrityCheck(false);
        $select->where("idActivity = :idActivity");
        $select->bind(array(
            ":idActivity" => $activityId
        ));
        $select->order('taskTitle');

        return $this->buildResultSet($this->getDbTable()->fetchAll($select));
    }

}

