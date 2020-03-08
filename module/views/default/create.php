<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Structure */

$this->title = Yii::t('abcms.structure', 'Create Structure');
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.structure', 'Structures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
