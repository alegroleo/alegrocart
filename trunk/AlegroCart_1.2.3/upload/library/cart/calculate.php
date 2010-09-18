<?php
class Calculate
{
	var $data = array();

	function __construct(&$locator)
	{
		$this->database =& $locator->get('database');
		$this->config   =& $locator->get('config');
		
		$results = $this->database->getRows("select * from extension where type = 'calculate'");
		foreach ($results as $result)
		{
			$file  = DIR_EXTENSION . $result['directory'] . '/' . $result['filename'];
			$class = 'Calculate' . str_replace('_', '', $result['code']);

			if (file_exists($file) && $this->config->get($result['code'] . '_status'))
			{
				require_once($file);
				$this->data[$result['code']] = new $class($locator);
			}
		}
	}

	function getTotals()
	{
		$sort_data = array();

		foreach (array_keys($this->data) as $key)
		{
			$order = $this->data[$key]->getSortOrder();
			$sort_data[] = array('order' => $order, 'key' => $key);
		}

		$sorted = $this->msort($sort_data, "order");
		$total_data = array();
		$i = 0;
		foreach ($sorted as  $key =>$calculator)
		{
			$results = $this->data[$calculator['key']]->calculate();
			foreach ($results as $result)
			{
				$total_data[] = array(
					'title'      => $result['title'],
					'text'       => $result['text'],
					'value'      => $result['value'],
					'sort_order' => $i
				);
				$i++;
			}
		}

		return $total_data;
	}

	function msort($array, $id="id", $sort_ascending=true)
	{
		$temp_array = array();
		while(count($array)>0)
		{
			$lowest_id = 0;
			$index=0;
			foreach ($array as $item)
			{
				if (isset($item[$id]))
				{
					if ($array[$lowest_id][$id])
					{
						if ($item[$id]<$array[$lowest_id][$id])
						{
							$lowest_id = $index;
						}
					}
				}
				$index++;
			}
			$temp_array[] = $array[$lowest_id];
			$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
		}
		if ($sort_ascending)
		{
			return $temp_array;
		}
		else
		{
			return array_reverse($temp_array);
		}
	}
}
?>