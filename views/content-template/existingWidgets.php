<?php
/**
 * @var $this yii\web\View
 * @var $position string
 * @var $widgets webvimark\ybc\content\models\ContentTemplateWidget[]
 */

?>

<?php foreach ($widgets as $widget): ?>
	<?= $this->render('_widgetElement', compact('widget', 'position')) ?>

<?php endforeach ?>

