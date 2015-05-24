<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\search\ContentMenuSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="content-menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'active') ?>

    <?= $form->field($model, 'has_submenu') ?>

    <?= $form->field($model, 'has_menu_image') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(ContentModule::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(ContentModule::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
