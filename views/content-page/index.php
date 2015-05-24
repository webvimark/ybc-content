<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentMenu;
use webvimark\ybc\content\models\ContentPage;
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
 * @var webvimark\ybc\content\models\search\ContentPageSearch $searchModel
 */

$this->title = ContentModule::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-page-index">

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
					<?= GridPageSize::widget(['pjaxId'=>'content-page-grid-pjax']) ?>
				</div>
			</div>


			<?php Pjax::begin([
				'id'=>'content-page-grid-pjax',
			]) ?>

			<?= GridView::widget([
				'id'=>'content-page-grid',
				'dataProvider' => $dataProvider,
				'pager'=>[
					'options'=>['class'=>'pagination pagination-sm'],
					'hideOnSinglePage'=>true,
					'lastPageLabel'=>'>>',
					'firstPageLabel'=>'<<',
				],

				'layout'=>'{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}'.GridBulkActions::widget(['gridId'=>'content-page-grid']).'</div></div>',

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
				'class'=>'webvimark\components\StatusColumn',
				'attribute'=>'is_main',
				'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'is_main', 'id'=>'_id_']),
			],
			'type',
			'slug',
			[
				'value'=>function(ContentPage $model){
						return Html::img($model->getImageUrl('small', 'menu_image'));
					},
				'contentOptions'=>['width'=>'10px'],
				'format'=>'raw',
			],
			[
				'attribute'=>'parent_id',
				'filter'=>ArrayHelper::map(ContentPage::find()->asArray()->all(), 'id', 'name'),
				'value'=>'parent.name',
			],
			[
				'attribute'=>'content_template_id',
				'filter'=>ArrayHelper::map(ContentTemplate::find()->asArray()->all(), 'id', 'name'),
				'value'=>'contentTemplate.name',
			],
			[
				'attribute'=>'content_menu_id',
				'filter'=>ArrayHelper::map(ContentMenu::find()->asArray()->all(), 'id', 'name'),
				'value'=>'contentMenu.name',
			],
			[
				'class'=>'webvimark\components\StatusColumn',
				'attribute'=>'active',
				'toggleUrl'=>Url::to(['toggle-attribute', 'attribute'=>'active', 'id'=>'_id_']),
			],
			['class' => 'webvimark\components\SorterColumn'],

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
