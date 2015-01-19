<?php

class MenuOrderItemExtension extends DataExtension{
	
	private static $has_one = array(
		"MenuProductSelection" => "MenuProductSelection"
	);

}
