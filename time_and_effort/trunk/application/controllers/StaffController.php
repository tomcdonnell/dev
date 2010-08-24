<?php

class StaffController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginFormAction()
    {
        // action body
        $request = $this->getRequest();

        $form = new Application_Form_Login();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                try {
                    $ldap = Zend_Registry::get("ldap");
                    $ldap->bind($request->getPost('soeid'), $request->getPost('password'));

                    $this->_helper->redirector('day-view', 'timesheet');
                } catch (Zend_Ldap_Exception $e) {
                    print_r($e->getMessage());
                }
            }
        }

        $this->view->form = $form;
    }

}

