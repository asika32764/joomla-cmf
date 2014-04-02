<?php
/**
 * Part of cmf2 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Cmf\Model;

/**
 * Class Extension
 *
 * @since 1.0
 */
class SystemModel extends \JModelDatabase
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

		$this->installDevPlugin();
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
			if (\JFolder::copy($dir . '/' . $folder, $tpl . '/html/' . $folder, '', true))
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

		$error = str_replace(
			"<?php echo JText::_('JGLOBAL_TPL_CPANEL_LINK_TEXT'); ?></a></p>",
			"<?php echo JText::_('JGLOBAL_TPL_CPANEL_LINK_TEXT'); ?></a></p>\n\n<!-- Backtrace for CMF -->\n" . $code . "\n",
			$error
		);

		\JFile::write($file, $error);
	}

	/**
	 * installDevPlugin
	 *
	 * @return  void
	 */
	protected function installDevPlugin()
	{
		$installer = new \JInstaller;

		$installer->install(dirname(__DIR__) . '/Resource/misc/plugin/system/dev');

		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set("enabled = 1")
			->where("`name` = 'plg_system_dev'");

		$this->db->setQuery($query)->execute();
	}
}
 