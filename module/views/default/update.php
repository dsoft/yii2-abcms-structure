<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Structure */

$this->title = 'Update Structure: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="structure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
