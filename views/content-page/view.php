<?php

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentPage;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentPage $model
 */

switch ($model->type)
{
	case ContentPage::TYPE_INTERNAL_LINK:
		$pageTypeText = ContentModule::t('app', 'internal link');
		break;
	case ContentPage::TYPE_EXTERNAL_LINK:
		$pageTypeText = ContentModule::t('app', 'external link');
		break;
	default:
		$pageTypeText = ContentModule::t('app', 'text page');
}

$this->title = Yii::t('app', 'Details of the') . " " . $pageTypeText . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->contentMenu->name, 'url' => ['tree', 'menuId' => $model->content_menu_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-page-view">


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
						'attribute'=>'is_main',
						'value'=>($model->is_main == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					[
						'attribute'=>'open_in_new_tab',
						'value'=>($model->open_in_new_tab == 1) ?
								'<span class="label label-success">'.Yii::t('yii', 'Yes').'</span>' :
								'<span class="label label-warning">'.Yii::t('yii', 'No').'</span>',
						'format'=>'raw',
					],
					'type',
					'slug',
					[
						'attribute'=>'menu_image',
						'value'=>Html::img($model->getImageUrl('medium', 'menu_image')),
						'visible'=>is_file($model->getImagePath('medium', 'menu_image')),
						'format'=>'raw',
					],
					'meta_title',
					'meta_keywords',
					'meta_description',
					'body:raw',
					[
						'attribute'=>'parent_id',
						'value'=>@$model->parent->name,
					],
					[
						'attribute'=>'content_template_id',
						'value'=>@$model->contentTemplate->name,
					],
					[
						'attribute'=>'content_menu_id',
						'value'=>@$model->contentMenu->name,
					],
					'created_at:datetime',
					'updated_at:datetime',
				],
			]) ?>

		</div>
	</div>
</div>
