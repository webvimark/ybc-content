<?php
namespace webvimark\ybc\content\components;


use webvimark\ybc\content\models\ContentMenu;
use yii\widgets\Menu;

class MenuWidget extends Menu
{
	/**
	 * @var int ContentMenu ID
	 */
	public $id;
	/**
	 * @var boolean whether the labels for menu items should be HTML-encoded.
	 */
	public $encodeLabels = false;
	/**
	 * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
	 * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
	 */
	public $activateParents = true;

	/**
	 * Fill items with ContentMenu elements
	 */
	public function init()
	{
		$this->items = ContentMenu::getItemsForMenu($this->id);
	}
} 