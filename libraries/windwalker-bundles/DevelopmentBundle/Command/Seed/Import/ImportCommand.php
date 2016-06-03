<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later. see LICENSE
 */

namespace DevelopmentBundle\Command\Seed\Import;

use DevelopmentBundle\Seeder\AbstractSeeder;
use Windwalker\Console\Command\Command;
use Windwalker\Core\Ioc;
use Windwalker\Core\Migration\Model\BackupModel;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Mvc\MvcHelper;
use DevelopmentBundle\Sqlsync\Model\SchemaModel;
use Windwalker\String\StringNormalise;

/**
 * Class Seed
 */
class ImportCommand extends Command
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
	protected $name = 'import';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Import seeders.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'import <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Initialise command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		parent::initialise();

		$this->addOption('no-backup')
			->description('Do not backup database.');
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
//		if (!$this->io->getOption('no-backup'))
//		{
//			// backup
//			$model = new SchemaModel;
//
//			$model->backup();
//		}

		$class = $this->app->get('seed.class');

		/** @var AbstractSeeder $seeder */
		$seeder = new $class(\JFactory::getDbo(), $this);

		$seeder->doExecute();

		return true;
	}
}
