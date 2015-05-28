<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentMenu;
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
 * @var webvimark\ybc\content\models\search\ContentMenuSearch $searchModel
 */

$this->title = ContentModule::t('app', 'Manage menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-menu-index">

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
					<?= GridPageSize::widget(['pjaxId'=>'content-menu-grid-pjax']) ?>
				</div>
			</div>


			<?php Pjax::begin([
				'id'=>'content-menu-grid-pjax',
			]) ?>

			<?= GridView::widget([
				'id'=>'content-menu-grid',
				'dataProvider' => $dataProvider,
				'pager'=>[
					'options'=>['class'=>'pagination pagination-sm'],
					'hideOnSinglePage'=>true,
					'lastPageLabel'=>'>>',
					'firstPageLabel'=>'<<',
				],

				'layout'=>'{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}'.GridBulkActions::widget(['gridId'=>'content-menu-grid']).'</div></div>',

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
						'attribute'=>'code',
						'visible'=>Yii::$app->user->isSuperadmin,
					],
					[
						'attribute'=>'position',
						'filter'=>Yii::$app->getModule('content')->availableWidgetPositions,
						'value'=>function(ContentMenu $model) {
								return $model->showNicePositions();
							},
						'format'=>'raw',
						'visible'=>Yii::$app->getModule('content')->enableTemplates,
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'has_submenu',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'has_submenu', 'id'=>'_id_']),
					],
					[
						'class'=>'webvimark\components\StatusColumn',
						'attribute'=>'has_menu_image',
						'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'has_menu_image', 'id'=>'_id_']),
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
