<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Category $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'slug') ?>
<?= $form->field($model, 'sort_order') ?>
<?= $form->field($model, 'is_active')->checkbox() ?>

<?= Html::submitButton('Save') ?>

<?php ActiveForm::end() ?>
