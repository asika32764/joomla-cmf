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

$file  = JPATH_LIBRARIES . '/cms/application/administrator.php';
$regex = '/public static function purgeMessages\(\)\s*\{/ms';
$replace = "$0\n\t\t// Hack for CMF\n\t\treturn;";

$content = preg_replace($regex, $replace, file_get_contents($file));
\JFile::write($file, $content);
