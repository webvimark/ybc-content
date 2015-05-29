<?php

use webvimark\ybc\content\ContentModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplateWidget $model
 */

$this->title = Yii::t('app', 'Details of the') . " " . ContentModule::t('app', 'template widget') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Template widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-template-widget-view">


	<div class="panel panel-default">
		<div class="panel-body">

			<p>
				<?= Html::a(ContentModule::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
				<?= Html::a(ContentModule::t('app', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
				<?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
					'class' => 'btn btn-sm btn-danger pull-right',
					'data' => [
						'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					[
						'attribute'=>'active',
						'value'=>($model->active == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					'name',
					[
						'attribute'=>'position',
						'value'=>$model->showNicePositions(),
						'format'=>'raw',
					],
					[
						'attribute'=>'single_per_page',
						'value'=>($model->single_per_page == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					'widget_class',
					[
						'attribute'=>'widget_options',
						'format'=>'raw',
						'value'=>'<pre>' . var_export(@unserialize($model->widget_options), true) . '</pre>',
					],
					[
						'attribute'=>'has_settings',
						'value'=>($model->has_settings == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					'link_to_settings',
					'created_at:datetime',
					'updated_at:datetime',
				],
			]) ?>

		</div>
	</div>
</div>
