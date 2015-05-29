<?php

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentMenu $model
 */

use webvimark\ybc\content\ContentModule;

$this->title = ContentModule::t('app', 'Creating') . " " . ContentModule::t('app', 'menu');
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Manage menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-menu-create">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
