<?php
/**
 * Part of cmf2 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Cmf\Model;

/**
 * Class Hook
 *
 * @since 1.0
 */
class HookModel extends \JModelDatabase
{
	/**
	 * execute
	 *
	 * @param string $type
	 * @param string $extension
	 * @param int    $client
	 *
	 * @return  bool
	 */
	public function execute($type, $extension, $client)
	{
		$clientName = $client ? 'admin' : 'site';

		$file = dirname(__DIR__) . '/Hook/' . $type . '/' . $clientName . '/' . $extension . '.php';

		if (is_file($file))
		{
			$db = $this->db;

			include $file;
		}

		return true;
	}
}
