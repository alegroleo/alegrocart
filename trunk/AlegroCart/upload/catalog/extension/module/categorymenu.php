<?php // Categorymenu AlegroCart
require_once('library/Tree.php');
require_once('library/Tree/Factory/List.php');

class ModuleCategoryMenu extends Controller {

	function fetch() {

		$config		=& $this->locator->get('config');
		$image		=& $this->locator->get('image');
		$language	=& $this->locator->get('language');
		$url		=& $this->locator->get('url');
		$request	=& $this->locator->get('request');
		$template	=& $this->locator->get('template');
		$head_def	=& $this->locator->get('HeaderDefinition');
		$this->modelCore= $this->model->get('model_core');
		if ($config->get('categorymenu_status')) {

			$view = $this->locator->create('template');

			$categorymenu_data = array();

			$list_data = array();
			if (isset($_GET['path'])) {$pathlvl = $_GET['path'];} else {$pathlvl = 0;}

			$results = $this->modelCore->get_menucategories();

			$level = explode('_', $pathlvl);
			$level_count = count($level);
			$level_path = $level_count>1 ? array_slice($level,0,$level_count-1):$level;

			foreach ($results as $result) {
				$path_count = count(explode('_',$result['path']));
				$class = '';
				if ($result['parent_id'] == 0) { // the main menu
					$class = 'menu_lvl_0'; // id of the <ul> tags and class of the <a> tags
					$id = 'menu_level_0'; // id of the <li> tags
					$type = "block"; // display:block or display:none
					$status = "enabled";
					$cat_image = $config->get('categorymenu_catimage') ? $image->resize($result['filename'], 16, 16) : '';
				} else if (in_array($result['parent_id'],$level) && $path_count<3){ // subcategory if selected
					$class = 'menu_lvl_' . ($path_count-1);
					$id = 'menu_level_' . ($path_count-1);
					$type = "none";// by default. Onhover --> block --> <ul> visible
					$status = "disabled";
					$cat_image = $config->get('categorymenu_subcatimage') ? $image->resize($result['filename'], 16, 16) : '';
				} else { // subcategory if not selected
					$class = 'menu_lvl_' . ($path_count-1);
					$id = 'menu_level_' . ($path_count-1);
					$type = "none";
					$status = "disabled";
					$cat_image = $config->get('categorymenu_subcatimage') ? $image->resize($result['filename'], 16, 16) : '';
				}
				
					if ($request->get('path') == $result['path']) {
						$state = 'active'; // if the menu element is selected add new class "active"
					} else {
						$state = '';
					}

				$products_in_category = $config->get('categorymenu_mcount') ? $this->modelCore->getPrInCat($result['category_id']): 0;

				if ($class) {
					$categorymenu_data[$result['category_id']] = array(
						'state'			=> $state,
						'name'			=> $result['name'],
						'href'			=> $url->href('category', false, array('path' => $result['path'])),
						'class'			=> $class,
						'id'			=> $id,
						'type'			=> $type,
						'level'			=> ($path_count-1),
						'status'		=> $status,
						'sort_order'		=> (int)$result['sort_order'],
						'image'			=> $cat_image,
						'products_in_category'	=> $products_in_category
					);
					$list_data[] = $result['path'];
				}
			}

			$new_categorymenu_data = array();

			if ($list_data) {
				$rit = new Tree_Factory_List($list_data, '_');
				$tree = Tree::factory($rit);
				$tree->nodes->traverse('setTags2', $categorymenu_data);
				$tree->usortNodes('cmp2');
				$flatList = $tree->nodes->getFlatList();
				foreach ($flatList as $node) {
					$tag = $node->getTag();
					$new_categorymenu_data[] = $tag;
				}
			}

			$view->set('menus', $new_categorymenu_data);
			$view->set('location', $this->modelCore->module_location['categorymenu']); // Template Manager 
			$view->set('head_def',$head_def);
			$template->set('head_def',$head_def);
			return $view->fetch('module/categorymenu.tpl');
		}
	}
}

function setTags2($node, $data) {
	$tag =  $data[$node->getTag()];
	$node->setTag($tag);
}

function cmp2($a, $b) {
$ret = 0;

if ( isset($a) && isset($b) ) {
		$ax = $a->getTag();
		$bx = $b->getTag();

		if ($ax['sort_order'] < $bx['sort_order']) $ret = -1;
		if ($ax['sort_order'] > $bx['sort_order']) $ret = 1;
	}
	return $ret;
}
?>
