<?php
    if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;

    include_once('libs/MBCurl.php');
    include_once('libs/Mail.php');

    function array_get(&$array, $name="", $fallback="") {
        return isset($array[$name]) ? $array[$name] : $fallback;
    }

    $form_errors = array();
    $form_values = array();

    $old_booking_code = array_get($_SESSION, 'booking_code', null);
    $booking_code = md5((microtime()).(rand(10000,65535)));

    $_SESSION['booking_code'] = $booking_code;

    session_commit();


    if (isset($_POST['booking_code']) and $old_booking_code !== null) {
        if($old_booking_code == $_POST['booking_code']) { // Valid request
            $booking_status = include('libs/reserve.php');
        } else { // Unvalid Request (code expired or not set)
            printf("<script>(function(){window.location.replace('/');})()</script>"); // (Clean browser POST history)
            printf('<a href="/" target="_self" class="span12 btn btn-warning">Plaese click here to continue</a>'); // No JS fallback
            return;
        }
    }

    try {
        //$options = $Utils::load_remote_json('https://backend.martin-dev.tk/backend/options/');
        $options = Utils::load_remote_json('https://127.0.0.1/backend/options/');
    } catch (Exception $e){
        $backend_error = TRUE;
        $admin_mail =  new Mail($e, "martin.brugnara@gmail.com", 'PHP@puntaindiana.it', 'connection error');
        if(! $admin_mail->send() ) {
            print("Unable to send mail to the admin.<br>");
        }
    }

    if(isset($backend_error)):
?>
    <div class="well">
        <h4><span class="text-error">Looks like something went wrong!</span></h4>
        <h5>
            We track these errors automatically, but if the problem persists feel free to contact us.</br>
            In the meantime, try refreshing.
        </h5>
        <center><em>Please try againg later.<em></center>
    </div>
<?php
        return;
    endif;

    if(isset($booking_status) and $booking_status !== TRUE and $booking_status !== FALSE):
?>
    <div class="well">
        <h4><span class="text-error">Looks like something went wrong  with your Reservation!</span></h4>
        <h5>
            We track these errors automatically, but if the problem persists feel free to contact us.</br>
        </h5>
        <center><em>Please try againg later.<em></center>
    </div>
<?php
        return;
    endif;

    if(isset($booking_status) and $booking_status === TRUE):
?>
    <div class="well">
        <h4><span class="text-success">Reservation completed successfully!</span></h4>
        <h5>
            We have sent you an email with your reservation data.</br>
            If it's not in your incoming mail please check the spam folder.
        </h5>
        <center><em>See you in summer!.<em></center>
    </div>
<?php
        return;
    endif;
?>

<form class="form-horizontal" action="/?page=booking" method="POST" id="booking" autocomplete="on">
    <input type="hidden" name="booking_code" value="<?=$booking_code?>"></input>
    <div class="row">
        <fieldset class="span6">
            <legend>Personal Information</legend>
            <div class="control-group <?= isset($form_errors['surname']) ? error : '' ?>">
                <label class="control-label" for="surname">Surname</label>
                <div class="controls">
                    <input type="text" name="surname" placeholder="Smith" class="span3" pattern="[a-zA-Z ]{2,255}" required value="<?=array_get($form_values, 'surname', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['name']) ? error : '' ?>">
                <label class="control-label" for="name">Name</label>
                <div class="controls">
                    <input type="text" name="name" placeholder="Alice" class="span3" pattern="[a-zA-Z ]{2,255}" required value="<?=array_get($form_values, 'name', '')?>" />  
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['birthdate']) ? error : '' ?>">
                <label class="control-label" for="birthdate">Birthdate</label>
                <div class="controls">
                    <input type="date" max="<?=date('Y-m-d', strtotime('-18 years')) ?>" name="birthdate"  class="span3" placeholde="yyy-mm-dd" required value="<?=array_get($form_values, 'birthdate', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['citizenship']) ? error : '' ?>">
                <label class="control-label" for="citizenship">Citizenship</label>
                <div class="controls">
                    <select name="citizenship" class="span3" required> 
                    <?php 
                        foreach ($options->{'citizenship'} as $id => $name) {
                            printf('<option value="%s" %s>%s</option>', 
                                $id, 
                                $id == array_get($form_values, 'citizenship', '') ? 'selected' : '',
                                $name);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['equipment']) ? error : '' ?>">
                <label class="control-label" for="equipment">Equipment</label>
                <div class="controls">
                    <?php /* TODO: find a way to loop and restore $form_value*/ ?>
                    <select name="equipment" class="span3" required value="<?=array_get($form_values, 'equipment', '')?>"> 
                        <optgroup label="Caravan">
                            <option value="s_caravan" <?=array_get($form_values, 'equipment', '') == 's_caravan' ? 'selected' : ''?>>caravan small</option>
                            <option value="m_caravan" <?=array_get($form_values, 'equipment', '') == 'm_caravan' ? 'selected' : ''?>>caravan medium</option>
                            <option value="l_caravan" <?=array_get($form_values, 'equipment', '') == 'l_caravan' ? 'selected' : ''?>>caravan large</option>
                        </optgrou>

                        <optgroup label="Camper">
                            <option value="s_camper" <?=array_get($form_values, 'equipment', '') == 's_camper' ? 'selected' : ''?>>camper small</option>
                            <option value="m_camper" <?=array_get($form_values, 'equipment', '') == 'm_camper' ? 'selected' : ''?>>camper medium</option>
                            <option value="l_camper" <?=array_get($form_values, 'equipment', '') == 'l_camper' ? 'selected' : ''?>>camper large</option>
                        </optgrou>

                        <optgroup label="Tent">
                            <option value="s_tent" <?=array_get($form_values, 'equipment', '') == 's_tent' ? 'selected' : ''?>>tent small</option>
                            <option value="m_tent" <?=array_get($form_values, 'equipment', '') == 'm_tent' ? 'selected' : ''?>>tent medium</option>
                            <option value="l_tent" <?=array_get($form_values, 'equipment', '') == 'l_tent' ? 'selected' : ''?>>tent large</option>
                        </optgrou>

                        <optgroup label="Other">
                            <option value="other" <?=array_get($form_values, 'equipment', '') == 'other' ? 'selected' : ''?>>other - specify in Note</option>
                        </optgeoup>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['email']) ? error : '' ?>">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <input type="email" name="email" placeholder="you@provider.domain" class="span3" required value="<?=array_get($form_values, 'email', '')?>" /> 
                </div>
            </div>
        </fieldset>

        <fieldset class="span6">
            <legend>Booking data</legend>
            <div class="control-group <?= isset($form_errors['arrival']) ? error : '' ?>">
                <label class="control-label" for="arrival">Arrival</label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="arrival"  placeholder="2014-mm-dd" class="span3" required value="<?=array_get($form_values, 'arrival', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['departure']) ? error : '' ?>">
                <label class="control-label" for="departure">Departure</label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="departure" placeholder="2014-mm-dd" class="span3" required value="<?=array_get($form_values, 'departure', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['pitch']) ? error : '' ?>">
                <label class="control-label" for="pitch">Fav. Pitch</label>
                <div class="controls">
                    <select name="pitch" class="span3" size="1" requierd> 
                    <?php 
                        $last_zone=null;
                        foreach ($options->{'pitch'} as $id => $desc) {
                            if($last_zone !== $desc->{'zone'}) {
                                if(!is_null($last_zone)){
                                    print('</optgroup>');
                                }
                                printf('<optgroup label="Zone %s" class="zone_%s">',
                                    $desc->{'zone'}, 
                                    $desc->{'zone'}
                                );
                                $last_zone = $desc->{'zone'};
                            }
                            printf('<option value="%s"class="zone_%s" %s>%s</option>', 
                                $id, 
                                $desc->{'zone'},
                                $id == array_get($form_values, 'pitch', '') ? 'selected' : '',
                                $desc->{'name'},
                                $desc->{'zone'}
                            );
                        }
                        print('</optgroup>');
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['adults']) ? error : '' ?>">
                <label class="control-label" for="adults">Adults</label>
                <div class="controls">
                    <select name="adults" class="span3" required> 
                    <?php 
                        foreach (range(1,10) as $_ => $v) {
                            printf('<option value="%s" %s>%s</option>', 
                                $v, 
                                $v == array_get($form_values, 'adults', '') ? 'selected' : '',
                                $v);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['children']) ? error : '' ?>">
                <label class="control-label" for="children">Children</label>
                <div class="controls">
                    <select name="children" class="span3" required value="<?=array_get($form_values, 'children', '0')?>"> 
                    <?php 
                        foreach (range(0,10) as $_ => $v) {
                            printf('<option value="%s" %s>%s</option>', 
                                $v, 
                                $v == array_get($form_values, 'children', '') ? 'selected' : '',
                                $v);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['email_again']) ? error : '' ?>">
                <label class="control-label" for="email_again">Email againg</label>
                <div class="controls">
                    <input type="email" name="email_again" placeholder="you@provider.domain" class="span3" required value="<?=array_get($form_values, 'email_again', '')?>" />
                </div>
            </div>
        </fieldset>
    </div>
    <fieldset>
        <legend>Note</legend>
        <div class="row">
            <div class="offset1 span10">
                <textarea name="note" wrap="soft" placeholder="Write here your note" maxlength="500"><?=array_get($form_values, 'note', '')?></textarea>
            </div>
        </div>
    </fieldset>
    <footer class="actions row">
        <center>
        <button type="reset" class="btn btn-inverse span6">Undo</button>
        <button type="submit" class="btn btn-success span6">Reserve!</button>
        </center>
    </footer>
</form>

    <script type="text/javascript" src="/static/js/booking.js"></script>
