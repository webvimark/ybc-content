<?php

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplateWidget $model
 */

use webvimark\ybc\content\ContentModule;

$this->title = Yii::t('app', 'Creating') . " " . ContentModule::t('app', 'template widget');
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Template widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-template-widget-create">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
