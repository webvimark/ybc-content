<?php

namespace webvimark\ybc\content\controllers;

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentMenu;
use Yii;
use webvimark\ybc\content\models\ContentPage;
use webvimark\ybc\content\models\search\ContentPageSearch;
use webvimark\components\AdminDefaultController;
use yii\base\NotSupportedException;
use yii\web\NotFoundHttpException;

/**
 * ContentPageController implements the CRUD actions for ContentPage model.
 */
class ContentPageController extends AdminDefaultController
{
	/**
	 * @var ContentPage
	 */
	public $modelClass = 'webvimark\ybc\content\models\ContentPage';

	/**
	 * @var ContentPageSearch
	 */
	public $modelSearchClass = 'webvimark\ybc\content\models\search\ContentPageSearch';

	/**
	* @var string
	*/
	public $layout = '//backend';

	/**
	 * When $menuId is null - show pages without menu
	 *
	 * @param int|null $menuId
	 *
	 * @return string
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function actionTree($menuId = null)
	{
		$hasSubmenu = 0;
		$menuName = ContentModule::t('app', 'Without menu');

		if ( $menuId )
		{
			$contentMenu = ContentMenu::find()
				->andWhere([
					'id'     => $menuId,
					'active' => 1,
				])->one();

			if ( !$contentMenu )
				throw new NotFoundHttpException(ContentModule::t('app', 'Menu not found'));

			$hasSubmenu = $contentMenu->has_submenu;

			$menuName = $contentMenu->name;
		}

		$pages = ContentPage::find()
			->where(['content_page.content_menu_id' => $menuId ? $menuId : null])
			->orderBy('content_page.sorter ASC')
			->all();

		return $this->render('tree', compact('pages', 'menuId', 'hasSubmenu', 'menuName'));
	}


	/**
	 * @inheritdoc
	 */
	public function actionCreate($type = null, $menuId = null)
	{
		if ( !in_array($type, [ContentPage::TYPE_TEXT, ContentPage::TYPE_INTERNAL_LINK, ContentPage::TYPE_EXTERNAL_LINK]) )
		{
			throw new NotSupportedException('Type = ' . $type . ' is not supported');
		}

		$menuName = ContentModule::t('app', 'Without menu');
		$hasMenuImage = false;

		if ( $menuId )
		{
			$contentMenu = ContentMenu::find()
				->andWhere([
					'id'     => $menuId,
					'active' => 1,
				])->one();

			if ( !$contentMenu )
				throw new NotFoundHttpException(ContentModule::t('app', 'Menu not found'));

			$menuName = $contentMenu->name;

			$hasMenuImage = $contentMenu->has_menu_image === 1;
		}


		$model = new ContentPage([
			'type'            => $type,
			'content_menu_id' => $menuId
		]);


		if ( $model->load(Yii::$app->request->post()) && $model->save() )
		{
			return $this->redirect(['tree',	'menuId' => $model->content_menu_id]);
		}

		return $this->renderIsAjax('create', compact('model', 'menuName', 'hasMenuImage'));
	}


	/**
	 * @inheritdoc
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		$hasMenuImage = ($model->contentMenu && $model->contentMenu->has_menu_image === 1);
		$menuName = $model->contentMenu ? $model->contentMenu->name : ContentModule::t('app', 'Without menu');

		if ( $model->load(Yii::$app->request->post()) )
		{
			if ( $model->validate() )
			{
				if ( $model->oldAttributes['content_menu_id'] != $model->content_menu_id )
					$model->parent_id = null;

				$model->save(false);

				return $this->redirect(['tree',	'menuId' => $model->content_menu_id]);
			}
		}

		return $this->renderIsAjax('update', compact('model', 'menuName', 'hasMenuImage'));
	}
}
