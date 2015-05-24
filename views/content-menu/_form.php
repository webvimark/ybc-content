<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentMenu $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>
<div class="content-menu-form">

	<?php $form = ActiveForm::begin([
		'id'=>'content-menu-form',
		'layout'=>'horizontal',
		'validateOnBlur'=>false,
	]); ?>

	<?= $form->field($model->loadDefaultValues(), 'active')->checkbox(['class'=>'b-switch'], false) ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autofocus'=>$model->isNewRecord ? true:false]) ?>

	<?= $form->field($model, 'positionIds')->checkboxList($this->context->module->availableWidgetPositions) ?>

	<?= $form->field($model->loadDefaultValues(), 'has_submenu')->checkbox(['class'=>'b-switch'], false) ?>

	<?= $form->field($model->loadDefaultValues(), 'has_menu_image')->checkbox(['class'=>'b-switch'], false) ?>

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