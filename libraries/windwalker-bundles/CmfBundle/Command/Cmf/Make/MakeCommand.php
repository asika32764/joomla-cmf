<?php
/**
 * Part of cmf project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace CmfBundle\Command\CmfBundle\Make;

use Command\User\Create\CreateCommand;
use Windwalker\Console\Command\Command;

/**
 * The MakeCommand class.
 *
 * @since  {DEPLOY_VERSION}
 */
class MakeCommand extends Command
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'make';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Make CMS to CMF';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'make <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Configure command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		$this->addOption('u')
			->alias('no-user')
			->description('Do not create user account.');

		parent::initialise();
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		if (!$this->getOption('u'))
		{
			// User create
			$userCommand = new CreateCommand('create', $this->io, $this);
			$userCommand->execute();
		}

		$extension = new ExtensionModel;
		$table = new TableModel;
		$system = new SystemModel;

		// Hack for CMS
		$app = \JFactory::getApplication();

		$_SERVER['HTTP_HOST'] = 'php://';
		\JFactory::$application = new \JApplicationAdministrator;

		// Do convert
		$this->out('Unprotect extensions...');
		$extension->unprotectAll();

		$this->out('Uninstall extensions...');
		$extension->uninstallExtensions();

		$this->out('Drop tables...');
		$table->dropTables();

		$this->out('Misc handling...');
		$system->postMake();

		// Restore
		\JFactory::$application = $app;

		// @TODO: Show information.
		/*
		print_r($extension->getState());
		print_r($table->getState());
		print_r($system->getState());
		*/

		$this->out('Make CMF success!');

		return true;
	}
}
