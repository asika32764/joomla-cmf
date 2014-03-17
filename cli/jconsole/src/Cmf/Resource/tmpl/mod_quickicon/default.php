<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_quickicon
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$icons = ModQuickIconHelper::groupButtons($buttons);

unset($icons['MOD_QUICKICON_CONTENT'][0]);
unset($icons['MOD_QUICKICON_CONTENT'][1]);
unset($icons['MOD_QUICKICON_CONTENT'][2]);

$html = JHtml::_('links.linksgroups', $icons);
?>
<?php if (!empty($html)) : ?>
	<div class="sidebar-nav quick-icons">
		<?php echo $html;?>
	</div>
<?php endif;?>
