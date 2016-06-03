<?php
/**
 * Part of cmf project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace CmfBundle\Command\Cmf;

use CmfBundle\Command\Cmf\Make\MakeCommand;
use Windwalker\Console\Command\Command;

/**
 * The CmfCommand class.
 *
 * @since  {DEPLOY_VERSION}
 */
class CmfCommand extends Command
{
	/**
	 * Property isEnabled.
	 *
	 * @var  bool
	 */
	public static $isEnabled = true;

	/**
	 * Property name.
	 *
	 * @var  string
	 */
	public $name = 'cmf';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	public $description = 'Cmf maker.';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addCommand(new MakeCommand);

		parent::initialise();
	}
}
