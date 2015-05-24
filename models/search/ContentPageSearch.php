<?php

namespace webvimark\ybc\content\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\ybc\content\models\ContentPage;

/**
 * ContentPageSearch represents the model behind the search form about `webvimark\ybc\content\models\ContentPage`.
 */
class ContentPageSearch extends ContentPage
{
	public function rules()
	{
		return [
			[['id', 'active', 'sorter', 'is_main', 'type', 'parent_id', 'content_template_id', 'content_menu_id'], 'integer'],
			[['name', 'slug', 'menu_image', 'meta_title', 'meta_keywords', 'meta_description', 'body', 'created_at', 'updated_at'], 'safe'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = ContentPage::find();

		if ( ! Yii::$app->request->get('sort') )
		{
			$query->orderBy('content_page.sorter');
		}

		$query->joinWith(['contentTemplate', 'contentMenu']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
			],
			'sort'=>[
				'defaultOrder'=>['id'=> SORT_DESC],
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		if ( $this->created_at )
		{
			$tmp = explode(' - ', $this->created_at);
			if ( isset($tmp[0], $tmp[1]) )
			{
				$query->andFilterWhere(['between','content_page.created_at', strtotime($tmp[0]), strtotime($tmp[1])]);
			}
		}

		$query->andFilterWhere([
			'content_page.id' => $this->id,
			'content_page.active' => $this->active,
			'content_page.sorter' => $this->sorter,
			'content_page.is_main' => $this->is_main,
			'content_page.type' => $this->type,
			'content_page.parent_id' => $this->parent_id,
			'content_page.content_template_id' => $this->content_template_id,
			'content_page.content_menu_id' => $this->content_menu_id,
		]);

        	$query->andFilterWhere(['like', 'content_page.name', $this->name])
			->andFilterWhere(['like', 'content_page.slug', $this->slug])
			->andFilterWhere(['like', 'content_page.meta_title', $this->meta_title])
			->andFilterWhere(['like', 'content_page.meta_keywords', $this->meta_keywords])
			->andFilterWhere(['like', 'content_page.meta_description', $this->meta_description]);

		return $dataProvider;
	}
}
