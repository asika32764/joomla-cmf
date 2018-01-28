<?php
/**
 * @package     Joomla.Cli
 * @subpackage  JConsole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DevelopmentBundle\Sqlsync;

use Joomla\Registry\Registry;
use DevelopmentBundle\Sqlsync\Registry\Format\Json;

/**
 * Class Config
 */
class Config extends Registry
{
	/**
	 * Constructor
	 *
	 * @param   mixed $data The data to bind to the new Registry object.
	 *
	 * @since   1.0
	 */
	public function __construct($data = null)
	{
		parent::__construct($data);

		// Workaround to fix Joomla 3.6.4 bug, @see https://github.com/ventoviro/rad-development-bundle/pull/10
		$this->initialized = true;
	}

	/**
	 * Get a namespace in a given string format
	 *
	 * @param   string  $format   Format to return the string in
	 * @param   mixed   $options  Parameters used by the formatter, see formatters for more info
	 *
	 * @return  string   Namespace in string format
	 *
	 * @since   1.0
	 */
	public function toString($format = 'JSON', $options = array())
	{
		if (strtolower($format) == 'json')
		{
			$handler = new Json;
		}
		else
		{
			$handler = AbstractRegistryFormat::getInstance($format);
		}

		return $handler->objectToString($this->data, $options);
	}
}
