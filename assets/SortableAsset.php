<?php

namespace webvimark\ybc\content\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class SortableAsset extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = __DIR__;
		$this->js = ['jquery.sortable.min.js'];

		$this->depends = [
			JqueryAsset::className(),
		];

		parent::init();
	}
} 