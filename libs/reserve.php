<?php
    include_once('libs/MBCurl.php');
    include_once('libs/Mail.php');

    if ($_SERVER["REQUEST_METHOD"] != "POST")
        return;

    function cleanInput($input) {
 
        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );
     
        $output = preg_replace($search, '', $input);
        return $output;
    }

    function sanitize($input) {
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = sanitize($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input  = cleanInput($input);
            $output = mysql_real_escape_string($input);
        }
        return $output;
    }

    //  Basic sanitization
    $_POST = sanitize($_POST);

    //  Per field sanitization and validation
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

    list($equipment_size, $equipment_type) = split('_', array_get($_POST, 'equipment', ''));

    if (!((in_array($equipment_type , array('caravan', 'camper', 'tent')) and in_array($equipment_size, array('s', 'm', 'l'))) or in_array($equipment_type, array('other')))) {
        $form_errors['equipment'] = 'Not valid';
    } else {
        $form_values['equipment'] = array_get($_POST, 'equipment', '');
    }

    if (count($form_errors) === 0) {
        $form_values['note'] = join(array(
            sprintf('Equipment %s', $form_values['equipment']),
            sprintf('People %s + %s', $form_values['adults'], $form_values['children']),
            array_get($_POST, 'note', '')
        ), " \r\n");

        try {
            $backend_post = array(
                'surname' => $form_values['surname'],
                'name' => $form_values['name'],
                'email' => $form_values['email'],
                'citizenship' => $form_values['citizenship'],

                'birthdate' => $form_values['birthdate'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'note' => $form_values['note'],
                'pitch' => $form_values['pitch'],

                'arrival' => $form_values['arrival'],
                'departure' => $form_values['departure'],
            );
            $backend_response = Utils::load_remote_json('https://127.0.0.1/backend/reserve/', $backend_post);
        } catch(Exception $ex) {
            return sprintf("500 Internal Server Error: %s \r\n", $ex);
        }

        /* this sould never happend but... double validation is better the single */
        if ($backend_response->{'success'} == TRUE) {
            return TRUE;
        } else {
            foreach ($backend_response->{'errors'} as $filed => $error) {
                $form_errors[$field] = $error;
            }
        }
    }

    $form_values['note'] = array_get($_POST, 'note', '');
    return FALSE; 
?>