<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'soeid', array(
            'label' => 'SOE Id:',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 5))
            )
        ));

        // Add the comment element
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login',
        ));


        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }

}

