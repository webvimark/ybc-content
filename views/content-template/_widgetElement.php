<?php
/**
 * @var $this yii\web\View
 * @var $position string
 * @var $widget webvimark\ybc\content\models\ContentTemplateWidget
 */
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Html;
?>

<div class="widget-element" data-widget-id="<?= $widget->id ?>">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<strong>

				<?php if ( $widget->has_settings == 1 && $widget->link_to_settings && User::canRoute(Yii::$app->homeUrl . ltrim($widget->link_to_settings, '/')) ): ?>
					<?= Html::a(
						"<i class='fa fa-cogs'></i>",
						Yii::$app->homeUrl . ltrim($widget->link_to_settings, '/'),
						['target'=>'_blank', 'class'=>'tn']
					) ?>

				<?php else: ?>
					<i class='fa fa-th'></i>

				<?php endif; ?>

				<span>
					<?= $widget->name ?>
				</span>

				<span style='cursor:pointer' class="pull-right removeWidget">
					<i class="fa fa-trash-o alert-danger"></i>
				</span>
			</strong>
		</div>
		<div class="panel-body">

			<?= Html::hiddenInput("sorted-widgets[{$position}][]", $widget->id) ?>

		</div>
	</div>
</div>