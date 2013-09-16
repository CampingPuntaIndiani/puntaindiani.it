<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  // TODO: try catch for main srv down
  include_once('libs/MBCurl.php');
  //$options = $Utils::load_remote_json('https://backend.martin-dev.tk/backend/options/');
  $options = Utils::load_remote_json('https://127.0.0.1/backend/options/');

?>

<form class="form-horizontal" action="/?page=booking" method="POST" id="booking" autocomplete="on">
    <input type="hidden" name="reserve" value="1"></input>
    <div class="row">
        <fieldset class="span6">
            <legend>Personal Information</legend>
            <div class="control-group">
                <label class="control-label" for="surname">Surname</label>
                <div class="controls">
                    <input type="text" name="surname" placeholder="Smith" class="input-xlarge" pattern="[a-zA-Z ]{2,}"> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Name</label>
                <div class="controls">
                    <input type="text" name="name" placeholder="Alice" class="input-xlarge" pattern="[a-zA-Z ]{2,}">  
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="birthdate">Birthdate</label>
                <div class="controls">
                    <input type="date" max=" <?=date('Y-m-d', strtotime('-18 years')) ?>" name="birthdate"  class="input-xlarge"> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="citizenship">Citizenship</label>
                <div class="controls">
                    <select name="citizenship" class="input-xlarge"> 
                    <?php 
                        foreach ($options->{'citizenship'} as $id => $name) {
                            printf('<option value="%s">%s</option>', $id, $name);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="equipment">Equipment</label>
                <div class="controls">
                    <select name="equipment" class="input-xlarge"> 
                        <optgroup label="Caravan">
                            <option value="s_caravan">caravan small</option>
                            <option value="m_caravan">'- medium</option>
                            <option value="l_caravan">\- large</option>
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
            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <input type="email" name="email" placeholder="you@provider.domain" class="input-xlarge"> 
                </div>
            </div>
        </fieldset>

        <fieldset class="span6">
            <legend>Booking data</legend>
            <div class="control-group">
                <label class="control-label" for="arrival">Arrival</label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="arrival"  class="input-xlarge"> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="departure">Departure</label>
                <div class="controls">
                    <input type="date" min="<?=$options->{'opening'}?>" max="<?=$options->{'closure'}?>" name="departure"  class="input-xlarge"> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="pitch">Fav. Pitch</label>
                <div class="controls">
                    <select name="pitch" class="input-xlarge" size="1"> 
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
                            printf('<option value="%s"class="zone_%s">%s</option>', 
                                $id, 
                                $desc->{'zone'},
                                $desc->{'name'},
                                $desc->{'zone'}
                            );
                        }
                        print('</optgroup>');
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="adults">Adults</label>
                <div class="controls">
                    <select name="adults" class="input-xlarge"> 
                    <?php 
                        foreach (range(1,10) as $_ => $v) {
                            printf('<option value="%s">%s</option>', $v, $v);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="children">Children</label>
                <div class="controls">
                    <select name="children" class="input-xlarge"> 
                    <?php 
                        foreach (range(0,10) as $_ => $v) {
                            printf('<option value="%s">%s</option>', $v, $v);
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email_again">Email againg</label>
                <div class="controls">
                    <input type="email" name="email_again" placeholder="you@provider.domain" class="input-xlarge">
                </div>
            </div>
        </fieldset>
    </div>

    <div class="span1"><!-- padding, offset1 does not work (due to use of section instead of div?)... --></div>
    <textarea class="span10" name="note" wrap="soft" placeholder="Write here your note"></textarea>
    <div class="span1"><!-- padding, offset1 does not work... --></div>
</form>

    <script type="text/javascript" src="/static/js/booking.js"></script>

