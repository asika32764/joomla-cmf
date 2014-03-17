<?php
/**
 * Part of cmf2 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $db JDatabaseDriver */
$db->dropTable('#__postinstall_messages');

$file  = JPATH_ADMINISTRATOR . '/components/com_postinstall/views/view.html.php';
$regex = '/^\/\/\sLoad the RAD(.*)count\(\$messages\)\;/m';

$content = preg_replace($regex, file_get_contents($file), '');
\JFile::write($file, $content);
