<?php

namespace webvimark\ybc\content\controllers;

use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentPage;
use webvimark\ybc\content\models\ContentTemplate;
use webvimark\components\BaseController;
use webvimark\helpers\Singleton;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\NotFoundHttpException;

class DefaultController extends BaseController
{
	public $freeAccess = true;

	/**
	 * @return array
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		if ( $this->module->enablePageCache )
		{
			$behaviors['content-page-cache'] = [
				'class' => 'yii\filters\PageCache',
				'duration' => $this->module->pageCacheTime,
				'variations' => array_merge($this->module->additionalPageCacheVariations, [
					Yii::$app->language,
					Yii::$app->user->isGuest,
					Yii::$app->request->isAjax,
					Yii::$app->request->pathInfo,
				]),
				'dependency'=>new TagDependency([
						'tags'=>[ContentModule::CACHE_TAG, ContentModule::PAGE_CACHE_TAG]
					]),
			];
		}

		return $behaviors;
	}

	/**
	 * @param string $slug
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @return string
	 */
	public function actionView($slug)
	{
		$contentPage = ContentPage::getDb()->cache(function() use ($slug) {
			return ContentPage::find()
				->andWhere([
					'active' => 1,
					'slug'   => $slug,
				])
				->one();
		}, ContentModule::CACHE_TIME, new TagDependency(['tags'=>ContentModule::CACHE_TAG]));

		if ( !$contentPage )
		{
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		// content_template_id is set in ContentUrlRule
		$templateId = Singleton::getData('content_template_id');

		if ( $templateId && $this->module->enableTemplates )
		{
			$template = ContentTemplate::getDb()->cache(function() use ($templateId) {
				return ContentTemplate::find()
					->andWhere([
						'id'   => $templateId,
						'active' => 1,
					])
					->one();
			}, ContentModule::CACHE_TIME, new TagDependency(['tags'=>ContentModule::CACHE_TAG]));

			if ( $template && is_file(Yii::getAlias('@app/templates/') . $template->layout . '/layout.php') )
			{
				$this->layout = '@app/templates/' . $template->layout . '/layout.php';
			}
		}

		$breadcrumbs[] = ['label'=>$contentPage->name];

//		$this->prepareBreadcrumbs($contentPage, $breadcrumbs);

		return $this->renderIsAjax('view', compact('contentPage', 'breadcrumbs'));
	}

	/**
	 * @param ContentPage $page
	 * @param array $breadcrumbs
	 */
	protected function prepareBreadcrumbs($page, &$breadcrumbs)
	{
		if ( $page->parent_id !== null && $page->parent )
		{
			$breadcrumbs[] = ['label'=>$page->parent->name];

			$this->prepareBreadcrumbs($page->parent, $breadcrumbs);
		}
	}
}
