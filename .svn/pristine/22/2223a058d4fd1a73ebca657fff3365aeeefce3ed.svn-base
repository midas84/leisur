<?php
define('IN_CB', true);
include('header.php');

$default_value['setChecksum'] = '';
$setChecksum = isset($_POST['setChecksum']) ? $_POST['setChecksum'] : $default_value['setChecksum'];
registerImageKey('setChecksum', $setChecksum);
registerImageKey('code', 'BCGcode39');

?>

<ul id="specificOptions">
    <li class="option">
        <div class="title">
            <label for="setChecksum">Checksum</label>
        </div>
        <div class="value">hola
            <?php echo getCheckboxHtml('setChecksum', $setChecksum, array('value' => 1)); ?>
        </div>
    </li>
</ul>



<?php
include('footer.php');
?>