<?php
/**
 * @var $this yii\web\View
 * @var $position string
 * @var $widgets webvimark\ybc\content\models\ContentTemplateWidget[]
 */
use webvimark\ybc\content\ContentModule;

?>
<div class="available-widgets-container">
	<?php if ( count($widgets) == 0 ): ?>
		<br/>
		<div class="text-center"><?= ContentModule::t('app', 'No available widgets for selected block') ?></div>
		<br/>
	<?php endif; ?>

	<?php foreach ($widgets as $widget): ?>

		<?php $uuid = uniqid(); ?>

		<div class="available-widget">
			<div class="row">
				<div class="col-sm-9">
					<div id="<?= $uuid ?>">
						<?= $this->render('_widgetElement', compact('widget', 'position')) ?>
					</div>

				</div>
				<div class="col-sm-3 text-right">
					<span class="btn btn-sm btn-info select-widget-btn" data-widget-uuid="<?= $uuid ?>" data-widget-position="<?= $position ?>">
						<?= ContentModule::t('app', 'Add widget') ?>
					</span>
				</div>
			</div>
		</div>

	<?php endforeach ?>
</div>
