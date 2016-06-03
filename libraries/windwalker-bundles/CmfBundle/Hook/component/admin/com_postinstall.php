<?php
/**
 * Part of cmf2 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $db JDatabaseDriver */
$db->dropTable('#__postinstall_messages');

$file  = JPATH_ADMINISTRATOR . '/components/com_cpanel/views/cpanel/view.html.php';
$regex = '/\/\/\sLoad the RAD(.*)count\(\$messages\)\;/ms';
$replace = <<<RP
// Removed for CMF';
\$this->postinstall_message_count = 0;
RP;

$content = preg_replace($regex, $replace, file_get_contents($file));
\JFile::write($file, $content);
