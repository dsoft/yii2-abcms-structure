<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Field */

$this->title = 'Create Field';
$this->params['breadcrumbs'][] = ['label' => 'Structures', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $structure->name, 'url' => ['default/view', 'id' => $structure->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
