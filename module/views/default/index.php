<?php

use yii\helpers\Html;
use yii\grid\GridView;
use abcms\library\models\Model;

/* @var $this yii\web\View */
/* @var $searchModel abcms\structure\module\models\StructureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('abcms.structure', 'Structures');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structure-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('abcms.structure', 'Create Structure'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'attribute' => 'modelId',
                'value' => function($data){
                    return $data->modelName;
                },
                'filter' => Model::getList(),
            ],
            'pk',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
