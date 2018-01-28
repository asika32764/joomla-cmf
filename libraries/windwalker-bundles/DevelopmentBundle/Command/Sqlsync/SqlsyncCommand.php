<?php

namespace DevelopmentBundle\Command\Sqlsync;

use DevelopmentBundle\Command\Sqlsync\Backup\BackupCommand;
use DevelopmentBundle\Command\Sqlsync\Column\ColumnCommand;
use DevelopmentBundle\Command\Sqlsync\Export\ExportCommand;
use DevelopmentBundle\Command\Sqlsync\Import\ImportCommand;
use DevelopmentBundle\Command\Sqlsync\Profile\ProfileCommand;
use DevelopmentBundle\Command\Sqlsync\Restore\RestoreCommand;
use DevelopmentBundle\Command\Sqlsync\Table\TableCommand;
use Windwalker\Console\Command\Command;
use Symfony\Component\Yaml\Dumper as SymfonyYamlDumper;

class SqlsyncCommand extends Command
{
	public $name = 'sql';

	public $description = 'SQL sync & diff tools.';

	public static $isEnabled = true;

	//        public $usage = 'example <command> [option]';

	/**
	 * Initialise command.
	 *
	 * @return void
	 *
	 * @since  2.0
	 */
	protected function initialise()
	{
		$this->addCommand(new BackupCommand);
		$this->addCommand(new ColumnCommand);
		$this->addCommand(new ExportCommand);
		$this->addCommand(new ImportCommand);
		$this->addCommand(new ProfileCommand);
		$this->addCommand(new RestoreCommand);
		$this->addCommand(new TableCommand);

		$this->addGlobalOption('y')
			->alias('assume-yes')
			->defaultValue(0)
			->description('Ignore confirm prompter.');
	}

	/**
	 * execute
	 *
	 * @return  mixed
	 */
	public function execute()
	{
		define('SQLSYNC_COMMAND',  __DIR__);

		define('SQLSYNC_RESOURCE', JPATH_ROOT . '/resources/sqlsync');

		define('SQLSYNC_PROFILE',  SQLSYNC_RESOURCE);

		define('SQLSYNC_LIB',      DEVELOPMENT_BUNDLE_ROOT . '/Sqlsync');

		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		// Create DB first
		$this->initDb();

		return parent::execute();
	}

	/**
	 * initDb
	 *
	 * @return  void
	 */
	protected function initDb()
	{
		$config = \JFactory::getConfig();

		$host = $config->get('host');
		$user = $config->get('user');
		$password = $config->get('password');
		$database = $config->get('db');
		$prefix = $config->get('dbprefix');
		$driver = $config->get('dbtype');

		$options = array('driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'prefix' => $prefix);

		$options['db_select'] = false;

		$db = \JDatabaseDriver::getInstance($options);

		$dbs = $db->setQuery('SHOW DATABASES')->loadColumn();
		
		if (!in_array($database, $dbs))
		{
			$db->setQuery('CREATE DATABASE `' . $database . '` CHARACTER SET `utf8`')->execute();
		}

		$db->disconnect();
	}
}
