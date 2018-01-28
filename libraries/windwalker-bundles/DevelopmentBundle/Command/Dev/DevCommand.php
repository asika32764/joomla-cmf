<?php
/**
 * Part of rad project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace DevelopmentBundle\Command\Dev;

use DevelopmentBundle\Command\Dev\Constant\ConstantCommand;
use DevelopmentBundle\Command\Dev\Gpl\GplCommand;
use DevelopmentBundle\Command\Dev\Indexmaker\IndexmakerCommand;
use DevelopmentBundle\Command\Dev\Init\InitCommand;
use Windwalker\Console\Command\Command;

/**
 * The CheckCommand class.
 *
 * @since  __DEPLOY_VERSION__
 */
class DevCommand extends Command
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
	protected $name = 'dev';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Some useful development tools';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'dev <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Initialise command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		parent::initialise();

		$this->addCommand(new InitCommand);
		$this->addCommand(new IndexmakerCommand);
		$this->addCommand(new ConstantCommand);
		$this->addCommand(new GplCommand);
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		parent::doExecute();
	}
}
