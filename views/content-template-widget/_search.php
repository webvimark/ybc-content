<?php

use webvimark\ybc\content\ContentModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\search\ContentTemplateWidgetSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="content-template-widget-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'active') ?>

    <?= $form->field($model, 'single_per_page') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'widget_class') ?>

    <?php // echo $form->field($model, 'widget_options') ?>

    <?php // echo $form->field($model, 'has_settings') ?>

    <?php // echo $form->field($model, 'link_to_settings') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(ContentModule::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(ContentModule::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
