<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use abcms\structure\models\Field;

/* @var $this yii\web\View */
/* @var $model abcms\structure\models\Field */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Field::getTypeList()) ?>
    
    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'hint')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'isRequired')->checkbox() ?>
    
    <?= $form->field($model, 'isTranslatable')->checkbox() ?>
    
    <?= $form->field($model, 'list')->textarea(['rows' => 6])->hint(Yii::t('abcms.structure', 'Used for drop-down lists, write each option on one line.')) ?>
    
    <?= $form->field($model, 'additionalData')->textarea(['rows' => 6])->hint(Yii::t('abcms.structure', 'JSON format.')); ?>
    
    <?= $form->field($model, 'ordering')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('abcms.structure', 'Create') : Yii::t('abcms.structure', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
