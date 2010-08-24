<?php

/*
 * vim: ts=4 sw=4 et wrap co=100 go-=b
 */

class Application_Model_ActivityMapper extends Application_Model_BaseMapper
{
    public function __construct()
    {
        $this->_dbTableModel = 'Application_Model_DbTable_Activity';
    }

    public function save(Application_Model_Activity $activity)
    {
        $data = array(
            'activityTitle' => $activity->getActivityTitle(),
            'activityNo' => $activity->getActivityNo()
        );
        if (null === ($id = $activity->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id)
    {
        $activity = new Application_Model_Activity();

        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $activity->setId($row->id)
            ->setActivityNo($row->activityNo)
            ->setActivityTitle($row->activityTitle);

        return $activity;
    }

    protected function buildResultSet($resultSet)
    {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Activity();
            $entry->setId($row->id)
                ->setActivityNo($row->activityNo)
                ->setActivityTitle($row->activityTitle);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll()
    {
        return $this->buildResultSet($this->getDbTable()->fetchAll());
    }

    public function fetchByStaffId($idStaff)
    {
        $select = new Zend_Db_Table_Select($this->getDbTable());
        $select->setIntegrityCheck(false);
        $select->from('activity');
        $select->join(
            'link_activity_staff',
            'link_activity_staff.idActivity = activity.id',
            ''
        );
        $select->where("link_activity_staff.idStaff = :idStaff");
        $select->bind(array(
            ":idStaff" => $idStaff
        ));
        $select->order('activityTitle');

        return $this->buildResultSet($this->getDbTable()->fetchAll($select));
    }
}

