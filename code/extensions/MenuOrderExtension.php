<?php

class MenuOrderExtension extends DataExtension{
		
	/**
	 * Allow items to be grouped
	 * @param boolean $editable
	 */
	public function MenuGroupableItems(){
		$items = $this->owner->Items()
				->leftJoin("Product_OrderItem", "Product_OrderItem.ID = OrderAttribute.ID")
				->leftJoin("MenuProductSelection", "Product_OrderItem.MenuProductSelectionID = MenuProductSelection.ID")
				->leftJoin("Menu", "Menu.ID = MenuProductSelection.MenuID");

		return new Menu_GroupedList($items);
	}

}
