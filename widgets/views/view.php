<?php
use yii\widgets\DetailView;
?>

<?php if($title && $titleTag): ?>
    <<?= $titleTag ?>><?= $title ?></<?= $titleTag ?>>
<?php endif; ?>
<?php
echo DetailView::widget([
    'model' => [],
    'attributes' => $attributes,
]);
?>
