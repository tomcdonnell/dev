<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

class Application_Model_StaffMapper
{
    public function __construct()
    {
        $this->_dbTableModel = 'Application_Model_DbTable_Staff';
    }

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }

        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }

        $this->_dbTable = $dbTable;

        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Staff');
        }
        return $this->_dbTable;
    }
 
    public function find($identifier, Application_Model_Staff $staff)
    {
        $result = $this->getDbTable()->find($identifier);

        if (0 == count($result)) {
            return;
        }

        $row = $result->current();

        $staff->setIdentifier($row->identifier)
            ->setFirst_name($row->first_name)
            ->setLast_name($row->last_name)
            ->setPreferred_name($row->preferred_name);
    }

    protected function buildResultSet($resultSet)
    {
        $entries = array();

        foreach ($resultSet as $row) {
            $entry = new Application_Model_Staff();
            $entry->setIdentifier($row->identifier)
                ->setFirst_name($row->first_name)
                ->setPreferred_name($row->preferred_name);
            $entries[] = $entry;
        }

        return $entries;
    }

    public function fetchAll()
    {
        return $this->buildResultSet($this->getDbTable()->fetchAll());
    }

    public function fetchByStaff($idStaff, $date)
    {
        $params = array(
            'identifier' => $identifier
        );

        $timeLogs = $this->getDbTable()->fetchAll($params);

        return $this->buildResultSet($timeLogs);
    }

    public function getPreferredNamesFull()
    {
        $table  = $this->getDbTable();
        $select = new Zend_Db_Table_Select($table);
        $select->from($table, array('preferred_name', 'last_name'));
        //$select->where('preferred_name LIKE "a%"');
        $select->order('preferred_name ASC');
        $select->order('last_name ASC');
        $rows   = $table->fetchAll($select);

        $preferredNamesFull = array();
        foreach ($rows as $row) {
            $preferredNamesFull[] = "{$row['preferred_name']} {$row['last_name']}";
        }

        return $preferredNamesFull;
    }

    public function getPreferredNamesFullOfManagers()
    {
        $staff = $this->getDbTable()->fetchAll(array());

        return $this->buildResultSet($timeLogs);
    }
}

