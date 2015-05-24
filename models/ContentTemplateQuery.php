<?php

namespace webvimark\ybc\content\models;

/**
 * This is the ActiveQuery class for [[ContentTemplate]].
 * @see ContentTemplate
 */
class ContentTemplateQuery extends \yii\db\ActiveQuery
{
	public function active()
	{
		$this->andWhere('[[active]]=1');

		return $this;
	}

	/**
	 * @inheritdoc
	 * @return ContentTemplate[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return ContentTemplate|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}