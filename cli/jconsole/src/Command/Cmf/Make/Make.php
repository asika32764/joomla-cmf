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

use Cmf\Model\Extension;
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
		// $this->addCommand();

		parent::configure();
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		$model = new Extension;

		$this->out('Unprotect extensions...');
		$model->unprotectAll();

		$this->out('Uninstall extensions...');
		$model->uninstallExtensions();

		print_r($model->getState());
	}
}
