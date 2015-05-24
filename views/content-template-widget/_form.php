<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplateWidget $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>
<div class="content-template-widget-form">

	<?php $form = ActiveForm::begin([
		'id'=>'content-template-widget-form',
		'layout'=>'horizontal',
		'validateOnBlur'=>false,
	]); ?>

	<?= $form->field($model->loadDefaultValues(), 'active')->checkbox(['class'=>'b-switch'], false) ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autofocus'=>$model->isNewRecord ? true:false]) ?>

	<?= $form->field($model->loadDefaultValues(), 'single_per_page')->checkbox(['class'=>'b-switch'], false) ?>

	<?= $form->field($model, 'positionIds')->checkboxList($this->context->module->availableWidgetPositions) ?>

	<?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'widget_class')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'widget_options')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model->loadDefaultValues(), 'has_settings')->checkbox(['class'=>'b-switch'], false) ?>

	<?= $form->field($model, 'link_to_settings')->textInput(['maxlength' => 255]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php if ( $model->isNewRecord ): ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-plus-sign"></span> ' . Yii::t('app', 'Create'),
					['class' => 'btn btn-success']
				) ?>
			<?php else: ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-ok"></span> ' . Yii::t('app', 'Save'),
					['class' => 'btn btn-primary']
				) ?>
			<?php endif; ?>
			<?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class'=>'btn btn-default']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php BootstrapSwitch::widget() ?>