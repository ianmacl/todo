<?php

namespace Todo\Services\Test;

use Joomla\Controller\AbstractController;

class TestGet extends AbstractController
{
	public function execute()
	{
		echo json_encode('OK');
	}
}
