<?php // Category Menu AlegroCart
require_once('library/Tree.php');
require_once('library/Tree/Factory/List.php');

class ModuleCategory extends Controller
{
	function fetch()
	{
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');       
		$head_def =& $this->locator->get('HeaderDefinition'); 
		$this->modelCore = $this->model->get('model_core'); 
		if ($config->get('category_status'))
		{
			$language->load('extension/module/category.php');

			$view = $this->locator->create('template');

			$view->set('heading_title', $language->get('heading_title'));

			$category_data = array();
			
			$list_data = array();
			if (isset($_GET['path'])) {$pathlvl = $_GET['path'];} else {$pathlvl = 0;}
			
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
						'href'   => $url->href('category', false, array('path' => $result['path'])),
						'class'  => $class,
						'type'   => $type,
						'level'  => ($path_count-1),
						'status' => $status,
						'sort_order' => (int)$result['sort_order'],
					);
					$list_data[] = $result['path'];
				}
			}
			
			$new_category_data = array();

			if ($list_data)
			{
				$rit = new Tree_Factory_List($list_data, '_');
				$tree = Tree::factory($rit);
				$tree->nodes->traverse('setTags', $category_data);
				$tree->usortNodes('cmp');
				$flatList = $tree->nodes->getFlatList();
				foreach ($flatList as $node)
				{
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