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
class Extension extends \JModelDatabase
{
	/**
	 * Property installer.
	 *
	 * @var  \JInstaller
	 */
	protected $installer = null;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->installer = new \JInstaller;
	}

	/**
	 * unprotectAll
	 *
	 * @return  $this
	 */
	public function unprotectAll()
	{
		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set('protected', 0);

		// $this->db->setQuery($query)->execute();

		return $this;
	}

	/**
	 * uninstallExtensions
	 *
	 * @return  void
	 */
	public function uninstallExtensions()
	{
		$uninstalls = new Registry;

		$uninstalls->loadFile(dirname(__DIR__) . '/Resource/uninstalls.yml', 'yaml');

		$this->unstallComponent('component', $uninstalls['component']);
	}

	/**
	 * unstallComponent
	 *
	 * @param $type
	 * @param $extensions
	 *
	 * @return  void
	 */
	protected function unstallComponent($type, $extensions)
	{
		foreach ($extensions as $ext)
		{
			$this->installer->uninstall($type, $ext);
		}
	}
}
 