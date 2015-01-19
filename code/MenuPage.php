<?php

class MenuPage extends Page{



}

class MenuPage_Controller extends Page_Controller{

	protected $minpeople = 5;

	public static $allowed_actions = array(
		"Form"
	);

	public function getProductMenu() {
		return Menu::get()->first();
	}

	public function Form() {
		$menu = $this->getProductMenu();
 		$gselections = new Menu_GroupedList($menu->ProductSelections());
		$fields = new FieldList(
			NumericField::create("NumberOfPeople", "Number of people")
				->setValue($this->minpeople)
				->setDescription("(Minimum is 5)"),
			QuantitiesSelectionField::create(
				"Selections", $menu->Title, $gselections
			)
		);
		$actions = new FieldList(
			new FormAction("save", _t("MenuPage.SAVE","Save"))
		);
		$form = new Form($this, "Form", $fields, $actions);

		//TODO: load data from cart

		return $form;
	}

	//validate menu count is accurate

	public function save($data, $form) {
		$selectionsfield = $form->Fields()->fieldByName("Selections");
		$totalqty = $selectionsfield->getSumQuantities();

		if($totalqty < $this->minpeople){
			$form->addErrorMessage("NumberOfPeople", "Please select a quantity greater than ".$this->minpeople);
		}
		
		//add quantities of selected products
		$quantities = $selectionsfield->getQuantities();
		$selectables = $this->getProductMenu()->ProductSelections();
		foreach($quantities as $id => $quantity) {
			$selection = $selectables->byID($id);
			if($selection && $buyable = $selection->Product()){
				//restrict order item to given selection
				$filter = array("MenuProductSelectionID" => $selection->ID);
				$item = ShoppingCart::singleton()->setQuantity($buyable, $quantity, $filter);
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

