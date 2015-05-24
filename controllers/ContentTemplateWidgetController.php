<?php

namespace webvimark\ybc\content\controllers;

use Yii;
use webvimark\ybc\content\models\ContentTemplateWidget;
use webvimark\ybc\content\models\search\ContentTemplateWidgetSearch;
use webvimark\components\AdminDefaultController;
use yii\web\NotFoundHttpException;

/**
 * ContentTemplateWidgetController implements the CRUD actions for ContentTemplateWidget model.
 */
class ContentTemplateWidgetController extends AdminDefaultController
{
	/**
	 * @var ContentTemplateWidget
	 */
	public $modelClass = 'webvimark\ybc\content\models\ContentTemplateWidget';

	/**
	 * @var ContentTemplateWidgetSearch
	 */
	public $modelSearchClass = 'webvimark\ybc\content\models\search\ContentTemplateWidgetSearch';

	/**
	* @var string
	*/
	public $layout = '//backend';


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ContentTemplateWidget();

		if ( $model->load(Yii::$app->request->post()) && $model->convertPositionIds() && $model->save() )
		{
			return $this->redirect($this->getRedirectPage('create', $model));
		}

		return $this->renderIsAjax('create', compact('model'));
	}

	/**
	 * Updates an existing model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		$model->convertPositionIds(true);

		if ( $model->load(Yii::$app->request->post()) && $model->convertPositionIds() && $model->save())
		{
			return $this->redirect($this->getRedirectPage('update', $model));
		}

		return $this->renderIsAjax('update', compact('model'));
	}

	/**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param int $id
	 *
	 * @return ContentTemplateWidget the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		return parent::findModel($id);
	}
}
