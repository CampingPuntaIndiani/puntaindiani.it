<?php
    if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
    // TODO: try catch for main srv down
    include_once('libs/MBCurl.php');
    include_once('libs/Mail.php');

    function array_get(&$array, $name="", $fallback="") {
        return isset($array[$name]) ? $array[$name] : $fallback;
    }

    $form_errors = array();
    $form_values = array();

    if (isset($_POST['reserve'])) {
        if (include_once('libs/reserve.php')){
            return; // All went good.
        }// otherwise the errors messages will be rendered
    }

    var_dump($form_values);
    var_dump($form_errors);


    try {
        //$options = $Utils::load_remote_json('https://backend.martin-dev.tk/backend/options/');
        $options = Utils::load_remote_json('https://127.0.0.1/backend/options/');
    } catch (Exception $e){
        $error = TRUE;
        $admin_mail =  new Mail($e, "martin.brugnara@gmail.com", 'PHP@puntaindiana.it', 'connection error');
        if(! $admin_mail->send() ) {
            print("Unable to send mail to the admin.<br>");
        }
    }

    if(isset($error)):
?>
    <div class="well">
        <h4><span class="text-error">Looks like something went wrong!</span></h4>
        <h5>
            We track these errors automatically, but if the problem persists feel free to contact us.</br>
            In the meantime, try refreshing.
        <h5>
        <center><em>Please try againg later.<em></center>
    </div>
<?php
        return;
    endif;
?>

<form class="form-horizontal" action="/?page=booking" method="POST" id="booking" autocomplete="on">
    <input type="hidden" name="reserve" value="1"></input>
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
                    <input type="date" max=" <?=date('Y-m-d', strtotime('-18 years')) ?>" name="birthdate"  class="span3" placeholde="yyy-mm-dd" required value="<?=array_get($form_values, 'birthdate', '')?>" /> 
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
                            <option value="s_caravan">caravan small</option>
                            <option value="m_caravan">caravan medium</option>
                            <option value="l_caravan">caravan large</option>
                        </optgrou>

                        <optgroup label="Camper">
                            <option value="s_camper">camper small</option>
                            <option value="m_camper">camper medium</option>
                            <option value="l_camper">camper large</option>
                        </optgrou>

                        <optgroup label="Tent">
                            <option value="s_tent">tent small</option>
                            <option value="m_tent">tent medium</option>
                            <option value="l_tent">tent large</option>
                        </optgrou>

                        <optgroup label="Other">
                            <option value="other">other - specify in Note</option>
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

