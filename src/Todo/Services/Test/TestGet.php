<?php

namespace Todo\Services\Test;

use Joomla\Controller\Base;

class TestGet extends Base
{
	public function execute()
	{
		echo json_encode('OK');
	}
}