<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentTemplateWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\ybc\content\models\search\ContentTemplateWidgetSearch $searchModel
 */

$this->title = ContentModule::t('app', 'Template widgets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-template-widget-index">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<div class="panel panel-default">
		<div class="panel-body">

			<div class="row">
				<div class="col-xs-6">
					<p>
						<?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ' . Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
					</p>
				</div>

				<div class="col-xs-6 text-right">
					<?= GridPageSize::widget(['pjaxId'=>'content-template-widget-grid-pjax']) ?>
				</div>
			</div>


			<?php Pjax::begin([
				'id'=>'content-template-widget-grid-pjax',
			]) ?>

			<?= GridView::widget([
				'id'=>'content-template-widget-grid',
				'dataProvider' => $dataProvider,
				'pager'=>[
					'options'=>['class'=>'pagination pagination-sm'],
					'hideOnSinglePage'=>true,
					'lastPageLabel'=>'>>',
					'firstPageLabel'=>'<<',
				],

				'layout'=>'{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}'.GridBulkActions::widget(['gridId'=>'content-template-widget-grid']).'</div></div>',

				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn', 'options'=>['style'=>'width:10px'] ],

					[
						'attribute'=>'name',
						'value'=>function($model){
								return Html::a($model->name, ['update', 'id'=>$model->id], ['data-pjax'=>0]);
							},
						'format'=>'raw',
					],
					[
						'attribute'=>'position',
						'filter'=>$this->context->module->availableWidgetPositions,
						'value'=>function(ContentTemplateWidget $model) {
								return $model->showNicePositions();
							},
						'format'=>'raw',
					],
					'widget_class',
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'single_per_page',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'single_per_page', 'id'=>'_id_']),
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'has_settings',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'has_settings', 'id'=>'_id_']),
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'active',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'active', 'id'=>'_id_']),
					],

					['class' => 'yii\grid\CheckboxColumn', 'options'=>['style'=>'width:10px'] ],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:70px; text-align:center;'],
					],
				],
			]); ?>
		
			<?php Pjax::end() ?>
		</div>
	</div>
</div>
