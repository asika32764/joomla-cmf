<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DevelopmentBundle\Sqlsync\Importer;

use DevelopmentBundle\Sqlsync\Helper\AbstractQueryHelper;

/**
 * Class AbstractImporter
 */
abstract class AbstractImporter extends \JModelDatabase
{
	/**
	 * @var array
	 */
	static protected $instance = array();

	/**
	 * @var AbstractQueryHelper
	 */
	protected $queryHelper;

	/**
	 * @var int
	 */
	protected $tableCount = 0;

	/**
	 * @var int
	 */
	protected $rowCount = 0;

	/**
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * getInstance
	 *
	 * @param string $type
	 *
	 * @return mixed
	 */
	static public function getInstance($type = 'yaml')
	{
		if (!empty(self::$instance[$type]))
		{
			return self::$instance[$type];
		}

		$class = 'DevelopmentBundle\Sqlsync\\Importer\\' . ucfirst($type) . 'Importer';

		return self::$instance[$type] = new $class;
	}

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->queryHelper = AbstractQueryHelper::getInstance($this->db->name);
	}

	/**
	 * import
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	abstract public function import($content);

	/**
	 * execute
	 *
	 * @param string $sql
	 *
	 * @return bool|mixed
	 */
	protected function execute($sql)
	{
		try
		{
			return $this->debug ? false : $this->db->setQuery($sql)->execute();
		}
		catch (\RuntimeException $e)
		{
			throw new $e(
				sprintf("%s\n\n[SQL]: %s", $e->getMessage(), $sql),
				$e->getCode(),
				$e
			);
		}
	}
}
