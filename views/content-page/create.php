<?php

/**
 * @var yii\web\View $this
 * @var string $menuName
 * @var webvimark\ybc\content\models\ContentPage $model
 */

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentPage;

switch ($model->type)
{
	case ContentPage::TYPE_INTERNAL_LINK:
		$pageTypeText = ContentModule::t('app', 'internal link for');
        	break;
	case ContentPage::TYPE_EXTERNAL_LINK:
		$pageTypeText = ContentModule::t('app', 'external link for');
		break;
	default:
		$pageTypeText = ContentModule::t('app', 'text page for');
}

$this->title = Yii::t('app', 'Creating') . " " . $pageTypeText . ' - ' . $menuName;
$this->params['breadcrumbs'][] = ['label' => $menuName, 'url' => ['tree', 'menuId'=>$model->content_menu_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-page-create">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model', 'menuName', 'hasMenuImage')) ?>
		</div>
	</div>

</div>
