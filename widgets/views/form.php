<?php if($title): ?>
    <h2><?= $title ?></h2>
<?php endif; ?>

<?php foreach($fields as $field): ?>
    <?= $field->renderField() ?>
<?php endforeach; ?>
