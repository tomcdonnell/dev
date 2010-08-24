<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body

     //   $this->_helper->redirector('login-form', 'staff');

        $this->_helper->redirector('day-view', 'timesheet');
    }

}



