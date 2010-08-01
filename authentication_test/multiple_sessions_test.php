<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "multiple_sessions_test.php"
*
* Project: Security.
*
* Purpose: Test the idea of accessing multiple sessions from one script.
*
* Author: Tom McDonnell 2010-06-30.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

    error_reporting(E_ALL ^ E_STRICT);


    session_name('session_one');
    session_start();
    $_SESSION = array();
    $_SESSION['session_one_var'] = 'test_one';
    $output1a =
    (
       "session_id(): '" . session_id() . "'.<br/>\n" .
       "session_name(): '" . session_name() . "'.<br/>\n" .
       print_r($_SESSION, true)
    );
    session_write_close();
    $_SESSION = array();
    
    
    session_name('session_two');
    session_id($_COOKIE['session_two']);
    session_start();
    $_SESSION = array();
    $_SESSION['session_two_var'] = 'test_two';
    $output2a =
    (
       "session_id(): '" . session_id() . "'.<br/>\n" .
       "session_name(): '" . session_name() . "'.<br/>\n" .
       print_r($_SESSION, true)
    );
    session_write_close();
    $_SESSION = array();

    session_name('session_one');
    session_id($_COOKIE['session_one']);
    session_start();
    $_SESSION['session_one_var_b'] = 'test_one_b';
    $output1b =
    (
       "session_id(): '" . session_id() . "'.<br/>\n" .
       "session_name(): '" . session_name() . "'.<br/>\n" .
       print_r($_SESSION, true)
    );
    session_write_close();
    $_SESSION = array();
    
    
    session_name('session_two');
    session_id($_COOKIE['session_two']);
    session_start();
    session_regenerate_id(true);
    $_SESSION['session_two_var_b'] = 'test_two_b';
    $output2b =
    (
       "session_id(): '" . session_id() . "'.<br/>\n" .
       "session_name(): '" . session_name() . "'.<br/>\n" .
       print_r($_SESSION, true)
    );
    session_write_close();
    $_SESSION = array();

    echo "$output1a<br/>\n<br/>\n$output2a";
    echo "<br/>\n<br/>\n";
    echo "$output1b<br/>\n<br/>\n$output2b";

/*******************************************END*OF*FILE********************************************/
?>
