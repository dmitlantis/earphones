<?php
require 'init.php';

$models = Model::query(QueryCriteria::create()->addLikeCondition('descr', '%7.1%'));
$props = Prop::query(QueryCriteria::create()->addInCondition('id', [3,5,6,8,9,15,16,17,18,33,36,39,58,59,66,53]));
//$props = Prop::query(QueryCriteria::create()->addParam('id', 114, '<'));
?>

<table>
    <tr>
        <th>Модель</th>
        <th>Диаметер</th>
        <th>Динамики</th>
        <th>Коннектор</th>
        <?php foreach ($props as $prop) { ?>
        <th><?= "$prop->name ($prop->id)" ?></th>
        <?php } ?>
    </tr>
    <?php foreach ($models as $model) { ?>
    <tr>
        <td><a href="<?= $model->getUrl() ?>"><?= $model->name ?></a></td>
        <td><?= $model->diameter ?></td>
        <td><?= $model->dinamics ?></td>
        <td><?= $model->connector ?></td>
        <?php foreach ($props as $index => $prop) { ?>
            <td><?= $model->getPropById($index)->value ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
