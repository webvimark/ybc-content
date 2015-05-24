<?php
/**
 * @var $this yii\web\View
 * @var $menuName string
 * @var $pages ContentPage[]
 * @var $menuId int|null
 * @var $hasSubmenu int
 */
use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentPage;
use webvimark\extensions\jqtreewidget\JQTreeWidget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $menuName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">

	<div class="panel-body">

		<?= ButtonDropdown::widget([
			'label'=>'<span class="glyphicon glyphicon-plus-sign"></span> ' . Yii::t('app', 'Create'),
			'options'=>['class'=>'btn btn-success btn-sm'],
			'encodeLabel'=>false,
			'dropdown'=>[
				'items' => [
					[
						'label' => ContentModule::t('app', 'Text page'),
						'url'   => Url::to([
								'create',
								'type'   => ContentPage::TYPE_TEXT,
								'menuId' => $menuId,
							])
					],
					[
						'label' => ContentModule::t('app', 'Internal link'),
						'url'   => Url::to([
								'create',
								'type'   => ContentPage::TYPE_INTERNAL_LINK,
								'menuId' => $menuId,
							])
					],
					[
						'label' => ContentModule::t('app', 'External link'),
						'url'   => Url::to([
								'create',
								'type'   => ContentPage::TYPE_EXTERNAL_LINK,
								'menuId' => $menuId,
							])
					],
				],
			],

		]) ?>
		<?= JQTreeWidget::widget([
			'models'        => $pages,
			'modelName'     => 'webvimark\ybc\content\models\ContentPage',
			'parentIdField' => 'parent_id',
			'statusField'   => 'active',
			'orderField'    => 'sorter',
			'showExpandAndCollapse'=>false,
			'withChildren'  => $hasSubmenu === 1,
			'leafName'      => function (ContentPage $model) {
					$pageName = $model->is_main == 1 ? '<span style="color:green; font-style: italic;">'.$model->name.'</span>' : $model->name;

					if ( $model->type == ContentPage::TYPE_TEXT )
					{
						$pageType = ' <span class="page-tree-type">'.ContentModule::t('app', 'Text page').'</span>';

						if ( $model->is_main == 1 )
							$viewUrl = '/';
						else
							$viewUrl = ['/content/default/view', 'slug'=>$model->slug];
					}
					elseif ( $model->type == ContentPage::TYPE_INTERNAL_LINK )
					{
						$pageType = ' <span class="page-tree-type">'.ContentModule::t('app', 'Internal link').'</span>';

						$viewUrl = $model->slug;
					}
					else
					{
						$pageType = ' <span class="page-tree-type">'.ContentModule::t('app', 'External link').'</span>';

						$viewUrl = $model->slug;
					}

					$editLink = Html::a($pageName . $pageType, ['/content/content-page/update', 'id'=>$model->id]);

					$viewLink = Html::a('<i class="fa fa-eye"></i>', $viewUrl, ['target'=>'_blank']);

					return $editLink . ' ' .$viewLink;
				},
		]) ?>

	</div>
</div>
