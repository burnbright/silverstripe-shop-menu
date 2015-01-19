<?php

class MenuOrderItemExtension extends DataExtension{
	
	private static $has_one = array(
		"MenuProductSelection" => "MenuProductSelection"
	);

	public function Menu(){
		if($selection = $this->owner->MenuProductSelection()){
			return $selection->Menu();
		}
	}

	public function MenuGroup(){
		if($selection = $this->owner->MenuProductSelection()){
			return $selection->Group();
		}
	}

}
