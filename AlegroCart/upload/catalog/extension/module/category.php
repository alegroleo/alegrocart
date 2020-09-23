<?php // Category Menu AlegroCart
require_once('library/Tree.php');
require_once('library/Tree/Factory/List.php');

class ModuleCategory extends Controller{

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->modelCore	= $model->get('model_core');
		$this->cache		=& $locator->get('cache');
	}

	public function fetch() {

		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');
		$head_def =& $this->locator->get('HeaderDefinition'); 

		if ($config->get('category_status')) {

			$language->load('extension/module/category.php');

			$view = $this->locator->create('template');

			$view->set('heading_title', $language->get('heading_title'));
			$view->set('subcategory', $language->get('text_subcategory'));

			$category_data = array();

			$list_data = array();
			if (isset($_GET['path'])) {$pathlvl = $_GET['path'];} else {$pathlvl = 0;}


			$count_data = $this->getcount();
			$results = $this->modelCore->get_categories();

			$level = explode('_', $pathlvl);
			$level_count = count($level);
			$level_path = $level_count>1 ? array_slice($level,0,$level_count-1):$level;

			foreach ($results as $result) {
				$path_count = count(explode('_',$result['path']));
				$class = '';
				if ($result['parent_id'] == 0) {
					$class = 'cat_lvl_0';
					$type = "block";
					$status = "enabled";
				} else if (in_array($result['parent_id'],$level)){
					$class = 'cat_lvl_' . ($path_count-1);
					$type = "block";
					$status = "enabled";
				} else {
					$class = 'cat_lvl_' . ($path_count-1);
					$type = "none";
					$status = "disabled";
				}

					if ($request->get('path') == $result['path']) {
						$state = 'active';
					} else {
						$state = '';
					}

				if ($class) {
					$category_data[$result['category_id']] = array(
						'state'  => $state,
						'name'   => $result['name'],
						'href'   => $url->ssl('category', false, array('path' => $result['path'])),
						'class'  => $class,
						'type'   => $type,
						'level'  => ($path_count-1),
						'status' => $status,
						'sort_order' => (int)$result['sort_order'],
						'products_in_category' => $count_data[$result['category_id']]['total']
					);
					$list_data[] = $result['path'];
				}
			}

			$new_category_data = array();

			if ($list_data) {
				$rit = new Tree_Factory_List($list_data, '_');
				$tree = Tree::factory($rit);
				$tree->nodes->traverse('setTags', $category_data);
				$tree->usortNodes('cmp');
				$flatList = $tree->nodes->getFlatList();
				foreach ($flatList as $node) {
					$tag = $node->getTag();
					$new_category_data[] = $tag;
				}
			}

			$view->set('categories', $new_category_data);
			$view->set('head_def',$head_def);
			$template->set('head_def',$head_def);
			return $view->fetch('module/category.tpl');
		}
	}

	function getcount(){
		if(!$count_data = $this->cache->get('category_product')){
			$count_data = array();
			$results = $this->modelCore->get_categories();
			foreach ($results as $result) {
				$products_in_category = $this->modelCore->getPrInCat($result['category_id']);
				$count_data[$result['category_id']] = array(
					'category_id'	=> $result['category_id'],
					'total'		=> $products_in_category
				);
			}
			$this->cache->set('category_product', $count_data);
		}
		return $count_data;
	}
}

function setTags($node, $data) {
	$tag =  $data[$node->getTag()];
	$node->setTag($tag);
}

function cmp($a, $b) {
	$ret = 0;

	if ( isset($a) && isset($b) )  {
		$ax = $a->getTag();
		$bx = $b->getTag();
		
		if ($ax['sort_order'] < $bx['sort_order']) $ret = -1;
		if ($ax['sort_order'] > $bx['sort_order']) $ret = 1;
	}
	return $ret;
}
?>
