<?php
/**
 * Part of rad project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace DevelopmentBundle\Command\Dev\Gpl;

use Windwalker\Console\Command\Command;

/**
 * The ConstantCommand class.
 *
 * @since  __DEPLOY_VERSION__
 */
class GplCommand extends Command
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
	protected $name = 'check-gpl';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Check php files which do not included GPL docblock.';

	/**
	 * Property usage.
	 *
	 * @var  string
	 */
	protected $usage = 'check-gpl <cmd><path></cmd> <option>[options]</option>';

	/**
	 * Property constants.
	 *
	 * @var  array
	 */
	protected $licenses = array(
		'BSD'
	);

	/**
	 * Property dump.
	 *
	 * @var  bool
	 */
	protected $dump = false;

	/**
	 * Property fileName.
	 *
	 * @var  string
	 */
	protected $fileName = 'dev.missing.gpl.php';

	/**
	 * configure
	 *
	 * @return  void
	 */
	public function initialise()
	{
		$this->addOption(
			array('d', 'dump'),
			true,
			'Dump to file, the log file will save in {ROOT}/logs.'
		);

		parent::initialise();
	}

	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		if ($this->getOption('d'))
		{
			\JFile::delete($this->app->get('log_path') . '/' . $this->fileName);

			\JLog::addLogger(
				array(
					//Sets file name
					'text_file' => $this->fileName
				),
				//Sets all JLog messages to be set to the file
				\JLog::ALL,
				//Chooses a category name
				'dev'
			);
			
			$this->dump = true;
		}

		$path = $this->getArgument(0) ? JPATH_BASE . '/' . $this->getArgument(0) : JPATH_BASE;

		$this->out('Scaning files...');

		$count = $this->checkFiles($path);

		$this->out();

		if (!$count)
		{
			$this->out('All files included constants.');
		}
		else
		{
			$this->out($count . ' files need constants.');

			if ($this->dump)
			{
				$this->out('Dumped log to ' . $this->app->get('log_path') . '/' . $this->fileName);
			}
		}
	}

	/**
	 * checkFiles
	 *
	 * @param string $dist
	 *
	 * @return  int
	 */
	protected function  checkFiles($dist)
	{
		// From: https://github.com/joomla/joomla-cms/blob/master/build/indexmaker.php
		$iterator  = new \RecursiveDirectoryIterator($dist, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS);
		$flattened = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
		$count = 0;

		foreach ($flattened as $path => $dir)
		{
			if ($dir->isDir() || $dir->getExtension() !== 'php')
			{
				continue;
			}

			$content = file($path);
			$hascode = $this->checkFile($content);

			if (!$hascode)
			{
				$count++;
				$this->out($path);

				if ($this->dump)
				{
					\JLog::add($path, \JLog::INFO, 'dev');
				}
			}
		}
		
		return $count;
	}

	/**
	 * checkFile
	 *
	 * @param array $content
	 *
	 * @return  bool
	 */
	protected function checkFile($content)
	{
		$hascode = 0;

		foreach ($content AS $key => $line)
		{
			$tline = trim($line);

			if ($tline === '' || $tline === '<?php' || $tline === '?>')
			{
				continue;
			}

			if ($tline[0] !== '/' && $tline[0] !== '*')
			{
				$hascode = 1;
			}

			// Search for GPL license
			$gpl = stripos($line, 'GPL');
			$gnu = stripos($line, 'GNU');
			$gpl_long = stripos($line, 'general public license');

			if ($gpl || $gnu || $gpl_long)
			{
				return true;
			}

			// Search for the constant name
			foreach ($this->licenses AS $license)
			{
				$license = trim($license);

				// Search for the license
				$found = strpos($line, $license);

				// Skip the line if the license is not found
				if ($found === false)
				{
					continue;
				}
				else
				{
					return true;
				}
			}
		}

		unset($content);

		return $hascode ? false : true;
	}
}
