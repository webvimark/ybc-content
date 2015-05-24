<?php

namespace webvimark\ybc\content\controllers;

use Yii;
use webvimark\ybc\content\models\ContentMenu;
use webvimark\ybc\content\models\search\ContentMenuSearch;
use webvimark\components\AdminDefaultController;
use yii\web\NotFoundHttpException;

/**
 * ContentMenuController implements the CRUD actions for ContentMenu model.
 */
class ContentMenuController extends AdminDefaultController
{
	/**
	 * @var ContentMenu
	 */
	public $modelClass = 'webvimark\ybc\content\models\ContentMenu';

	/**
	 * @var ContentMenuSearch
	 */
	public $modelSearchClass = 'webvimark\ybc\content\models\search\ContentMenuSearch';

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
		$model = new ContentMenu();

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
	 * @return ContentMenu the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		return parent::findModel($id);
	}
}
