<?php
/**
 * Part of rad project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace DevelopmentBundle\Command\Dev\Indexmaker;

use Windwalker\Console\Command\Command;

/**
 * The IndexCommand class.
 *
 * @since  __DEPLOY_VERSION__
 */
class IndexmakerCommand extends Command
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Property name.
	 *
	 * @var  string
	 */
	public $name = 'make-index';

	/**
	 * Property usage.
	 *
	 * @var  string
	 */
	protected $usage = 'make-index <cmd><path></cmd> <option>[options]</option>';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	public $description = 'Create empty index.html files in directories.';

	/**
	 * configure
	 *
	 * @return  void
	 */
	public function initialise()
	{
		parent::initialise();
	}
	/**
	 * doExecute
	 *
	 * @return  int|void
	 */
	public function doExecute()
	{
		$path = $this->getArgument(0) ? JPATH_BASE . '/' . $this->getArgument(0) : JPATH_BASE;

		if (!is_dir($path))
		{
			$this->out('Path not found: ' . $path)->app->close();
		}

		$this->out('Scaning dirs...');

		$count = $this->createIndex($path);

		if ($count)
		{
			$this->out('OK! ' . $count . ' files created.');
		}
		else
		{
			$this->out('No file created.');
		}
	}
	/**
	 * createIndex
	 *
	 * @param string $dist
	 *
	 * @return  int
	 */
	protected function  createIndex($dist)
	{
		// From: https://github.com/joomla/joomla-cms/blob/master/build/indexmaker.php
		$iterator  = new \RecursiveDirectoryIterator(
			$dist,
			\FilesystemIterator::KEY_AS_PATHNAME
			| \FilesystemIterator::CURRENT_AS_FILEINFO
			| \FilesystemIterator::SKIP_DOTS
		);

		$flattened = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
		$count = 0;

		foreach ($flattened as $path => $dir)
		{
			if (!$dir->isDir())
			{
				continue;
			}

			// Add an index.html if neither an index.html nor an index.php exist
			if (!(file_exists($path . '/index.html') || file_exists($path . '/index.php')))
			{
				$this->out('File created: ' . $path . '/index.html');
				$count++;
				file_put_contents($path . '/index.html', '<!DOCTYPE html><title></title>' . "\n");
			}
		}

		return $count;
	}
}
