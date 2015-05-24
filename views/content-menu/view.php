<?php

use webvimark\ybc\content\ContentModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentMenu $model
 */

$this->title = Yii::t('app', 'Details of the') . " " . ContentModule::t('app', 'menu') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Manage menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-menu-view">


	<div class="panel panel-default">
		<div class="panel-body">

			<p>
				<?= Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
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
						'attribute'=>'has_submenu',
						'value'=>($model->has_submenu == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					[
						'attribute'=>'has_menu_image',
						'value'=>($model->has_menu_image == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					'created_at:datetime',
					'updated_at:datetime',
				],
			]) ?>

		</div>
	</div>
</div>
