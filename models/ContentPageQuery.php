<?php

namespace webvimark\ybc\content\models;

/**
 * This is the ActiveQuery class for [[ContentPage]].
 *
 * @see ContentPage
 */
class ContentPageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ContentPage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ContentPage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}