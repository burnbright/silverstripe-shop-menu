<?php

/**
* Menu administration
*/
class MenuAdmin	extends ModelAdmin{
	
	private static $url_segment = 'menus';
	private static $menu_title = 'Menus';
	private static $menu_priority = 1;
	private static $menu_icon = 'shop_menu/images/menu_icon.png';

	private static $managed_models = array(
		'Menu' => array(
			'title' => 'Menus'
		)
	);

	public function getEditForm($id = null, $fields = null){
		$form = parent::getEditForm($id, $fields);
		$gridField = $form->Fields()->fieldByName('Menu');
		$importer = $gridField->getConfig()
			->getComponentByType('GridFieldImporter');
		$loader  = $importer->getLoader($gridField);
		$loader->transforms = array(
			'Title' => array(
				'required' => true
			)
		);
		$loader->duplicateChecks = array(
			'Title'
		);

		return $form;
	}

}
