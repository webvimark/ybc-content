<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\search\ContentPageSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="content-page-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'active') ?>

    <?= $form->field($model, 'sorter') ?>

    <?= $form->field($model, 'is_main') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'menu_image') ?>

    <?php // echo $form->field($model, 'meta_title') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'body') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'content_template_id') ?>

    <?php // echo $form->field($model, 'content_menu_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(ContentModule::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(ContentModule::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
