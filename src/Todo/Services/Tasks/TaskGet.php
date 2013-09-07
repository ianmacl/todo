<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\AbstractController;
use InvalidArgumentException;

class TaskGet extends AbstractController
{
	public function execute()
	{
		$task_id = $this->getApplication()->input->getInt('task_id');

		if (!$task_id)
		{
			throw new InvalidArgumentException('You must specify a task id.', 400);
		}

		$db = $this->getApplication()->getDatabase();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('tasks')
			->where('task_id = ' . $task_id);

		$db->setQuery($query);

		$task = $db->loadObject();

		$this->getApplication()->setBody(json_encode($task));
	}
}
