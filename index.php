<?php
require 'init.php';

$models = Model::query(QueryCriteria::create()->param('origin','ogo')); //->param('diameter', 50, '>='));
//$models = Model::query(QueryCriteria::create(QueryCriteria::MERGE_MODE_OR)
//    ->where(QueryCriteria::create()->param('diameter', 52, '>=')
//        ->where(QueryCriteria::create(QueryCriteria::MERGE_MODE_OR)
//            ->like('descr', '%7.1%')
//            ->like('name', '%7.1%')
//            ->like('name', '%SURROUND%')
//            ->like('descr', '%SURROUND%')
//        )
//    )
//    ->where(QueryCriteria::create()->isNull('diameter')->regexp('connector', '(^|[^io])usb'))
//    ->sort('name')
//);
//$props = Prop::query(QueryCriteria::create()->IN('id', [3,5,6,8,9,15,16,17,18,33,36,39,58,59,66,53]));
$props = Prop::query(QueryCriteria::create());
$counter = 0;
$propsFiltered = [];
foreach ($props as $prop) {
    foreach ($models as $model) {
        if ($model->getPropById($prop->id)) {
            $propsFiltered[$prop->id] = $prop;
            break;
        }
    }
}


?>

<table>
    <tr>
        <th>#</th>
        <th>Platform</th>
        <th>Модель</th>
        <th>Диаметер</th>
        <th>Динамики</th>
        <th>Коннектор</th>
        <?php foreach ($propsFiltered as $prop) { ?>
        <th><?= "$prop->name ($prop->id)" ?></th>
        <?php } ?>
    </tr>
    <?php foreach ($models as $model) { ?>
    <tr>
        <td><?= ++$counter ?></td>
        <td><?= $model->origin ?></td>
        <td><a href="<?= $model->getUrl() ?>"><?= $model->name ?></a></td>
        <td><?= $model->diameter ?></td>
        <td><?= $model->dinamics ?></td>
        <td><?= $model->connector ?></td>
        <?php foreach ($propsFiltered as $prop) { ?>
            <td><?= $model->getPropById($prop->id)->value ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
