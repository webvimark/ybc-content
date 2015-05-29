<?php

namespace webvimark\ybc\content\models;

use webvimark\ybc\content\ContentModule;
use webvimark\helpers\Singleton;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\helpers\FileHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "content_template".
 *
 * @property integer $id
 * @property integer $active
 * @property integer $can_be_deleted
 * @property string $name
 * @property string $layout
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentPage[] $contentPages
 * @property ContentTemplateHasWidget[] $contentTemplateHasWidgets
 * @property ContentTemplateWidget[] $contentTemplateWidgets
 */
class ContentTemplate extends \webvimark\components\BaseActiveRecord
{
	/**
	 * Render widgets in active template
	 * Singleton::getData('content_template_id') is set in ContentUrlRule
	 *
	 * @param string $position
	 *
	 * @return string
	 */
	public static function renderWidgets($position)
	{
		$templateId = Singleton::getData('content_template_id');

		if ( !$templateId )
			return '';

		$result = '';

		$templateHasWidgets = ContentTemplateHasWidget::getDb()->cache(function() use ($position, $templateId){
			return ContentTemplateHasWidget::find()
				->joinWith('contentTemplateWidget')
				->andWhere(['content_template_widget.active'=>1])
				->andWhere([
					'content_template_has_widget.content_template_id' => $templateId,
					'content_template_has_widget.position' => $position,
					'content_template_widget.active'=>1,
				])
				->orderBy('content_template_has_widget.sorter ASC')
				->all();
		}, ContentModule::CACHE_TIME, new TagDependency(['tags'=>ContentModule::CACHE_TAG]));

		foreach ($templateHasWidgets as $templateHasWidget)
		{
			$widgetClass = $templateHasWidget->contentTemplateWidget->widget_class;

			$result .= "<div class='layout-widget layout-widget-{$position}'>";
			$result .=  $widgetClass::widget(@unserialize($templateHasWidget->contentTemplateWidget->widget_options));
			$result .=  "</div>";
		}


		return $result;
	}

	/**
	 * Scan directory with templates and show them in dropdown list
	 *
	 * @param \yii\bootstrap\ActiveForm $form
	 *
	 * @return string
	 */
	public function layoutSelector($form)
	{
		$items = [];

		$pathToTemplates = Yii::getAlias('@app/templates/');

		Yii::$app->assetManager->publish($pathToTemplates);
		$assetUrl = Yii::$app->assetManager->getPublishedUrl($pathToTemplates);

		$layoutFolders = scandir($pathToTemplates);

		foreach ($layoutFolders as $layoutFolder)
		{
			if ( !in_array($layoutFolder, ['.', '..']) && is_dir($pathToTemplates . $layoutFolder) )
			{
				$items[$layoutFolder] = Html::img($assetUrl . '/' . $layoutFolder . '/backend_image.png');
			}
		}

		return $form->field($this, 'layout')->radioList($items);
	}

	/**
	 * @return string
	 */
	public function getLayoutImageFromAssets()
	{
		$pathToTemplates = Yii::getAlias('@app/templates/');

		Yii::$app->assetManager->publish($pathToTemplates);
		$assetUrl = Yii::$app->assetManager->getPublishedUrl($pathToTemplates);

		return $assetUrl . '/' . $this->layout . '/backend_image.png';
	}

	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%content_template}}';
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
			[['active', 'can_be_deleted'], 'integer'],
			[['name', 'layout'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['name'], 'trim']
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
			'can_be_deleted' => ContentModule::t('app', 'Can be deleted'),
			'name' => ContentModule::t('app', 'Name'),
			'layout' => ContentModule::t('app', 'Layout'),
			'created_at' => ContentModule::t('app', 'Created'),
			'updated_at' => ContentModule::t('app', 'Updated'),
		];
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentPages()
	{
		return $this->hasMany(ContentPage::className(), ['content_template_id' => 'id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentTemplateHasWidgets()
	{
		return $this->hasMany(ContentTemplateHasWidget::className(), ['content_template_id' => 'id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getContentTemplateWidgets()
	{
		return $this->hasMany(ContentTemplateWidget::className(), ['id' => 'content_template_widget_id'])->viaTable('{{%content_template_has_widget}}', ['content_template_id' => 'id']);
	}
	
	/**
	* @inheritdoc
	* @return ContentTemplateQuery the active query used by this AR class.
	*/
	public static function find()
	{
		return new ContentTemplateQuery(get_called_class());
	}

	/**
	 * Don't let delete templates that cannot be deleted
	 *
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		if ( $this->can_be_deleted != 1 )
		{
			return false;
		}

		return parent::beforeDelete();
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
