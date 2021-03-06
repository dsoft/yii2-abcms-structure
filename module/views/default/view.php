<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Structure */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.structure', 'Structures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('abcms.structure', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'modelId',
                'value' => $model->modelName,
            ],
            'pk',
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('abcms.structure', 'Add Field'), ['field/create', 'structureId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'type',
            'label',
            'isRequired:boolean',
            'isTranslatable:boolean',
            'ordering',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}', 'controller' => 'field'],
        ],
    ]);
    ?>
    
    
</div>
