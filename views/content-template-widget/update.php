<?php

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplateWidget $model
 */

use webvimark\ybc\content\ContentModule;

$this->title = Yii::t('app', 'Editing') . " " . ContentModule::t('app', 'template widget') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Template widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Editing')
?>
<div class="content-template-widget-update">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
