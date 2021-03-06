<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use abcms\library\models\Model;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Structure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="structure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modelId')->dropDownList(Model::getList(), ['prompt' => Yii::t('abcms.structure', '--Select--')])->hint(Yii::t('abcms.structure','Choose the class name if the structure belongs to a certain class.')) ?>

    <?= $form->field($model, 'pk')->textInput()->hint(Yii::t('abcms.structure','Add the primary key of the model if the structure belongs to a specific model.')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('abcms.structure', 'Create') : Yii::t('abcms.structure', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
