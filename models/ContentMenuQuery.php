<?php

namespace webvimark\ybc\content\models;

/**
 * This is the ActiveQuery class for [[ContentMenu]].
 *
 * @see ContentMenu
 */
class ContentMenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ContentMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ContentMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}