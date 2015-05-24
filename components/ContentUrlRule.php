<?php


namespace webvimark\ybc\content\components;


use webvimark\ybc\content\ContentModule;
use webvimark\ybc\content\models\ContentPage;
use webvimark\helpers\Singleton;
use yii\caching\TagDependency;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRule;
use Yii;

class ContentUrlRule extends UrlRule
{
	public $connectionID = 'db';

	/**
	 * @var array
	 */
	protected $_pagesById = null;

	/**
	 * @var array
	 */
	protected $_pagesBySlug = null;


	/**
	 * Creates a URL according to the given route and parameters.
	 * @param UrlManager $manager the URL manager
	 * @param string $route the route. It should not have slashes at the beginning or the end.
	 * @param array $params the parameters
	 * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
	 */
	public function createUrl($manager, $route, $params)
	{
		$this->preparePagesById();

		if ( $route === 'content/default/view' AND isset( $params['slug'] ) )
		{
			if ( isset( $params['_language'] ) )
			{
				return $params['_language'] . '/' . $this->getPageRecursiveUrl($params['slug']);
			}
			else
			{
				return $this->getPageRecursiveUrl($params['slug']);
			}
		}

		return false;  // this rule does not apply
	}

	/**
	 * Parses the given request and returns the corresponding route and parameters.
	 * @param UrlManager $manager the URL manager
	 * @param Request $request the request component
	 * @return array|boolean the parsing result. The route and the parameters are returned as an array.
	 * If false, it means this rule cannot be used to parse this path info.
	 */
	public function parseRequest($manager, $request)
	{
		// If it's base url - call main page
		if ( $request->getPathInfo() === '' )
		{
			$mainPage = $this->getMainPage();

			if ( $mainPage !== false )
			{
				Singleton::setData('content_template_id', $mainPage['content_template_id']);

				if ( $mainPage['type'] == ContentPage::TYPE_TEXT )
				{
					return ['/content/default/view', ['slug'=>$mainPage['slug']]];
				}
				elseif ( $mainPage['type'] == ContentPage::TYPE_INTERNAL_LINK )
				{
					return [
						'/' . ltrim($mainPage['slug'], '/'),
						[]
					];
				}
			}
		}

		$path = rtrim($request->getPathInfo(), '/');

		$parts = explode('/', $path);

		$languagePart = null;

		// Multilingual support - remove language from parts
		if ( isset( Yii::$app->params['mlConfig']['languages'] ) )
		{
			if ( array_key_exists($parts[0], Yii::$app->params['mlConfig']['languages'] ) )
			{
				$languagePart = array_shift($parts);
			}
		}

		$slug = ( count($parts) == 1 AND $parts[0] != '' ) ? $parts[0] : end($parts);

		if ( isset($this->getAllPages()[$slug]) )
		{
			$page = $this->getAllPages()[$slug];

			Singleton::setData('content_template_id', $page['content_template_id']);

			return ['content/default/view', ['slug'=>$slug, '_language'=>$languagePart]];
		}

		return false;  // this rule does not apply
	}

	/**
	 * Find page and all it parents to create nice url like: "/first-parent/second-parent/this-page-url"
	 *
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function getPageRecursiveUrl($slug)
	{
		if ( isset($this->_pagesBySlug[$slug]) )
		{
			$page = $this->_pagesBySlug[$slug];
			$parentId = $page['parent_id'];

			if ( $parentId AND isset($this->_pagesById[$parentId]) )
			{
				$slug = $this->getPageRecursiveUrl($this->_pagesById[$parentId]['slug']) . '/' . $slug;
			}
		}

		return $slug;
	}

	/**
	 * @return bool|array
	 */
	protected function getMainPage()
	{
		foreach ($this->getAllPages() as $page)
		{
			if ( $page['is_main'] == 1 )
			{
				return $page;
			}
		}

		return false;
	}

	/**
	 * Prepare array of all pages indexed by ID
	 *
	 * @return array
	 */
	protected function preparePagesById()
	{
		if ( $this->_pagesById === null )
		{
			foreach ($this->getAllPages() as $page)
			{
				$this->_pagesById[$page['id']] = $page;
			}
		}
	}

	/**
	 * Prepare array of all pages indexed by slug
	 *
	 * @return array
	 */
	protected function getAllPages()
	{
		if ( $this->_pagesBySlug === null )
		{
			$this->_pagesBySlug = ContentPage::getDb()->cache(function(){
				return ContentPage::find()
					->select(['id', 'slug', 'is_main', 'parent_id', 'type', 'content_template_id'])
					->asArray()
					->andWhere([
						'active'=>1,
						'type'=>ContentPage::TYPE_TEXT,
					])
					->indexBy('slug')
					->all();
			}, ContentModule::CACHE_TIME, new TagDependency(['tags'=>ContentModule::CACHE_TAG]));
		}

		return $this->_pagesBySlug;
	}
} 