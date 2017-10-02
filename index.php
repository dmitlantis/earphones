<?php
require 'init.php';

$models = Model::fetchAll();
$props = Prop::fetchByIds([3,5,6,8,9,15,16,17,18,33,36,39,58,59,66,53]);

?>

<table>
    <tr>
        <th>Модель</th>
        <?php foreach ($props as $prop) { ?>
        <th><?= "$prop->name ($prop->id)" ?></th>
        <?php } ?>
    </tr>
    <?php foreach ($models as $model) { ?>
    <tr>
        <td><a href="http://mvideo.ru<?= $model->url_mvideo ?>"><?= $model->name ?></a></td>
        <?php foreach ($props as $index => $prop) { ?>
            <td><?= $model->getPropById($index)->value ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
