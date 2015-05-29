<?php

use webvimark\ybc\content\ContentModule;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplate $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>
<div class="content-template-form">

	<?php $form = ActiveForm::begin([
		'id'=>'content-template-form',
		'layout'=>'horizontal',
		'validateOnBlur'=>false,
	]); ?>

	<?= $form->field($model->loadDefaultValues(), 'active')->checkbox(['class'=>'b-switch'], false) ?>


	<?php if ( Yii::$app->user->isSuperadmin ): ?>
		<?= $form->field($model->loadDefaultValues(), 'can_be_deleted')->checkbox(['class'=>'b-switch'], false) ?>
	<?php endif; ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autofocus'=>$model->isNewRecord ? true:false]) ?>

	<?php if ( $model->isNewRecord ): ?>

		<?= $model->layoutSelector($form) ?>

	<?php endif; ?>


	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php if ( $model->isNewRecord ): ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-plus-sign"></span> ' . ContentModule::t('app', 'Create'),
					['class' => 'btn btn-success']
				) ?>
			<?php else: ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-ok"></span> ' . ContentModule::t('app', 'Save'),
					['class' => 'btn btn-primary']
				) ?>
			<?php endif; ?>
			<?= Html::a(ContentModule::t('app', 'Cancel'), ['index'], ['class'=>'btn btn-default']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php BootstrapSwitch::widget() ?>