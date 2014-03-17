<?php
/**
 * Part of cmf2 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Cmf\Model;

use Joomla\Registry\Registry;

/**
 * Class Extension
 *
 * @since 1.0
 */
class SystemModel extends \JModelBase
{
	/**
	 * postMake
	 *
	 * @return  void
	 */
	public function postMake()
	{
		$this->copyTemplates();

		$this->addBacktrace();
	}

	/**
	 * copyTemplates
	 *
	 * @return  void
	 */
	protected function copyTemplates()
	{
		$dir = dirname(__DIR__) . '/Resource/tmpl';
		$tpl = JPATH_ADMINISTRATOR . '/templates/isis';

		$folders = \JFolder::folders($dir);

		$copied = array();

		foreach ($folders as $folder)
		{
			if (\JFolder::copy($dir . '/' . $folder, $tpl . '/html/' . $folder))
			{
				$copied[] = $tpl . '/html/' . $folder;
			}
		}

		$this->state->set('system.tmpl.copied', $copied);
	}

	/**
	 * addBacktrace
	 *
	 * @return  void
	 */
	protected function addBacktrace()
	{
		$file = dirname(__DIR__) . '/Resource/misc/layout/backtrace.php';
		$code = file_get_contents($file);

		$file = JPATH_ADMINISTRATOR . '/templates/isis/error.php';
		$error = file_get_contents($file);

		$error = str_replace('<!-- End Content -->', "\n" . $code . "\n\t\t\t\t\t\t<!-- End Content -->", $error);

		\JFile::write($file, $error);
	}
}
 