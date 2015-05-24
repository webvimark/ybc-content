<?php

use webvimark\ybc\content\ContentModule;
use webvimark\modules\content\assets\SortableAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplate $model
 */

$this->title = Yii::t('app', 'Details of the') . " " . ContentModule::t('app', 'template') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::beginForm() ?>

<?= Html::hiddenInput('form-submitted', 1) ?>

<div class="row">
	<div class="col-sm-12">
		<div class="col-sm-2">
			<?= Html::submitButton(
				'<span class="glyphicon glyphicon-ok"></span> ' . Yii::t('app', 'Save'),
				['class' => 'btn btn-primary btn-lg']
			) ?>
		</div>
		<div class="col-sm-8">
			<div class="alert alert-info text-center">
				This is schematic page template. Hover mouse over blocks with <b>blue</b> border and you'll see "Add widget" button.
			</div>
		</div>
		<div class="col-sm-2 text-center">
			<?= Html::img($model->getLayoutImageFromAssets(), ['style'=>'max-height:100px']) ?>
		</div>
	</div>

</div>

<hr/>

<div class="content-template-view">

	<div class="content-template-builder col-sm-12">

		<?= $this->renderFile(Yii::getAlias('@app') . '/views/layouts/templates/'.$model->layout.'/backend_template.php') ?>

	</div>

</div>

<?= Html::endForm() ?>

<?php
Modal::begin([
	'options'=>[
		'class'=>'fire',
	],
	'header'=>ContentModule::t('app', 'Available widgets'),
	'id'=>'add-widget-modal',
]);
Modal::end();

$availableWidgetUrl = Url::to(['available-widgets', 'id'=>$model->id]);
$existingWidgetUrl = Url::to(['existing-widgets', 'id'=>$model->id]);

$addWidgetText = ContentModule::t('app', 'Add widget');

$js = <<<JS

var elementsWithWidgets = $('[data-position]');

elementsWithWidgets.each(function(){
	var _t = $(this);

	$.get('$existingWidgetUrl', { position: _t.data('position') })
		.success(function(data){
			_t.append(data);
			elementsWithWidgets.sortable();

		});
});


var addWidgetModal = $('#add-widget-modal');
var modalBody = addWidgetModal.find('.modal-body');


// Show or hide "Add widget" button
$('.content-template-builder [data-position]')
	.prepend('<div class="add-widget">$addWidgetText</div>')
	.on('mouseover', function(){
		$(this).find('.add-widget').show();
	})
	.on('mouseout', function(){
		$(this).find('.add-widget').hide();
	});

// Show modal popup and load available widget in it after clicking "Add widget"
$(document).on('click', '.add-widget', function(){
	var _t = $(this);
	_t.hide();

	modalBody.html('');
//	modalBody.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');

	addWidgetModal.modal();



	$.get('$availableWidgetUrl', { position: _t.closest('[data-position]').data('position') })
		.success(function(data) {
			modalBody.html(data);
		});
});

// Select widget in popup
$(document).on('click', '.select-widget-btn', function(){
	var _t = $(this);

	$('[data-position=' + _t.data('widget-position') + ']').prepend($('#' + _t.data('widget-uuid')).html());

	elementsWithWidgets.sortable();
});

// Remove widget
$(document).on('click', '.template-element .removeWidget', function(){

	$(this).closest('.widget-element').remove();

	elementsWithWidgets.sortable();
});
JS;

$css = <<<CSS
.sortable-placeholder {
	border: 1px dashed #CCC;
	background: none;
	width: 100%;
	height: 75px;
	margin-bottom: 20px;

}
CSS;

$this->registerCss($css);

$this->registerJs($js);

SortableAsset::register($this);
?>