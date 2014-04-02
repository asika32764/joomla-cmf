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
class ExtensionModel extends \JModelDatabase
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
		parent::__construct();

		$this->installer = new \JInstaller;

		$this->extensions = $this->loadExtensions();

		$this->hook = new HookModel;
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
			->set('protected = 0');

		$this->db->setQuery($query)->execute();

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

		// Extension type
		foreach ($uninstalls->toArray() as $type => $clients)
		{
			// Site => 0 or Admin => 1
			foreach ((array) $clients as $client => $extensions)
			{
				$uninstalled = array();

				// Extensions
				foreach ((array) $extensions as $extName)
				{
					// Get extension information
					$ext = $this->queryExtension($type, $extName, $client);

					if (empty($ext->extension_id))
					{
						continue;
					}

					if ($this->installer->uninstall($type, $ext->extension_id))
					{
						$this->postInstall($type, $extName, $client);

						$uninstalled[] = $extName;
					}
				}

				// Analyze
				$position = $client ? 'admin' : 'site';

				$this->state->set('uninstall.' . $type . '.' . $position, $uninstalled);
			}
		}
	}

	/**
	 * queryExtension
	 *
	 * @param string $type
	 * @param string $element
	 * @param int    $client
	 * @param string $group
	 *
	 * @return  mixed
	 */
	protected function queryExtension($type, $element, $client = 1, $group = null)
	{
		foreach ($this->extensions as $ext)
		{
			if ($ext->name == $element && $ext->type == $type && $ext->client_id == $client)
			{
				return $ext;
			}
		}

		return new \stdClass;
	}

	/**
	 * loadExtensions
	 *
	 * @return  mixed
	 */
	protected function loadExtensions()
	{
		$query = $this->db->getQuery(true)
			->select('*')
			->from('#__extensions');

		return $this->db->setQuery($query)->loadObjectList();
	}

	/**
	 * postInstall
	 *
	 * @param string $type
	 * @param string $extension
	 * @param int    $client
	 *
	 * @return  boolean
	 */
	protected function postInstall($type, $extension, $client)
	{
		return $this->hook->execute($type, $extension, $client);
	}
}
 