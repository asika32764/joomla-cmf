<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace DevelopmentBundle\Seeder;

use Windwalker\Console\Command\Command;

/**
 * The AbstractSeeder class.
 * 
 * @since  2.0
 */
abstract class AbstractSeeder
{
	/**
	 * Property db.
	 *
	 * @var \JDatabaseDriver
	 */
	protected $db;

	/**
	 * Property io.
	 *
	 * @var Command
	 */
	protected $command;

	/**
	 * Class init.
	 *
	 * @param \JDatabaseDriver $db
	 * @param Command                $command
	 */
	public function __construct(\JDatabaseDriver $db = null, Command $command = null)
	{
		$this->db = $db;
		$this->command = $command;
	}

	/**
	 * execute
	 *
	 * @param AbstractSeeder|string $seeder
	 *
	 * @return  static
	 */
	public function execute($seeder = null)
	{
		if ($seeder === null)
		{
			return $this;
		}

		if (is_string($seeder))
		{
			$ref = new \ReflectionClass($this);

			include_once dirname($ref->getFileName()) . '/' . $seeder . '.php';

			$seeder = new $seeder;
		}

		$seeder->setDb($this->db)
			->setCommand($this->command);

		$this->command->out('Import seeder ' . get_class($seeder));

		$seeder->doExecute();

		return $this;
	}

	/**
	 * doExecute
	 *
	 * @return  void
	 */
	abstract public function doExecute();

	/**
	 * clear
	 *
	 * @param AbstractSeeder|string $seeder
	 *
	 * @return  static
	 */
	public function clear($seeder = null)
	{
		if ($seeder === null)
		{
			return $this;
		}

		if (is_string($seeder))
		{
			$ref = new \ReflectionClass($this);

			include_once dirname($ref->getFileName()) . '/' . $seeder . '.php';

			$seeder = new $seeder;
		}

		$seeder->setDb($this->db);
		$seeder->setCommand($this->command);

		$this->command->out('Clear seeder ' . get_class($seeder));

		$seeder->doClear();

		return $this;
	}

	/**
	 * doClear
	 *
	 * @return  void
	 */
	public function doClear()
	{
	}

	/**
	 * truncate
	 *
	 * @param $name
	 *
	 * @return  static
	 */
	public function truncate($name)
	{
		$this->db->truncateTable($name);

		return $this;
	}

	/**
	 * Method to get property Db
	 *
	 * @return  \JDatabaseDriver
	 */
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * Method to set property db
	 *
	 * @param   \JDatabaseDriver $db
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setDb(\JDatabaseDriver $db)
	{
		$this->db = $db;

		return $this;
	}

	/**
	 * Method to get property Command
	 *
	 * @return  Command
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * Method to set property command
	 *
	 * @param   Command $command
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setCommand(Command $command)
	{
		$this->command = $command;

		return $this;
	}
}
