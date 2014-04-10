<?php
/**
 * Part of cmf2 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $db JDatabaseDriver */
$db->dropTable('#__content');
// $db->dropTable('#__contentitem_tag_map');
$db->dropTable('#__content_frontpage');
$db->dropTable('#__content_rating');
