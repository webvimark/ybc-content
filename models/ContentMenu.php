<?php

namespace webvimark\ybc\content\models;

use webvimark\ybc\content\ContentModule;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\helpers\Url;

/**
 * This is the model class for table "content_menu".
 *
 * @property integer $id
 * @property integer $active
 * @property integer $has_submenu
 * @property integer $has_menu_image
 * @property string $name
 * @property string $code
 * @property string $position
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentPage[] $contentPages
 */
class ContentMenu extends \webvimark\components\BaseActiveRecord
{
	const CACHE_DURATION = 3600;
	const CACHE_TAG_KEY = __CLASS__;

	const WIDGET_CLASS = 'webvimark\ybc\content\components\MenuWidget';

	/**
	 * Used in _form for checkBoxList
	 *
	 * @var array
	 */
	public $positionIds = [];


	/**
	 * Used in Menu or Nav widget
	 *
	 * @param int $id ContentMenu ID
	 *
	 * @return array
	 */
	public static function getItemsForMenu($id)
	{
		$cacheKey = implode('_-_', [
			__CLASS__,
			__FUNCTION__,
			Yii::$app->language,
			$id,
			Yii::$app->user->isGuest ? 'guest' : 'authorized',
		]);

		$result = Yii::$app->cache->get($cacheKey);

		if ( $result === false )
		{
			$pages = ContentPage::find()
				->innerJoinWith(['contentMenu'])
				->select([
					'content_page.name',
					'content_menu.has_menu_image',
					'content_page.menu_image',
					'content_page.id',
					'content_page.parent_id',
					'content_page.is_main',
					'content_page.content_menu_id',
					'content_page.type',
					'content_page.slug',
				])
				->where([
					'content_page.active'=>1,
					'content_menu.active'=>1,
					'content_menu.id'=>$id,
				])
				->orderBy('content_page.sorter')
				->all();

			$result = self::getChildrenForMenu($pages, null);

			Yii::$app->cache->set($cacheKey, $result, ContentModule::CACHE_TIME, new TagDependency(['tags'=>ContentModule::CACHE_TAG]));
		}

		return $result;
	}

	/**
	 * Helper for "getItemsForMenu"
	 *
	 * @param ContentPage[]    $pages
	 * @param int|null $id ContentPage ID
	 *
	 * @return array
	 */
	protected static function getChildrenForMenu($pages, $id)
	{
		$output = [];

		foreach ($pages as $page)
		{
			if ( $page->parent_id == $id )
			{
				self::makeLabelForMenu($output, $page);

				self::makeUrlForMenu($output, $page);

				$items = self::getChildrenForMenu($pages, $page->id);

				if ( $items )
				{
					$output[$page->id]['items'] = $items;
				}
			}
		}

		return $output;
	}

	/**
	 * Helper for "getChildrenForMenu"
	 *
	 * Check if this menu has images in labels and render them before or after label
	 *
	 * @param array $output
	 * @param ContentPage|array $page
	 */
	protected static function makeLabelForMenu(&$output, $page)
	{
		if ( $page->contentMenu->has_menu_image == 1 AND $page->menu_image )
		{
			$imageTag = '<span class="menu-inner-image">'
				. Html::img($page->getImageUrl('full', 'menu_image'), ['alt'=>$page->name])
				. '</span>';

			$textTag = "<span class='menu-inner-text'>{$page->name}</span>";


			if ( $page->contentMenu->image_before_label )
			{
				$label = $imageTag . $textTag;
			}
			else
			{
				$label = $textTag . $imageTag;
			}
		}
		else
		{
			$label = $page->name;
		}

		$output[$page->id]['label'] = $label;
	}

	/**
	 * Helper for "getChildrenForMenu"
	 *
	 * @param array $output
	 * @param ContentPage|array $page
	 */
	protected static function makeUrlForMenu(&$output, $page)
	{
		if ( $page->is_main == 1 )
		{
			$output[$page->id]['url'] =  Yii::$app->homeUrl;
		}
		elseif ( $page->type == ContentPage::TYPE_TEXT )
		{
			$output[$page->id]['url'] = ['/content/default/view', 'slug'=>$page->slug];
		}
		elseif ( $page->type == ContentPage::TYPE_INTERNAL_LINK )
		{
			// Slug contains linked ContentPage ID
			if ( is_numeric($page->slug) )
			{
				$linkedPage = ContentPage::find()
					->andWhere(['id' => $page->slug])
					->one();

				if ( $linkedPage )
				{
					$output[$page->id]['url'] = ['/content/default/view', 'slug'=>$linkedPage->slug];
				}
			}
			else // Slug contains to functional page like news/default/index
			{
				$output[$page->id]['url'] = ['/' . ltrim($page->slug, '/')];
			}
		}
		else
		{
			$output[$page->id]['url'] =  $page->slug;
		}
	}

	/**
	 * Show list of selected positions like Left, Top center, Footer
	 *
	 * @return string
	 */
	public function showNicePositions()
	{
		$res = [];

		if ( $this->position )
		{
			$this->convertPositionIds(true);

			foreach ($this->positionIds as $positionId)
			{
				$res[$positionId] = @Yii::$app->getModule('content')->availableWidgetPositions[$positionId];
			}
		}

		return implode(', ', $res);
	}

	/**
	 * @param bool $stringToIds
	 *
	 * @return bool
	 */
	public function convertPositionIds($stringToIds = false)
	{
		if ( $stringToIds )
		{
			if ( $this->position )
			{
				$this->positionIds = explode('|', trim($this->position, '|'));
			}
		}
		else
		{
			if ( $this->positionIds )
			{
				$this->position = '|' . implode('|', $this->positionIds) . '|';
			}
			else
			{
				$this->position = null;
			}
		}

		return true;
	}

	/**
	 * List of main menu places
	 *
	 * @return array
	 */
	public static function getListOfMenus()
	{
		$menus = static::find()->andWhere('active = 1')->asArray()->all();
		$output = [];

		$i = 11;

		foreach ($menus as $menu)
		{
			$output[$i++] = [
				'label'=>'<i class="fa fa-file"></i> ' . $menu['name'],
				'url'=>['/content/content-page/tree', 'menuId'=>$menu['id']],
			];
		}
		$output[$i] = ['label' => '<i class="fa fa-file-o"></i> ' . static::withoutMenuName(), 'url' => ['/content/content-page/tree', 'id'=>null]];

		return $output;
	}

	/**
	 * @return string
	 */
	public static function withoutMenuName()
	{
		return ContentModule::t('app', 'Pages without menu');
	}

	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%content_menu}}';
	}

	/**
	* @inheritdoc
	*/
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
			[['active', 'has_submenu', 'has_menu_image'], 'integer'],
			['positionIds', 'safe'],
			[['name'], 'required'],
			[['name', 'code'], 'string', 'max' => 255],
			[['name', 'code'], 'trim'],
		];
	}

	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'active' => ContentModule::t('app', 'Active'),
			'has_submenu' => ContentModule::t('app', 'Has submenu'),
			'has_menu_image' => ContentModule::t('app', 'Has menu image'),
			'positionIds' => ContentModule::t('app', 'Position'),
			'name' => ContentModule::t('app', 'Name'),
			'code' => ContentModule::t('app', 'Code'),
			'created_at' => Yii::t('app', 'Created'),
			'updated_at' => Yii::t('app', 'Updated'),
		];
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentPages()
	{
		return $this->hasMany(ContentPage::className(), ['content_menu_id' => 'id']);
	}
	
	/**
	* @inheritdoc
	* @return ContentMenuQuery the active query used by this AR class.
	*/
	public static function find()
	{
		return new ContentMenuQuery(get_called_class());
	}

	/**
	 * Delete ContentTemplateWidget
	 *
	 * @inheritdoc
	 */
	public function afterDelete()
	{
		ContentTemplateWidget::deleteIfExists([
			'widget_class' => self::WIDGET_CLASS,
			'code'         => md5(__CLASS__ . '_' . $this->id),
		]);

		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterDelete();
	}

	/**
	 * Create ContentTemplateWidget or update it
	 *
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		if ( $insert )
		{
			$widget = new ContentTemplateWidget();

			$widget->name           = $this->name;
			$widget->position       = $this->position;
			$widget->widget_class   = self::WIDGET_CLASS;
			$widget->code           = md5(__CLASS__ . '_' . $this->id);
			$widget->widget_options = serialize(['id' => $this->id]);
			$widget->has_settings   = 1;
			$widget->link_to_settings = '/content/content-page/tree?menuId=' . $this->id;

			$widget->save(false);
		}
		elseif ( array_key_exists('name', $changedAttributes) || array_key_exists('position', $changedAttributes) )
		{
			$widget = ContentTemplateWidget::findOne([
				'widget_class' => self::WIDGET_CLASS,
				'code'         => md5(__CLASS__ . '_' . $this->id),
			]);

			if ( $widget )
			{
				$widget->name = $this->name;
				$widget->position = $this->position;
				$widget->save(false);
			}
		}

		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterSave($insert, $changedAttributes);
	}
}
