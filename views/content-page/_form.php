<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentMenu;
use webvimark\ybc\content\models\ContentPage;
use webvimark\ybc\content\models\ContentTemplate;
use webvimark\behaviors\multilanguage\input_widget\MultiLanguageActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use webvimark\extensions\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentPage $model
 * @var yii\bootstrap\ActiveForm $form
 * @var string $menuName
 * @var int $menuId
 * @var boolean $hasMenuImage
 */
?>
<div class="content-page-form">

	<?php $form = ActiveForm::begin([
		'id'=>'content-page-form',
//		'layout'=>'horizontal',
		'validateOnBlur'=>false,
		'options'=>[
			'enctype'=>"multipart/form-data",
		]
	]); ?>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs">
		<li class="active"><a href="#page-content-main"  data-toggle="tab">
				<?= ContentModule::t('app', 'Base parameters') ?>
		</a></li>

		<?php if ( $model->type != ContentPage::TYPE_EXTERNAL_LINK ): ?>
			<li><a href="#page-content-seo" data-toggle="tab">SEO</a></li>

		<?php endif; ?>
		<li><a href="#page-content-additional" data-toggle="tab">
				<?= ContentModule::t('app', 'Additional parameters') ?>
		</a></li>
	</ul>

	<br/>
	<div class="tab-content">
		<div class="tab-pane active" id="page-content-main">

			<div class="row">
				<div class="col-lg-4">
					<?= $form->field($model, 'name')
						->textInput(['maxlength' => 255, 'autofocus'=>$model->isNewRecord ? true:false])
						->widget(MultiLanguageActiveField::className()) ?>

				</div>

				<?php if ( $model->type == ContentPage::TYPE_EXTERNAL_LINK ): ?>
					<div class="col-lg-8">
						<?php if ( count(Yii::$app->params['mlConfig']['languages']) > 1 ): ?>
							<br/><br/>

						<?php endif; ?>

						<?= $form->field($model, 'slug', ['options'=>['class'=>'required']])
							->textInput(['maxlength' => 255])
							->label(ContentModule::t('app', 'External link')) ?>
					</div>

				<?php else: ?>

					<div class="col-lg-4">
						<?php if ( count(Yii::$app->params['mlConfig']['languages']) > 1 ): ?>
							<br/><br/>

						<?php endif; ?>

						<?php if ( $model->type == ContentPage::TYPE_INTERNAL_LINK ): ?>
							<?= $form->field($model, 'slug', ['options'=>['class'=>'required']])
								->dropDownList($model->getInternalLinks(), ['prompt'=>''])
								->label(ContentModule::t('app', 'Internal link')) ?>

						<?php else: ?>
							<?= $form->field($model, 'slug', ['enableClientValidation'=>false])->textInput(['maxlength' => 255]) ?>

						<?php endif; ?>

					</div>

					<?php if ( Yii::$app->getModule('content')->enableTemplates ): ?>

						<div class="col-lg-4">
							<?php if ( count(Yii::$app->params['mlConfig']['languages']) > 1 ): ?>
								<br/><br/>

							<?php endif; ?>

							<?php
							$templates = ContentTemplate::find()->active()->asArray()->all();
							if ( count($templates) == 1 AND !$model->content_template_id )
							{
								$model->content_template_id = $templates[0];
							}
							?>

							<?= $form->field($model, 'content_template_id', ['options'=>['class'=>'required']])
								->dropDownList(
									ArrayHelper::map($templates, 'id', 'name'),
									['prompt'=>'']
								) ?>

						</div>

					<?php endif; ?>


				<?php endif; ?>

			</div>

			<?php if ( $model->type == ContentPage::TYPE_TEXT ): ?>
				<?= $form->field($model, 'body', ['enableClientValidation'=>false, 'enableAjaxValidation'=>false])
					->textarea(['rows' => 6, 'class'=>'form-control ck-text'])
					->widget(MultiLanguageActiveField::className(), ['inputType'=>'textArea', 'inputOptions'=>[
						'rows'=>6,
						'class'=>'form-control ck-text',
					]]) ?>

				<?php CKEditor::widget([
					'type'           => CKEditor::TYPE_STANDARD,
					'height'         => '320px',
					'replaceByClass' => 'ck-text',
				]) ?>
			<?php endif; ?>



			<div class="form-group">
				<div class="col-sm-offset-3_ col-sm-9_">
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
					<?= Html::a(Yii::t('app', 'Cancel'), ['tree', 'menuId'=>$model->content_menu_id], ['class'=>'btn btn-default']) ?>
				</div>
			</div>

		</div>

		<?php if ( $model->type != ContentPage::TYPE_EXTERNAL_LINK ): ?>

			<div class="tab-pane" id="page-content-seo">


				<?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255])->widget(MultiLanguageActiveField::className()) ?>

				<?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255])->widget(MultiLanguageActiveField::className()) ?>

				<?= $form->field($model, 'meta_description')->textarea(['maxlength' => 255, 'rows'=>3])
					->widget(MultiLanguageActiveField::className(), ['inputType'=>'textArea', 'inputOptions'=>[
						'rows'=>3,
						'class'=>'form-control',
					]]) ?>

			</div>
		<?php endif; ?>



		<div class="tab-pane" id="page-content-additional">

			<div class="row">
				<div class="col-sm-6">

					<?php if ( $model->is_main != 1 || $model->isNewRecord ): ?>

						<?= $form->field($model->loadDefaultValues(), 'active', [
							'template' => '<div class="row"><div class="col-sm-6 text-right" style="margin-top: 5px">{label}</div><div class="col-sm-6">{input}{error}{hint}</div></div>'
						])->checkbox(['class'=>'b-switch'], false) ?>

						<?php if ( $model->type == ContentPage::TYPE_TEXT ): ?>

							<?= $form->field($model->loadDefaultValues(), 'is_main', [
								'template' => '<div class="row"><div class="col-sm-6 text-right" style="margin-top: 5px">{label}</div><div class="col-sm-6">{input}{error}{hint}</div></div>'
							])->checkbox(['class'=>'b-switch'], false) ?>

						<?php endif; ?>


					<?php else: ?>
						<div class="alert alert-info text-center">
							<?= ContentModule::t('app', 'Main page') ?>
						</div>
					<?php endif; ?>



					<?= $form->field($model->loadDefaultValues(), 'open_in_new_tab', [
						'template' => '<div class="row"><div class="col-sm-6 text-right" style="margin-top: 5px">{label}</div><div class="col-sm-6">{input}{error}{hint}</div></div>'
					])->checkbox(['class'=>'b-switch'], false) ?>


				</div>
				<div class="col-sm-6">


					<?= $form->field($model, 'content_menu_id')
						->dropDownList(
							ArrayHelper::map(ContentMenu::find()->asArray()->all(), 'id', 'name'),
							['prompt'=>'']
						) ?>

					<?php if ( $hasMenuImage ): ?>
						<?php if ( ! $model->isNewRecord AND is_file($model->getImagePath('medium', 'menu_image'))): ?>
							<div class='form-group'>
								<div class='col-sm-3'></div>
								<div class='col-sm-6'>
									<?= Html::img($model->getImageUrl('medium', 'menu_image'), ['alt'=>'menu_image']) ?>
								</div>
							</div>
						<?php endif; ?>

						<?= $form->field($model, 'menu_image', ['enableClientValidation'=>true, 'enableAjaxValidation'=>false])->fileInput(['class'=>'form-control']) ?>

					<?php endif; ?>
				</div>
			</div>


		</div>
	</div>


	<?php ActiveForm::end(); ?>

</div>

<?php BootstrapSwitch::widget() ?>
