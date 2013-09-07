<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\AbstractController;

class TaskListGet extends AbstractController
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
