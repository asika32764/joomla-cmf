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
class TableModel extends \JModelDatabase
{
	/**
	 * dropTables
	 *
	 * @return  void
	 */
	public function dropTables()
	{
		$drops = new Registry;

		$drops->loadFile(dirname(__DIR__) . '/Resource/drops.yml', 'yaml');

		$droped = array();

		foreach ((array) $drops['table'] as $table)
		{
			// if ($this->db->dropTable($table, true))
			{
				$droped[] = $table;
			}
		}

		$this->state->set('table.droped', $droped);
	}
}
 