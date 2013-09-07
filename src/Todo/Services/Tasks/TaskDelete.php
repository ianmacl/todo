<?php

namespace Todo\Services\Tasks;

use Joomla\Controller\AbstractController;
use RuntimeException;
use InvalidArgumentException;

class TaskDelete extends AbstractController
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

		$query->delete('tasks')
			->where('task_id = ' . $task_id);

		$db->setQuery($query);

		$result = $db->execute();

		if ($result && $db->getAffectedRows())
		{
			$this->getApplication()->setHeader('status', '204 Deleted');
		}
		elseif ($result)
		{
			throw new RuntimeException('Record Not Found', 404);
		}
		else
		{
			throw new RuntimeException('Delete failed', 500);
		}
	}
}
