<?php

namespace webvimark\ybc\content\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\ybc\content\models\ContentTemplate;

/**
 * ContentTemplateSearch represents the model behind the search form about `webvimark\ybc\content\models\ContentTemplate`.
 */
class ContentTemplateSearch extends ContentTemplate
{
	public function rules()
	{
		return [
			[['id', 'active', 'can_be_deleted'], 'integer'],
			[['name', 'created_at', 'updated_at'], 'safe'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = ContentTemplate::find();

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
				$query->andFilterWhere(['between','content_template.created_at', strtotime($tmp[0]), strtotime($tmp[1])]);
			}
		}

		$query->andFilterWhere([
			'content_template.id' => $this->id,
			'content_template.active' => $this->active,
			'content_template.can_be_deleted' => $this->can_be_deleted,
		]);

        	$query->andFilterWhere(['like', 'content_template.name', $this->name]);

		return $dataProvider;
	}
}
