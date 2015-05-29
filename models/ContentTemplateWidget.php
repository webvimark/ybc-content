<?php

namespace webvimark\ybc\content\models;

use webvimark\ybc\content\ContentModule;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;

/**
 * This is the model class for table "content_template_widget".
 *
 * @property integer $id
 * @property integer $active
 * @property integer $single_per_page
 * @property string $name
 * @property string $position
 * @property string $code
 * @property string $widget_class
 * @property string $widget_options
 * @property integer $has_settings
 * @property string $link_to_settings
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentTemplateHasWidget[] $contentTemplateHasWidgets
 * @property ContentTemplate[] $contentTemplates
 */
class ContentTemplateWidget extends \webvimark\components\BaseActiveRecord
{
	/**
	 * Used in _form for checkBoxList
	 *
	 * @var array
	 */
	public $positionIds = [];

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
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%content_template_widget}}';
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
			[['active', 'single_per_page', 'has_settings'], 'integer'],
			[['name', 'widget_class'], 'required'],
			['code', 'unique'],
			['positionIds', 'safe'],
			[['name', 'widget_class', 'widget_options', 'link_to_settings'], 'string', 'max' => 255],
			[['name', 'widget_class', 'widget_options', 'link_to_settings'], 'trim']
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
			'single_per_page' => ContentModule::t('app', 'Single per page'),
			'name' => ContentModule::t('app', 'Name'),
			'code' => ContentModule::t('app', 'Code'),
			'position' => ContentModule::t('app', 'Position'),
			'positionIds' => ContentModule::t('app', 'Position'),
			'widget_class' => ContentModule::t('app', 'Widget class'),
			'widget_options' => ContentModule::t('app', 'Widget options'),
			'has_settings' => ContentModule::t('app', 'Has settings'),
			'link_to_settings' => ContentModule::t('app', 'Link to settings'),
			'created_at' => ContentModule::t('app', 'Created'),
			'updated_at' => ContentModule::t('app', 'Updated'),
		];
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentTemplateHasWidgets()
	{
		return $this->hasMany(ContentTemplateHasWidget::className(), ['content_template_widget_id' => 'id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentTemplates()
	{
		return $this->hasMany(ContentTemplate::className(), ['id' => 'content_template_id'])->viaTable('{{%content_template_has_widget}}', ['content_template_widget_id' => 'id']);
	}
	
	/**
	* @inheritdoc
	* @return ContentTemplateWidgetQuery the active query used by this AR class.
	*/
	public static function find()
	{
		return new ContentTemplateWidgetQuery(get_called_class());
	}

	/**
	 * Create unique code on insert if it's empty
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if ( $this->code === null || empty($this->code) )
		{
			$this->code = uniqid();
		}

		return parent::beforeSave($insert);
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
		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * Invalidate cache
	 */
	public function afterDelete()
	{
		TagDependency::invalidate(Yii::$app->cache, ContentModule::CACHE_TAG);

		parent::afterDelete();
	}
}
