<?php
    if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;

    Utils::load_dict();

    include_once('libs/pwd.inc.php');
    include_once('libs/MBCurl.php');
    include_once('libs/Mail.php');

    function array_get(&$array, $name="", $fallback="") {
        if (is_object($array))
            return isset($array->{$name}) ? $array->{$name} : $fallback;
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
        $options = Utils::load_remote_json($GLOBALS['backend_url'].'/options/');
    } catch (Exception $e){
        $backend_error = TRUE;
        $admin_mail =  new Mail($e, "martin.brugnara@gmail.com", 'PHP@puntaindiana.it', 'connection error');
        if(! $admin_mail->send() ) {
            print("Unable to send mail to the admin.<br>");
        }
    }

    if(isset($backend_error)){
        $message_type = 'error';
        $message_title = 'Looks like something went wrong!';
        $message_body = join(array(
            'We track these errors automatically, but if the problem persists feel free to contact us.',
            'In the meantime, try refreshing.'), '<br>');
        $message_footer = 'Please try again later.';
    } else if(isset($booking_status) and $booking_status !== TRUE and $booking_status !== FALSE){
        $message_type = 'error';
        $message_title = 'Looks like something went wrong  with your Reservation!';
        $message_body = 'We track these errors automatically, but if the problem persists feel free to contact us.';
        $message_footer = 'Please try again later.';
    } else if(isset($booking_status) and $booking_status === TRUE){
        $message_type = 'success';
        $message_title = 'Reservation completed successfully!';
        $message_body = join(array(
            'We have sent you an email with your reservation data.',
            'If it\'s not in your incoming mail please check the spam folder.'
            ), '<br>');
        $message_footer = 'See you in summer!';
    }
    if (isset($message_type)): 
?>
        <div class="well">
            <h4><span class="text-<?=$message_type?>"><?=$message_title?></span></h4>
            <h5><?=$message_body?></h5>
            <center><em><?=$message_footer?></em></center>
        </div>
<?php
        return;
    endif;
?>

<blockquote>
    <h5><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->TeC_label?></h5>
    <?=join($GLOBALS['dict']->page->{$_SESSION['lang']}->booking_disclaimer, '<br>')?>
</blockquote>

<form class="form-horizontal" action="/?page=booking" method="POST" id="booking" autocomplete="on">
    <input type="hidden" name="booking_code" value="<?=$booking_code?>"></input>
    <div class="row">
        <fieldset class="span6">
            <legend>Personal Information</legend>
            <div class="control-group <?= isset($form_errors['surname']) ? error : '' ?>">
            <label class="control-label" for="surname"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->surname?></label>
                <div class="controls">
                    <input type="text" name="surname" placeholder="Smith" class="span3" pattern="[a-zA-Z ]{2,255}" required value="<?=array_get($form_values, 'surname', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['name']) ? error : '' ?>">
                <label class="control-label" for="name"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->name?></label>
                <div class="controls">
                    <input type="text" name="name" placeholder="Alice" class="span3" pattern="[a-zA-Z ]{2,255}" required value="<?=array_get($form_values, 'name', '')?>" />  
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['birthdate']) ? error : '' ?>">
                <label class="control-label" for="birthdate"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->birthdate?></label>
                <div class="controls">
                    <input type="date" max="<?=date('Y-m-d', strtotime('-18 years')) ?>" name="birthdate"  class="span3" placeholde="yyy-mm-dd" required value="<?=array_get($form_values, 'birthdate', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['citizenship']) ? error : '' ?>">
                <label class="control-label" for="citizenship"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->citizenship?></label>
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
                <label class="control-label" for="equipment"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->equipement?></label>
                <div class="controls">
                    <select name="equipment" class="span3" required value="<?=array_get($form_values, 'equipment', '')?>"> 
                        <optgroup label="<?=$GLOBALS['dict']->page->{$_SESSION['lang']}->caravan?>">
                            <option value="s_caravan" <?=array_get($form_values, 'equipment', '') == 's_caravan' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->caravan?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->small?></option>
                            <option value="m_caravan" <?=array_get($form_values, 'equipment', '') == 'm_caravan' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->caravan?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->medium?></option>
                            <option value="l_caravan" <?=array_get($form_values, 'equipment', '') == 'l_caravan' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->caravan?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->large?></option>
                        </optgrou>

                        <optgroup label="<?=$GLOBALS['dict']->page->{$_SESSION['lang']}->camper?>">
                            <option value="s_camper" <?=array_get($form_values, 'equipment', '') == 's_camper' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->camper?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->small?></option>
                            <option value="m_camper" <?=array_get($form_values, 'equipment', '') == 'm_camper' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->camper?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->medium?></option>
                            <option value="l_camper" <?=array_get($form_values, 'equipment', '') == 'l_camper' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->camper?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->large?></option>
                        </optgrou>

                        <optgroup label="<?=$GLOBALS['dict']->page->{$_SESSION['lang']}->tent?>">
                            <option value="s_tent" <?=array_get($form_values, 'equipment', '') == 's_tent' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->tent?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->small?></option>
                            <option value="m_tent" <?=array_get($form_values, 'equipment', '') == 'm_tent' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->tent?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->medium?></option>
                            <option value="l_tent" <?=array_get($form_values, 'equipment', '') == 'l_tent' ? 'selected' : ''?>><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->tent?> <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->large?></option>
                        </optgrou>

                        <optgroup label="<?=$GLOBALS['dict']->page->{$_SESSION['lang']}->other?>">
                            <option value="other" <?=array_get($form_values, 'equipment', '') == 'other' ? 'selected' : ''?>>other - specify in Note</option>
                        </optgeoup>
                    </select>
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['email']) ? error : '' ?>">
                <label class="control-label" for="email"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->email?></label>
                <div class="controls">
                    <input type="email" name="email" placeholder="you@provider.domain" class="span3" required value="<?=array_get($form_values, 'email', '')?>" /> 
                </div>
            </div>
        </fieldset>

        <fieldset class="span6">
            <legend>Booking data</legend>
            <div class="control-group <?= isset($form_errors['arrival']) ? error : '' ?>">
                <label class="control-label" for="arrival"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->arrival?></label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="arrival"  placeholder="2014-mm-dd" class="span3" required value="<?=array_get($form_values, 'arrival', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['departure']) ? error : '' ?>">
                <label class="control-label" for="departure"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->departure?></label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="departure" placeholder="2014-mm-dd" class="span3" required value="<?=array_get($form_values, 'departure', '')?>" /> 
                </div>
            </div>
            <div class="control-group <?= isset($form_errors['pitch']) ? error : '' ?>">
                <label class="control-label" for="pitch"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->fav_pitch?></label>
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
                <label class="control-label" for="adults"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->adults?></label>
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
                <label class="control-label" for="children"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->children?></label>
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
                <label class="control-label" for="email_again"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->email_again?></label>
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
        <button type="reset" class="btn btn-inverse span6"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->reset?></button>
        <button type="submit" class="btn btn-success span6"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->reserve?></button>
        </center>
    </footer>
</form>

    <script type="text/javascript" src="/static/js/booking.js"></script>
