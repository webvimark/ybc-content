<?php

namespace webvimark\ybc\content;

use Yii;

class ContentModule extends \yii\base\Module
{
	// Used in TagDependency caching
	const CACHE_TIME = 3600;
	const CACHE_TAG = 'content_module_cache_tag';
	const PAGE_CACHE_TAG = 'content_module_page_cache_tag';


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

	public $controllerNamespace = 'webvimark\ybc\content\controllers';

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
				//'sourceLanguage' => 'en',
				'basePath'       => '@app/modules/content/messages',
				'fileMap'        => [
					'modules/content/app' => 'app.php',
				],
			];
		}

		return Yii::t('modules/content/' . $category, $message, $params, $language);
	}
}
