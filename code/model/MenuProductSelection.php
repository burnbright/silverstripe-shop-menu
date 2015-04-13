<?php

/**
 * Represents the selection of a product for a given menu.
 * Can be grouped, and sorted.
 */
class MenuProductSelection extends DataObject{

	private static $db = array(
		'Sort' => 'Int'
	);
	
	private static $has_one = array(
		'Menu' => 'Menu',
		'Product' => 'Product',
		'Group' => 'MenuGroup'
	);
	
	private static $summary_fields = array(
		'Product.Title' => 'Name',
		'Group' => 'Group',
		'Product.Price' => 'Price'
	);

	private static $default_sort = "Sort ASC";

	public function getCMSFields($params = array()) {
		$fields = new FieldList(
			DropdownField::create("ProductID", "Product",
				Product::get()->map()->toArray()	
			)->setHasEmptyDefault(true)
		);

		$menu = $this->Menu();
		if(!$menu->exists()){
			$menu = isset($params["MenuID"]) ? Menu::get()->byID((int)$params["MenuID"]) : null;
		}

		if($menu && $menu->exists()){
			$fields->push(
				DropdownField::create("GroupID", 'Menu Group', 
					MenuGroup::get()
						->filter("ParentID", $this->MenuID)
						->map('ID', 'Title')->toArray()
				)->setHasEmptyDefault(true)
			);
		}

		if($this->Product()->exists() && !$this->Product()->canPurchase()) {
			$fields->push(
				LiteralField::create("purchasenote",
					"<p class=\"message warning\">This product can't be purchased, so it won't show up in menus</p>"
				)
			);
		}

		return $fields;
	}

	/**
	 * Use the product title as the title.
	 * @return string
	 */
	public function Title() {
		return $this->Product()->Title;
	}

	/**
	 * Ensure selection contains a product and a menu.
	 * @return ValidationResult
	 */
	public function validate() {
		$result =  parent::validate();
		if(!$this->ProductID){
			$result->error("Selection must have a product.");
		}
		if($this->isInDB() && !$this->Menu()->exists()){
			$result->error("Selection must be assigned to a menu.");
		}

		return $result;
	}
	
	/**
	 * Returns the current associated item in the current shopping
	 * cart, if it exists
	 * @return OrderItem|null
	 */
	public function getCartItem() {
		return ShoppingCart::singleton()
			->get($this->Product(), array(
				"MenuProductSelectionID" => $this->ID
			));
	}

}
