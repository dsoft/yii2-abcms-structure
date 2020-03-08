<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Structure */

$this->title = Yii::t('abcms.structure', 'Update Structure').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.structure', 'Structures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('abcms.structure', 'Update');
?>
<div class="structure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
