<?php

class MenuOrderExtension extends DataExtension{
		
	//allow items to be grouped	
	function getMenuGroupableItems(){
		$items = $this->owner->Items()
				->leftJoin("Product_OrderItem", "Product_OrderItem.ID = OrderAttribute.ID")
				->leftJoin("MenuProductSelection", "Product_OrderItem.MenuProductSelectionID = MenuProductSelection.ID")
				->leftJoin("Menu", "Menu.ID = MenuProductSelection.MenuID");
		
		return new Menu_GroupedList($items);
	}

}
