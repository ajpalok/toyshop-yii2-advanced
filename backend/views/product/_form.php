<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Product $model
 */

use common\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'category_id')->dropDownList(
    ArrayHelper::map(Category::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
) ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'slug') ?>
<?= $form->field($model, 'sku') ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'sale_price') ?>
<?= $form->field($model, 'stock') ?>
<?= $form->field($model, 'min_age') ?>
<?= $form->field($model, 'max_age') ?>
<?= $form->field($model, 'is_featured')->checkbox() ?>
<?= $form->field($model, 'status')->dropDownList([
    \common\models\Product::STATUS_DRAFT => 'Draft',
    \common\models\Product::STATUS_ACTIVE => 'Active',
]) ?>

<?= Html::submitButton('Save') ?>

<?php ActiveForm::end() ?>
