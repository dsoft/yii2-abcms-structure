<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Field */

$this->title = 'Update Field: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Structures', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $structure->name, 'url' => ['default/view', 'id' => $structure->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
