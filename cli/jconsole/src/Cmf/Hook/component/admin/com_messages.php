<?php
/**
 * Part of cmf2 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $db JDatabaseDriver */
$db->dropTable('#__messages');
$db->dropTable('#__messages_cfg');

// Remove JApplicationCms::purgeMessages()
$file  = JPATH_LIBRARIES . '/cms/application/administrator.php';
$regex = '/public static function purgeMessages\(\)\s*\{/ms';
$replace = "$0\n\t\t// Hack for CMF\n\t\treturn;";

$content = preg_replace($regex, $replace, file_get_contents($file));
\JFile::write($file, $content);

// Remove JTableUser db queries when deleting users.
$file  = JPATH_LIBRARIES . '/joomla/table/user.php';
$find = "\$query->clear()
			->delete(\$this->_db->quoteName('#__messages_cfg'))
			->where(\$this->_db->quoteName('user_id') . ' = ' . (int) \$this->\$k);
		\$this->_db->setQuery(\$query);
		\$this->_db->execute();

		\$query->clear()
			->delete(\$this->_db->quoteName('#__messages'))
			->where(\$this->_db->quoteName('user_id_to') . ' = ' . (int) \$this->\$k);
		\$this->_db->setQuery(\$query);
		\$this->_db->execute();";

$replace = "// Hack for CMF";

$content = str_replace($find, $replace, file_get_contents($file));
\JFile::write($file, $content);
