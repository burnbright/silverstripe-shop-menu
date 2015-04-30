<?php

class MenuPage extends Page{

	private static $has_one = array(
		'Menu' => 'Menu'
	);

	private static $icon = 'shop_menu/images/menupage.png';

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Main",
			DropdownField::create("MenuID", "Menu",
				Menu::get()->map()->toArray()
			),
			"Content"
		);

		return $fields;
	}

}

class MenuPage_Controller extends Page_Controller{

	protected $minpeople = 5;

	public static $allowed_actions = array(
		"Form"
	);

	public function getProductMenu() {
		if($this->dataRecord->Menu()->exists()){
			return $this->dataRecord->Menu();
		}

		return Menu::get()->first();
	}

	public function Form() {
		$menu = $this->getProductMenu();
		$selections = new ArrayList();
		foreach($menu->ProductSelections() as $mps){
			if($mps->Product()->exists() && $mps->Product()->canPurchase()) {
				$selections->push($mps);
			}
		}
		if(!$selections->exists()){
			return;
		}

 		$gselections = new Menu_GroupedList($selections);
		$fields = new FieldList(
			QuantitiesSelectionField::create(
				"Selections", "", $gselections
			)
		);
		$actions = new FieldList(
			new FormAction("save", _t("MenuPage.SAVE","Save"))
		);
		$form = new Form($this, "Form", $fields, $actions);
		$this->dataRecord->extend("updateMenuSelectionForm", $form);

		return $form;
	}

	//TODO: validate menu count is accurate

	public function save($data, $form) {
		$selectionsfield = $form->Fields()->fieldByName("Selections");
		$totalqty = $selectionsfield->getSumQuantities();
		//add quantities of selected products
		$quantities = $selectionsfield->getQuantities();
		$selectables = $this->getProductMenu()->ProductSelections();
		foreach($quantities as $id => $quantity) {
			$selection = $selectables->byID($id);
			if($selection && $buyable = $selection->Product()){
				//restrict order item to given selection
				$filter = array(
					"MenuProductSelectionID" => $selection->ID
				);
				$item = ShoppingCart::singleton()
							->setQuantity($buyable, $quantity, $filter);
				if(!$item){
					//TODO: errors
					//ShoppingCart::singleton()->getMessage();
				}
			}else{
				//selection not found!
			}
		}

		return $this->redirect(CartPage::find_link());
	}

}

