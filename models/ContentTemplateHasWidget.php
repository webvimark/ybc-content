<?php

namespace webvimark\ybc\content\models;

use webvimark\ybc\content\ContentModule;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;

/**
 * This is the model class for table "content_template_has_widget".
 *
 * @property integer $content_template_id
 * @property integer $content_template_widget_id
 * @property string $position
 * @property integer $sorter
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentTemplate $contentTemplate
 * @property ContentTemplateWidget $contentTemplateWidget
 */
class ContentTemplateHasWidget extends \webvimark\components\BaseActiveRecord
{
	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%content_template_has_widget}}';
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
			[['content_template_id', 'content_template_widget_id'], 'required'],
			[['content_template_id', 'content_template_widget_id'], 'integer']
		];
	}

	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
			'content_template_id' => 'Content Template ID',
			'content_template_widget_id' => 'Content Template Widget ID',
			'created_at' => 'Создано',
			'updated_at' => 'Обновлено',
		];
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
	public function getContentTemplateWidget()
	{
		return $this->hasOne(ContentTemplateWidget::className(), ['id' => 'content_template_widget_id']);
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
