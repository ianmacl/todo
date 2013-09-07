<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\AbstractController;
use InvalidArgumentException;
use stdClass;

class TaskUpdate extends AbstractController
{
	public function execute()
	{
		$app = $this->getApplication();

		$task_id = $app->input->getInt('task_id');

		if (!$task_id)
		{
			throw new InvalidArgumentException('You must specify a task id.', 400);
		}

		$name = $app->input->getString('name', false);
		$completed = $app->input->getInt('completed', false);

		$task = new stdClass;

		$task->task_id = $task_id;

		if ($name)
		{
			$task->name = $name;
		}

		if ($completed !== false)
		{
			$task->completed = (int) $completed;
		}

		$db = $app->getDatabase();

		$db->updateObject('tasks', $task, 'task_id');

		$query = $db->getQuery(true);

		$query->select('*')
		->from('tasks')
		->where('task_id = ' . $task_id);

		$db->setQuery($query);

		$task = $db->loadObject();

		$this->getApplication()->setBody(json_encode($task));
	}
}
