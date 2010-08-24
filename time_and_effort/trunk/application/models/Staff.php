<?php
/*
 * vim: ts=4 sw=4 et wrap co=100
 */

class Application_Model_Staff
{
    public $identifier;
    public $first_name;
    public $last_name;
    public $preferred_name;

    // Getters. ////////////////////////////////////////////////////////////////////////////////

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function getPreferred_name()
    {
        return $this->preferred_name;
    }

    // Setters. ////////////////////////////////////////////////////////////////////////////////

    public function setIdentifier($int)
    {
        $this->identifier = $int;
        return $this;
    }

    public function setFirst_name($text)
    {
        $this->first_name = $text;
        return $this;
    }

    public function setLast_name($text)
    {
        $this->identifier = $text;
        return $this;
    }

    public function setPreferred_name($text)
    {
        $this->identifier = $text;
        return $this;
    }
}

