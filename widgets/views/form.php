<?php

/* @var $this yii\web\View */
/* @var $fields abcms\structure\models\Field[] */
/* @var $form yii\widgets\ActiveForm */
/* @var $dynamicModel yii\base\DynamicModel */
?>

<?php if($title && $titleTag): ?>
    <<?= $titleTag ?>><?= $title ?></<?= $titleTag ?>>
<?php endif; ?>

<?php foreach($fields as $field): ?>
    <?= $field->renderActiveField($form->field($dynamicModel, $field->name)); ?>
<?php endforeach; ?>
