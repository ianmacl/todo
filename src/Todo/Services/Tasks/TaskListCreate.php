<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\Base;
use stdClass;

class TaskListCreate extends \Joomla\Controller\Base
{
	public function execute()
	{
		$task = new stdClass;

		$task->name = $this->getApplication()->input->getString('name');
		$task->completed = 0;

		$db = $this->getApplication()->getDatabase();

		$db->insertObject('tasks', $task, 'task_id');

		$this->getApplication()->setBody(json_encode($task));
		$this->getApplication()->setHeader('status', '201 Created');
	}
}