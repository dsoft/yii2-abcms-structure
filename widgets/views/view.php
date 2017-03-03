<?php

use yii\widgets\DetailView;
?>
<?php if($title): ?>
    <h2><?= $title ?></h2>
<?php endif; ?>
<?php
echo DetailView::widget([
    'model' => [],
    'attributes' => $attributes,
]);
?>
