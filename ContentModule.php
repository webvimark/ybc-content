<?php

namespace webvimark\ybc\content;

use webvimark\modules\UserManagement\models\User;
use webvimark\ybc\content\models\ContentMenu;
use Yii;

class ContentModule extends \yii\base\Module
{
	// Used in TagDependency caching
	const CACHE_TIME = 3600;
	const CACHE_TAG = 'content_module_cache_tag';
	const PAGE_CACHE_TAG = 'content_module_page_cache_tag';

	/**
	 * Wherever use templates or don't mention them anywhere
	 *
	 * @var bool
	 */
	public $enableTemplates = true;

	/**
	 * Array of the pages for internal links where link to the functional page is the key of array
	 * and custom name is array vale
	 *
	 * <example>
	 * 	[
	 * 		'news/default/index' => Yii::t('functionalPages', 'News'),
	 * 		'site/contact' => 'Contacts',
	 * 	]
	 * </example>
	 *
	 * @var array
	 */
	public $functionalPages = [];

	/**
	 * Position codes where widgets can be placed in template layout
	 *
	 * @var array
	 */
	public $availableWidgetPositions = [
		'left'          => 'Left',
		'right'         => 'Right',
		'center_top'    => 'Center top',
		'center_bottom' => 'Center bottom',
		'header'        => 'Header',
		'footer'        => 'Footer',
	];

	/**
	 * Enable "yii\filters\PageCache" for text pages
	 *
	 * Use it with care and don't forget "$this->renderDynamic()"
	 *
	 * This cache invalidated on page, template or widget in content module change/delete/create
	 * You may also invalidate it with TagDependency::invalidate(Yii::$app->cache, ContentModule::PAGE_CACHE_TAG);
	 *
	 * @see http://www.yiiframework.com/doc-2.0/guide-caching-page.html
	 * @var bool
	 */
	public $enablePageCache = false;

	/**
	 * How long page with be cached (in seconds) if full page cache is enabled
	 *
	 * @see $enablePageCache
	 * @var int
	 */
	public $pageCacheTime = 3600;

	/**
	 * You can see default variations for the page caching in /ybc-content/controller/DefaultController
	 *
	 * @see $enablePageCache
	 * @var array
	 */
	public $additionalPageCacheVariations = [];


	public $controllerNamespace = 'webvimark\ybc\content\controllers';

	/**
	 * List of items for backend side menu
	 *
	 * @return array
	 */
	public static function getSideMenuItems()
	{
		$output = [
			'17' => [
				'label' => '<i class="fa fa-pagelines"></i> ' . ContentModule::t('app', 'Page templates'),
				'url'   => ['/content/content-template/index'],
				'visible'=>( Yii::$app->getModule('content')->enableTemplates && User::canRoute(['/content/content-template/index']) ),
			],

			'18' => [
				'label' => '<i class="fa fa-code-fork"></i> ' . ContentModule::t('app', 'Template widgets'),
				'url'   => ['/content/content-template-widget/index'],
				'visible'=>( Yii::$app->getModule('content')->enableTemplates && User::canRoute(['/content/content-template-widget/index']) ),
			],
			'19'=>[
				'label' => '<i class="fa fa-table"></i> ' . ContentModule::t('app', 'Manage menus'), 'url' => ['/content/content-menu/index']
			],
		];

		$menus = ContentMenu::getListOfMenus();

		krsort($menus);

		foreach ($menus as $menu)
		{
			array_unshift($output, $menu);
		}

		return $output;
	}

	/**
	* I18N helper
	*
	* @param string      $category
	* @param string      $message
	* @param array       $params
	* @param null|string $language
	*
	* @return string
	*/
	public static function t($category, $message, $params = [], $language = null)
	{
		if ( !isset(Yii::$app->i18n->translations['modules/content/*']) )
		{
			Yii::$app->i18n->translations['modules/content/*'] = [
				'class'          => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en',
				'basePath'       => '@vendor/webvimark/ybc-content/messages',
				'fileMap'        => [
					'modules/content/app' => 'app.php',
				],
			];
		}

		return Yii::t('modules/content/' . $category, $message, $params, $language);
	}
}
