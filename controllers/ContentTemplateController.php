<?php

namespace webvimark\ybc\content\controllers;

use webvimark\ybc\content\models\ContentTemplateHasWidget;
use webvimark\ybc\content\models\ContentTemplateWidget;
use Yii;
use webvimark\ybc\content\models\ContentTemplate;
use webvimark\ybc\content\models\search\ContentTemplateSearch;
use webvimark\components\AdminDefaultController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * ContentTemplateController implements the CRUD actions for ContentTemplate model.
 */
class ContentTemplateController extends AdminDefaultController
{
	/**
	 * @var ContentTemplate
	 */
	public $modelClass = 'webvimark\ybc\content\models\ContentTemplate';

	/**
	 * @var ContentTemplateSearch
	 */
	public $modelSearchClass = 'webvimark\ybc\content\models\search\ContentTemplateSearch';

	/**
	* @var string
	*/
	public $layout = '//backend';



	/**
	 * Displays a single model
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		if ( isset( $_POST['form-submitted'] ) )
		{
			ContentTemplateHasWidget::deleteAll(['content_template_id'=>$id]);

			if ( isset( $_POST['sorted-widgets'] ) )
			{
				foreach ($_POST['sorted-widgets'] as $position => $widgets)
				{
					$sorter = 0;
					foreach ($widgets as $widgetId)
					{
						$layoutWidget = new ContentTemplateHasWidget();
						$layoutWidget->content_template_id = $id;
						$layoutWidget->content_template_widget_id = $widgetId;
						$layoutWidget->position = $position;
						$layoutWidget->sorter = $sorter;
						$layoutWidget->save(false);

						$sorter++;
					}
				}
			}

			Yii::$app->session->setFlash('success', Yii::t('app', 'Saved'));

			return $this->refresh();
		}

		return $this->renderIsAjax('view', compact('model'));
	}

	/**
	 * @param int $id - PageLayout ID
	 */
	public function actionCloneTemplate($id)
	{
		$model = $this->findModel($id);

		$newModel = new ContentTemplate();

		// Clone attributes
		foreach ($model->attributes as $name => $value)
		{
			if ( in_array($name, ['id', 'can_be_deleted']) )
				continue;

			if ( $name == 'name' )
			{
				$value .= ' (Copy)';
			}

			$newModel->$name = $value;
		}

		$newModel->save(false);

		// Clone widget positions
		foreach ($model->contentTemplateHasWidgets as $prototypeLayoutWidget)
		{
			$layoutWidget = new ContentTemplateHasWidget();
			$layoutWidget->content_template_id = $newModel->id;
			$layoutWidget->content_template_widget_id = $prototypeLayoutWidget->content_template_widget_id;
			$layoutWidget->position = $prototypeLayoutWidget->position;
			$layoutWidget->sorter = $prototypeLayoutWidget->sorter;
			$layoutWidget->save(false);
		}
	}

	/**
	 * @param int    $id ContentTemplate ID
	 * @param string $position
	 *
	 * @return string
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function actionAvailableWidgets($id, $position)
	{
		if ( !Yii::$app->request->isAjax )
		{
			throw new BadRequestHttpException('AJAX only');
		}

		$widgets = ContentTemplateWidget::find()
			->andWhere(['active'=>1])
			->andWhere(['like', 'position', '|' . $position . '|'])
			->all();

		return $this->renderAjax('availableWidgets', compact('widgets', 'position'));
	}

	/**
	 * @param int    $id ContentTemplate ID
	 * @param string $position
	 *
	 * @return string
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function actionExistingWidgets($id, $position)
	{
		if ( !Yii::$app->request->isAjax )
		{
			throw new BadRequestHttpException('AJAX only');
		}

		$widgets = [];

		$templateHasWidgets = ContentTemplateHasWidget::find()
			->joinWith('contentTemplateWidget')
			->andWhere(['content_template_widget.active'=>1])
			->andWhere([
				'content_template_has_widget.content_template_id' => $id,
				'content_template_has_widget.position' => $position,
				'content_template_widget.active'=>1,
			])
			->orderBy('content_template_has_widget.sorter ASC')
			->all();

		foreach ($templateHasWidgets as $templateHasWidget)
		{
			$widgets[] = $templateHasWidget->contentTemplateWidget;
		}


		return $this->renderAjax('existingWidgets', compact('widgets', 'position'));
	}


	/**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param mixed $id
	 *
	 * @return ContentTemplate the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$modelClass = $this->modelClass;

		if ( ($model = $modelClass::findOne($id)) !== null )
		{
			return $model;
		}
		else
		{
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}
	}
}
