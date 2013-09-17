<?php
    /* They should yet be laoded*/
    include_once('libs/MBCurl.php');
    include_once('libs/Mail.php');

    if ($_SERVER["REQUEST_METHOD"] != "POST")
        return;

    $form_errors = array();
    $form_values = array();


    if (array_get($_POST, 'email', '') !== array_get($_POST, 'email_again', '')) {
        $form_errors['email_again'] = 'Emails have to be the same';
    } else {
        $form_values['email_again'] = $_POST['email_again'];
    }
    
    if (($form_values['email'] = filter_var(array_get($_POST, 'email', ''), FILTER_VALIDATE_EMAIL)) === FALSE) {
        $form_errors['email'] = 'Not valid';
    } if(strlen($form_values['email']) < 4) {
        $form_errors['email'] = 'Required';
    }

    if (($form_values['name'] = filter_var(array_get($_POST, 'name', ''),
        FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_ENCODE_HIGH )) === FALSE) {
        $form_errors['name'] = 'Not valid';
    } else if (strlen($form_values['name']) < 2) {
        $form_errors['name'] = 'Required';
    }

    if (($form_values['surname'] = filter_var(array_get($_POST, 'surname', ''),
        FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_ENCODE_HIGH ))  === FALSE) {
        $form_errors['surname'] = 'Not valid';
    } else if (strlen($form_values['surname']) < 2) {
        $form_errors['surname'] = 'Required';
    }

    if (($arrival_sec=strtotime(array_get($_POST, 'arrival', null))) === FALSE) {
        $form_errors['arrival'] = 'Not valid';
    } else {
        $form_values['arrival'] = $_POST['arrival'];
    }

    if (($departure_sec=strtotime(array_get($_POST, 'departure', null))) === FALSE) {
        $form_errors['departure'] = 'Not valid';
    } else {
        $form_values['departure'] = $_POST['departure'];
    }

    if ($arrival_sec !== FALSE and $departure_sec !== FALSE and $arrival_sec > $departure_sec) {
        $form_errors['name'] = $form_errors['departure'] = 'You sould arrive before leaving.';
    }

    if (strtotime(array_get($_POST, 'birthdate', null)) === FALSE) {
        $form_errors['birthdate'] = 'Not valid';
    } else {
        $form_values['birthdate'] = $_POST['birthdate'];
    }

    if (($form_values['citizenship']  = filter_var(array_get($_POST, 'citizenship', null), 
        FILTER_SANITIZE_NUMBER_INT)) === FALSE) {
        $form_errors['citizenship'] = 'Not valid';
    }

    if (($form_values['pitch']  = filter_var(array_get($_POST, 'pitch', null), 
        FILTER_SANITIZE_NUMBER_INT)) === FALSE) {
        $form_errors['pitch'] = 'Not valid';
    }

    if (($form_values['adults']  = filter_var(array_get($_POST, 'adults', null), 
        FILTER_SANITIZE_NUMBER_INT)) === FALSE) {
        $form_errors['adults'] = 'Not valid';
    }

    if (($form_values['children']  = filter_var(array_get($_POST, 'children', null), 
        FILTER_SANITIZE_NUMBER_INT)) === FALSE) {
        $form_errors['children'] = 'Not valid';
    }

    /* TODO: Write validation for equipment */

    /* TODO: sanitize, validate note */
    /* TODO: extend date with addon data */

    /* TODO: Send to backend and parse answer */

    return FALSE; 
?>