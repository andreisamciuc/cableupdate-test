<?php
/*
 * Vonaq Cable Guardian
 * Server time changer add-on
 * 18.08.2014
 * 
 * Armagan Corlu aka Psy_chip
 * psychip.net
 * elance.com/s/psychip
 * 
 * contact:
 * root@psychip.net
 */

//error_reporting(0);
$path = $_SERVER['REQUEST_URI'];
$file = basename(__FILE__);
$date = trim(date("j M Y"));
$time = trim(date("G:i"));
$variables = array(
    'date' => 'pdn_dateval',
    'time' => 'pdn_timeval',
);

if (isset($_GET['set'])) {
    foreach ($variables as $key => $value) {
        if (!array_key_exists($value, $_POST)) {
            die("Parameter Empty");
        }
    }
    $exec_file = shell_exec("sudo -u root /var/www/settime.sh '" . trim($_POST[$variables["date"]]) . "' '" . trim($_POST[$variables['time']]) . "'");


    //  shell_exec('sudo date --set "' .  . ' ' .  . ':00"');
    die(json_encode(array('r' => 'ok')));
}
?>

<input type="text" autocorrect="off" autocomplete="off" id="<?php echo $variables['date']; ?>" value="<?php echo $date; ?> " name="<?php echo $variables['date']; ?>" onKeyPress="return pdntime.handlekey(event)" />
&nbsp;
<input type="text" autocorrect="off" autocomplete="off" id="<?php echo $variables['time']; ?>" value="<?php echo $time; ?> " name="<?php echo $variables['time']; ?>" onKeyPress="return pdntime.handlekey(event)" />
&nbsp;
<a href="#" onclick="javascript:pdntime.submit();">Set</a>
&nbsp;|&nbsp;
<a href="#" onclick="javascript:pdntime.done();">Cancel</a>

<script type="text/javascript">
    $("#<?php echo $variables['date']; ?>").datepicker({
        dateFormat: "d M yy"
    });
    $('#<?php echo $variables['time']; ?>').timepicker({});
</script>
