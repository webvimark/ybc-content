<?php

namespace webvimark\ybc\content\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\ybc\content\models\ContentMenu;

/**
 * ContentMenuSearch represents the model behind the search form about `webvimark\ybc\content\models\ContentMenu`.
 */
class ContentMenuSearch extends ContentMenu
{
	public function rules()
	{
		return [
			[['id', 'active', 'has_submenu', 'has_menu_image'], 'integer'],
			[['name', 'created_at', 'updated_at', 'position', 'code'], 'safe'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = ContentMenu::find();

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
				$query->andFilterWhere(['between','content_menu.created_at', strtotime($tmp[0]), strtotime($tmp[1])]);
			}
		}

		$query->andFilterWhere([
			'content_menu.id' => $this->id,
			'content_menu.active' => $this->active,
			'content_menu.has_submenu' => $this->has_submenu,
		]);

        	$query->andFilterWhere(['like', 'content_menu.name', $this->name])
			->andFilterWhere(['like', 'content_menu.code', $this->code]);

		if ( $this->position )
		{
			$query->andFilterWhere(['like', 'content_menu.position', '|' . $this->position . '|']);
		}


		return $dataProvider;
	}
}
