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
            # We have no DB connection at that point.
            # So mysql_real_escape_string is not available
            #$output = mysql_real_escape_string($input);
            $output = mres($input);
        }
        return $output;
    }
    function mres($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
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

    if (strtotime(array_get($_POST, 'birth_date', null)) === FALSE) {
        $form_errors['birth_date'] = 'Not valid';
    } else {
        $form_values['birth_date'] = $_POST['birth_date'];
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

    if (!((in_array($equipment_type , array('caravan', 'camper', 'tent')) and in_array($equipment_size, array('s', 'm', 'l'))) or in_array($equipment_size, array('other')))) {
        $form_errors['equipment'] = 'Not valid';
    } else {
        $form_values['equipment'] = array_get($_POST, 'equipment', '');
    }

    $form_values['with_pet'] = isset($_POST['with_pet']) ? 1 : 0;

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

                'birth_date' => $form_values['birth_date'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'note' => $form_values['note'],
                'pitch' => $form_values['pitch'],

                'arrival' => $form_values['arrival'],
                'departure' => $form_values['departure'],

                'with_pet' => $form_values['with_pet']
            );
            $backend_response = Utils::load_remote_json($GLOBALS['backend_url'].'reserve/', $backend_post);
        } catch(Exception $ex) {
            return sprintf("500 Internal Server Error: %s \r\n", $ex);
        }

        /* this sould never happend but... double validation is better the single */
        if ($backend_response->{'success'} == TRUE) {
            function i18n_email(&$dict, $lang_key) {
                $keys = array('reservation_code', 'client_id', 'surname', 'name',
                    'birth_date', 'citizenship', 'arrival', 'departure',
                    'fav_pitch', 'with_pet', 'note');
                $max_length = 0;
                foreach($keys as $_ => $key){
                    $max_length = max($max_length, 
                        strlen(array_get($GLOBALS['dict']->page->{$lang_key}, $key, $key)),
                        strlen(array_get($dict, $key, $key)));
                }
                $mail_text_array = array();
                $format_string = sprintf('%%-%ds:%%s', $max_length+4);
                foreach($keys as $_ => $key) {
                    array_push($mail_text_array, sprintf(
                        $format_string,
                        array_get($GLOBALS['dict']->page->{$lang_key}, $key, $key),
                        array_get($dict, $key, $key)));
                }
                return $mail_text_array;
            }

            $mail_data = $backend_post;
            unset($mail_data['ip']);
            unset($mail_data['pitch']);
            $mail_data['citizenship'] = $backend_response->citizenship;
            $mail_data['fav_pitch'] = $backend_response->fav_pitch;
            $mail_data['reservation_code'] = $backend_response->reservation_id;
            $mail_data['client_id'] = $backend_response->client_id;

            $guest_mail = new Mail(
                join($GLOBALS['dict']->page->{$_SESSION['lang']}->booking_mail + i18n_email($mail_data, $_SESSION['lang']), "\r\n"),
                $form_values['email'],
                'info@campingpuntaindiani.it',
                sprintf('Reservation: %s', $backend_response->reservation_id));
            $guest_send_status = $guest_mail->send();
            $mail_data['guest_mail'] = $guest_send_status;
            $office_mail = new Mail(
                join(i18n_email($mail_data, 'eng'),"\r\n"),
                'info@campingpuntaindiani.it',
                $form_values['email'],
                sprintf('wres: %s', $backend_response->reservation_id));
            $office_mail->send();
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
