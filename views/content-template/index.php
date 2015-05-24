<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentTemplate;
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
 * @var webvimark\ybc\content\models\search\ContentTemplateSearch $searchModel
 */

$this->title = ContentModule::t('app', 'Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-template-index">

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
					<?= GridPageSize::widget(['pjaxId'=>'content-template-grid-pjax']) ?>
				</div>
			</div>


			<?php Pjax::begin([
				'id'=>'content-template-grid-pjax',
			]) ?>

			<?= GridView::widget([
				'id'=>'content-template-grid',
				'dataProvider' => $dataProvider,
				'pager'=>[
					'options'=>['class'=>'pagination pagination-sm'],
					'hideOnSinglePage'=>true,
					'lastPageLabel'=>'>>',
					'firstPageLabel'=>'<<',
				],

				'layout'=>'{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}'.GridBulkActions::widget(['gridId'=>'content-template-grid']).'</div></div>',

			'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn', 'options'=>['style'=>'width:10px'] ],

					[
						'attribute'=>'name',
						'value'=>function($model){
								return Html::a($model->name, ['view', 'id'=>$model->id], ['data-pjax'=>0]);
							},
						'format'=>'raw',
					],
					[
						'attribute'=>'layout',
						'value'=>function(ContentTemplate $model){
								return Html::a(
									Html::img($model->getLayoutImageFromAssets(), [
										'style'=>'max-height:200px; max-width:300px !important',
									]),
									['view', 'id'=>$model->id], ['data-pjax'=>0]
								);
							},
						'options'=>['style'=>'text-align:center'],
						'format'=>'raw',
					],
					[
						'value'=>function($model){
								return Html::tag('span', ContentModule::t('app', 'Copy') . ' <i class="fa fa-copy"></i>', [
									'class'=>'btn btn-default btn-sm clone-btn',
									'data-clone-id'=>$model->id,
								]);
							},
						'options'=>['width'=>'10px'],
						'format'=>'raw',
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'can_be_deleted',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'can_be_deleted', 'id'=>'_id_']),
						'visible'=>Yii::$app->user->isSuperadmin,
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'active',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'active', 'id'=>'_id_']),
					],

					['class' => 'yii\grid\CheckboxColumn', 'options'=>['style'=>'width:10px'] ],
					[
						'class' => 'yii\grid\ActionColumn',
						'buttons'=>[
							'delete'=>function($url, ContentTemplate $model){
									if ( $model->can_be_deleted != 1 )
									{
										return '';
									}
									else
									{
										return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
											'title' => Yii::t('yii', 'Delete'),
											'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
											'data-method' => 'post',
											'data-pjax' => '0',
										]);
									}
								},
						],
						'contentOptions'=>['style'=>'width:70px; text-align:center;'],
					],
				],
			]); ?>
		
			<?php Pjax::end() ?>
		</div>
	</div>
</div>

<?php
$url = Url::to(['clone-template']);

$js = <<<JS
$(document).on('click', '.clone-btn', function(){
	$.get('$url', { id: $(this).data('clone-id') }).done(function(){
		$.pjax.reload({container: '#content-template-grid-pjax'});
	})
});
JS;

$this->registerJs($js);
?>