<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later. see LICENSE
 */

namespace DevelopmentBundle\Command\Seed;

use Windwalker\Console\Command\Command;
use Windwalker\Console\Option\Option;
use DevelopmentBundle\Command\Seed\Clear\ClearCommand;
use DevelopmentBundle\Command\Seed\Import\ImportCommand;
use Windwalker\Filesystem\Filesystem;
use Windwalker\Filesystem\Folder;
use Windwalker\Helper\PathHelper;
use Windwalker\String\StringNormalise;
use Windwalker\System\ExtensionHelper;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * Class Seed
 */
class SeedCommand extends Command
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
	protected $name = 'seed';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'The data seeder help you create fake data.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'seed <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Initialise command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		$this->addCommand(new ImportCommand);
		$this->addCommand(new ClearCommand);

		$this->addGlobalOption('c')
			->alias('client')
			->description('Site or Administrator');

		$this->addGlobalOption('C')
			->alias('class')
			->defaultValue('DatabaseSeeder')
			->description('Class name');
	}

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$element = $this->getArgument(1);
		$client = strtolower($this->getOption('c'));
		$class = $this->getOption('class');

		if ($element)
		{
			list($type, $name, $group) = array_values(ExtensionHelper::extractElement($element));

			if ($client == 'admin')
			{
				$client = 'administrator';
			}

			if ($type == 'plugin')
			{
				$client = 'site';
			}
			elseif ($type == 'component')
			{
				$client = 'administrator';
			}
		}

		$path = JPATH_ROOT . '/resources/seeders';

		$classPath = $path . '/' . $class . '.php';

		if (!file_exists($classPath) && $element)
		{
			$path = PathHelper::get($element, $client);

			$classPath = $path . '/src/' . ucfirst($name) . '/Seed/' . $class . '.php';
		}

		if (file_exists($classPath))
		{
			include_once $classPath;
		}

		$className = $class;

		if (!class_exists($className))
		{
			$className = sprintf('%s\Seed\%s', ucfirst($name), ucfirst($class));
		}

		if (!class_exists($className))
		{
			throw new \UnexpectedValueException('Class: ' . $class . ' not found.');
		}

		// Auto include classes
		$path = dirname(ReflectionHelper::getPath($className));

		$files = \JFolder::files($path, '.', false, true);

		/** @var \SplFileInfo $file */
		foreach ($files as $file)
		{
			$file = new \SplFileInfo($file);

			\JLoader::register($file->getBasename('.php'), $file->getPathname());
		}
		
		$this->app->set('seed.class', $className);
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		return parent::doExecute();
	}
}
