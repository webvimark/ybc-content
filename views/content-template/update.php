<?php

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplate $model
 */

use webvimark\ybc\content\ContentModule;

$this->title = ContentModule::t('app', 'Editing') . " " . ContentModule::t('app', 'template') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ContentModule::t('app', 'Editing')
?>
<div class="content-template-update">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
