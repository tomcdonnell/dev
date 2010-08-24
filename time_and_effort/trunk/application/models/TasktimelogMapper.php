<?php

class Application_Model_TasktimelogMapper extends Application_Model_BaseMapper
{

    public function __construct()
    {
        $this->_dbTableModel = 'Application_Model_DbTable_Tasktimelog';
    }

    public function save(Application_Model_Tasktimelog $taskTimeLog)
    {
        $data = array(
            'date' => $taskTimeLog->getDate(),
            'deleted' => $taskTimeLog->getDeleted(),
            'idStaff' => $taskTimeLog->getIdStaff(),
            'idTask' => $taskTimeLog->getIdTask(),
            'hours' => $taskTimeLog->getHours(),
            'comment' => $taskTimeLog->getComment()
        );

        if (null === ($id = $taskTimeLog->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
            $taskTimeLog->setId($this->getDbTable()->getAdapter()->lastInsertId());
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $taskTimeLog;
    }

    public function find($id)
    {
        $taskTimeLog = new Application_Model_Tasktimelog();

        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $taskTimeLog->setId($row->id)
            ->setUpdated($row->updated)
            ->setDeleted($row->deleted)
            ->setDate($row->date)
            ->setIdStaff($row->idStaff)
            ->setIdTask($row->idTask)
            ->setHours($row->hours)
            ->setComment($row->comment);

        return $taskTimeLog;
    }

    protected function buildResultSet($resultSet)
    {

        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Tasktimelog();
            $entry->setId($row->id)
                ->setUpdated($row->updated)
                ->setDeleted($row->deleted)
                ->setDate($row->date)
                ->setIdStaff($row->idStaff)
                ->setIdTask($row->idTask)
                ->setHours($row->hours)
                ->setComment($row->comment);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        return $this->buildResultSet($this->getDbTable()->fetchAll());
    }

    public function fetchByStaffByDate($idStaff, $date)
    {
        $select = new Zend_Db_Table_Select($this->getDbTable());
        $select->where("idStaff = :idStaff");
        $select->where("date = :date");
        $select->where("deleted= :deleted");
        $select->bind(array(
            ":idStaff" => $idStaff,
            ":date" => $date,
            ":deleted" => 0
        ));

        return $this->buildResultSet($this->getDbTable()->fetchAll($select));
    }

}

