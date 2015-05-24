<?php

namespace webvimark\ybc\content\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\ybc\content\models\ContentTemplateWidget;

/**
 * ContentTemplateWidgetSearch represents the model behind the search form about `webvimark\ybc\content\models\ContentTemplateWidget`.
 */
class ContentTemplateWidgetSearch extends ContentTemplateWidget
{
	public function rules()
	{
		return [
			[['id', 'active', 'single_per_page', 'has_settings'], 'integer'],
			[['name', 'position', 'widget_class', 'widget_options', 'link_to_settings', 'created_at', 'updated_at'], 'safe'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = ContentTemplateWidget::find();

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
				$query->andFilterWhere(['between','content_template_widget.created_at', strtotime($tmp[0]), strtotime($tmp[1])]);
			}
		}

		$query->andFilterWhere([
			'content_template_widget.id' => $this->id,
			'content_template_widget.active' => $this->active,
			'content_template_widget.single_per_page' => $this->single_per_page,
			'content_template_widget.has_settings' => $this->has_settings,
		]);

        	$query->andFilterWhere(['like', 'content_template_widget.name', $this->name])
			->andFilterWhere(['like', 'content_template_widget.widget_class', $this->widget_class])
			->andFilterWhere(['like', 'content_template_widget.widget_options', $this->widget_options])
			->andFilterWhere(['like', 'content_template_widget.link_to_settings', $this->link_to_settings]);

		if ( $this->position )
		{
			$query->andFilterWhere(['like', 'content_template_widget.position', '|' . $this->position . '|']);
		}

		return $dataProvider;
	}
}
