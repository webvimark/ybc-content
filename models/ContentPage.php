<?php

namespace webvimark\ybc\content\models;

use webvimark\ybc\content\ContentModule;
use webvimark\helpers\LittleBigHelper;
use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "content_page".
 *
 * @property integer $id
 * @property integer $active
 * @property integer $sorter
 * @property integer $is_main
 * @property integer $open_in_new_tab
 * @property integer $type
 * @property string $name
 * @property string $slug
 * @property string $menu_image
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $body
 * @property integer $parent_id
 * @property integer $content_template_id
 * @property integer $content_menu_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentPage $parent
 * @property ContentPage[] $contentPages
 * @property ContentTemplate $contentTemplate
 * @property ContentMenu $contentMenu
 */
class ContentPage extends \webvimark\components\BaseActiveRecord
{
	const TYPE_TEXT = 0;
	const TYPE_INTERNAL_LINK = 1;
	const TYPE_EXTERNAL_LINK = 2;

	protected $_timestamp_enabled = true;

	protected $_i18n_enabled = true;
	protected $_i18n_attributes = ['name', 'body', 'meta_title', 'meta_description', 'meta_keywords'];
	protected $_i18n_admin_routes = [
		'content/content-page/update',
		'content/content-page/create',
		'content/content-page/tree',
		'content/content-page/index',
		'content/content-page/bulk-activate',
		'content/content-page/bulk-deactivate',
		'content/content-page/toggle-attribute',
		'content/content-page/grid-sort',
	];

	/**
	 * @return array
	 */
	public function getInternalLinks()
	{
		$result = [];

		foreach (Yii::$app->getModule('content')->functionalPages as $link => $name)
		{
			$result[ContentModule::t('app', 'Functional pages')][$link] = $name;
		}

		$existingTextPages = ContentPage::find()
			->andWhere(['type'=>static::TYPE_TEXT])
			->asArray()
			->all();

		foreach ($existingTextPages as $existingTextPage)
		{
			$result[ContentModule::t('app', 'Existing text pages')][$existingTextPage['id']] = $existingTextPage['name'];
		}

		return $result;
	}


	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%content_page}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
			[['active', 'sorter', 'is_main', 'type', 'parent_id', 'content_template_id', 'content_menu_id', 'open_in_new_tab'], 'integer'],
			[['type', 'name'], 'required'],
			[['body'], 'string'],
			[['name', 'slug', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
			[['name', 'slug', 'meta_title', 'meta_keywords', 'meta_description'], 'trim'],
			[['menu_image'], 'image', 'maxSize' => 1024*1024*5, 'extensions' => ['gif', 'png', 'jpg', 'jpeg']],
			['slug', 'unique', 'when'=>function(ContentPage $model){
					return $model->type == static::TYPE_TEXT;
				}],
			['slug', 'required', 'when'=>function(ContentPage $model){
					return $model->type != static::TYPE_TEXT;
				}],
			['content_template_id', 'required', 'when'=>function(ContentPage $model){
					return ( Yii::$app->getModule('content')->enableTemplates && $model->type != static::TYPE_EXTERNAL_LINK );
				}],
			['type', 'in', 'range'=>[static::TYPE_TEXT, static::TYPE_EXTERNAL_LINK, static::TYPE_INTERNAL_LINK]]
		];
	}

	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
			'id'                  => 'ID',
			'active'              => ContentModule::t('app', 'Active'),
			'sorter'              => ContentModule::t('app', 'Sorter'),
			'is_main'             => ContentModule::t('app', 'Main page'),
			'open_in_new_tab'     => ContentModule::t('app', 'Open in new tab'),
			'type'                => ContentModule::t('app', 'Type'),
			'name'                => ContentModule::t('app', 'Name'),
			'slug'                => ContentModule::t('app', 'Slug'),
			'menu_image'          => ContentModule::t('app', 'Menu image'),
			'meta_title'          => 'Title',
			'meta_keywords'       => 'Keywords',
			'meta_description'    => 'Description',
			'body'                => ContentModule::t('app', 'Text'),
			'parent_id'           => 'Parent',
			'content_template_id' => ContentModule::t('app', 'Template'),
			'content_menu_id'     => ContentModule::t('app', 'Menu'),
			'created_at'          => ContentModule::t('app', 'Created'),
			'updated_at'          => ContentModule::t('app', 'Updated'),
		];
	}

//	/**
//	 * Generate url from the name
//	 *
//	 * @return bool
//	 */
//	public function beforeValidate()
//	{
//		if ( $this->type == static::TYPE_TEXT )
//		{
//			$this->slug = LittleBigHelper::slug($this->slug ? $this->slug : $this->name);
//		}
//
//		return parent::beforeValidate();
//	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getParent()
	{
		return $this->hasOne(ContentPage::className(), ['id' => 'parent_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentPages()
	{
		return $this->hasMany(ContentPage::className(), ['parent_id' => 'id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentTemplate()
	{
		return $this->hasOne(ContentTemplate::className(), ['id' => 'content_template_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentMenu()
	{
		return $this->hasOne(ContentMenu::className(), ['id' => 'content_menu_id']);
	}
	
	/**
	* @inheritdoc
	* @return ContentPageQuery the active query used by this AR class.
	*/
	public static function find()
	{
		return new ContentPageQuery(get_called_class());
	}


	/**
	 * Make sure that only 1 main page exists
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if ( parent::beforeSave($insert) )
		{
			if ( $this->type == static::TYPE_TEXT )
			{
				$this->slug = LittleBigHelper::slug($this->slug ? $this->slug : $this->name);
			}

			if ( $this->is_main == 1 && ( $insert || $this->oldAttributes['is_main'] == 0 ) )
			{
				ContentPage::updateAll([
					'is_main'=>0
				]);

				$this->active = 1;
			}

			return true;
		}

		return false;
	}

	// ================= Invalidate cache =================

	/**
	 * Invalidate cache
	 *
	 * @param bool  $insert
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		if ( !$insert AND array_key_exists('content_menu_id', $changedAttributes) )
		{
			$menu = ContentMenu::find()->andWhere(['id'=>$this->content_menu_id])->one();

			foreach ($this->contentPages as $child)
			{
				if ( $menu->has_submenu != 1 )
				{
					$child->parent_id = null;
				}

				$child->content_menu_id = $this->content_menu_id;
				$child->save(false);
			}
		}

		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * Invalidate cache
	 */
	public function afterDelete()
	{
		$this->deleteImage($this->menu_image);

		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterDelete();
	}
}
