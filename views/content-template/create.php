<?php

/**
 * @var yii\web\View $this
 * @var webvimark\ybc\content\models\ContentTemplate $model
 */

use webvimark\ybc\content\ContentModule;

$this->title = Yii::t('app', 'Creating') . " " . ContentModule::t('app', 'template');
$this->params['breadcrumbs'][] = ['label' => ContentModule::t('app', 'Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-template-create">

	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
