<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Command\Cmf\Make;

defined('JCONSOLE') or die;

use Cmf\Model\ExtensionModel;
use Cmf\Model\SystemModel;
use Cmf\Model\TableModel;
use Command\User\Create\Create;
use JConsole\Command\JCommand;

/**
 * Class Make
 *
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @since       3.2
 */
class Make extends JCommand
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
	public function configure()
	{
		$this->addOption(array('u', 'no-user'), 0, 'Do not create user account.');

		parent::configure();
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
			$userCommand = new Create('create', $this->input, $this->output, $this);
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
