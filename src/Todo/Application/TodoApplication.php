<?php

namespace Todo\Application;

use Joomla\Application\Web;
use Joomla\Router\Router;
use Joomla\Router\Rest;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Database\Driver;

class TodoApplication extends Web
{
	/**
	 * @var    Joomla\Database\Driver  A database object for the application to use.
	 * @since  1.0
	 */
	protected $db;

	/**
	 * @var    Joomla\Router\Rest  A REST router object.
	 * @since  1.0
	 */
	protected $router;

	/**
	 * Overrides the parent constructor to set the execution start time.
	 *
	 * @param   mixed  $input   An optional argument to provide dependency injection for the application's
	 *                          input object.  If the argument is a JInput object that object will become
	 *                          the application's input object, otherwise a default input object is created.
	 * @param   mixed  $config  An optional argument to provide dependency injection for the application's
	 *                          config object.  If the argument is a JRegistry object that object will become
	 *                          the application's config object, otherwise a default config object is created.
	 *
	 * @since   11.3
	 */
	public function __construct(Input $input = null, Registry $config = null)
	{
		parent::__construct($input, $config);

		$this->config->loadObject($this->fetchConfigurationData());
	}

	/**
	 * Allows the application to load a custom or default router.
	 *
	 * @param   JApplicationWebRouter  $router  An optional router object. If omitted, the standard router is created.
	 *
	 * @return  JApplicationWeb This method is chainable.
	 *
	 * @since   1.0
	 */
	public function loadRouter(Router $router = null)
	{
		$this->router = ($router === null) ? new Rest($this->input) : $router;

		return $this;
	}

	/**
	 * Allows the application to load a custom or default database object.
	 *
	 * @param   Joomla\Database\Driver  $driver  An optional database driver object. If omitted, the application driver is created.
	 *
	 * @return  Todo\Application\WebApplication  This method is chainable.
	 */
	public function loadDatabase(Driver $driver = null)
	{
		if ($driver === null)
		{
			$config = $this->get('database');

			if ($config->driver == 'sqlite' && substr($config->database, 0, 1) != '/')
			{
				$config->database = JPATH_CONFIGURATION . '/' . $config->database;
			}

			$this->db = Driver::getInstance((array)$config);

			// Set the debug flag.
			$this->db->setDebug($this->get('debug', false));

			// Select the database.
			$this->db->select($this->get('database.database'));
		}
		// Use the given database driver object.
		else
		{
			$this->db = $driver;
		}

		return $this;
	}

	/**
	 * Retrieves the database object.
	 *
	 * @return   Joomla\Database\Driver  The database driver object.
	 */
	public function getDatabase()
	{
		return $this->db;
	}

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Set the controller prefix, add maps, and execute the appropriate controller.
			$this->router->setControllerPrefix('\\Todo\\Services')
				->setDefaultController('\\Test\\Test')
				->addMaps(json_decode(file_get_contents(JPATH_CONFIGURATION . '/routes.json'), true))
				->getController($this->get('uri.route'))
				->setApplication($this)
				->execute();
		}
		catch (Exception $e)
		{
			$this->setHeader('status', '400', true);
			$message = $e->getMessage();
			$body = array('message' => $message, 'code' => $e->getCode(), 'type' => get_class($e));

			if ($this->get('debug', false))
			{
				$backtrace = $e->getTrace();
				$trace = array();

				for ($i = count($backtrace) - 1; $i >= 0; $i--)
				{
					$line = '';

					if (isset($backtrace[$i]['class']))
					{
						$line .= sprintf("%s %s %s()", $backtrace[$i]['class'], $backtrace[$i]['type'], $backtrace[$i]['function']);
					}
					else
					{
						$line .= sprintf("%s()", $backtrace[$i]['function']);
					}

					if (isset($backtrace[$i]['file']))
					{
						$line .= sprintf(' @ %s:%d', $backtrace[$i]['file'], $backtrace[$i]['line']);
					}
					$trace[] = $line;
				}
				$body['trace'] = $trace;
			}
			$this->setBody(json_encode($body));
		}
	}

	/**
	 * Method to get the application configuration data to be loaded.
	 *
	 * @return  object An object to be loaded into the application configuration.
	 *
	 * @since   1.0
	 */
	protected function fetchConfigurationData()
	{
		// Instantiate variables.
		$config = array();

		// Set the configuration file path for the application.
		if (file_exists(JPATH_CONFIGURATION . '/config.json'))
		{
			$file = JPATH_CONFIGURATION . '/config.json';
		}
		else
		{
			$file = JPATH_CONFIGURATION . '/config.dist.json';
		}

		if (!is_readable($file))
		{
			throw new RuntimeException('Configuration file does not exist or is unreadable.');
		}

		// Load the configuration file into an object.
		$config = json_decode(file_get_contents($file));

		return $config;
	}
}