<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\Base;

class TaskListGet extends Base
{
	public function execute()
	{
		$tasks = array();

		$db = $this->getApplication()->getDatabase();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('tasks');

		$db->setQuery($query);

		$tasks = $db->loadObjectList();

		$this->getApplication()->setBody(json_encode($tasks));
	}
}