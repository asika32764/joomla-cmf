<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Windwalker\DI\Container;
use Windwalker\Helper\LanguageHelper;
use Windwalker\Helper\ModalHelper;
use Windwalker\Script\WindwalkerScript;

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('category');

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

/**
 * Form Field class for Category & quickadd.
 *
 * @since 2.0
 */
class JFormFieldCategoryadd extends JFormFieldCategory
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'Categoryadd';

	/**
	 * List name.
	 *
	 * @var string
	 */
	protected $view_list = 'categories';

	/**
	 * Item name.
	 *
	 * @var string
	 */
	protected $view_item = 'category';

	/**
	 * Component name without ext type, eg: content.
	 *
	 * @var string
	 */
	protected $component = 'categories';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		return parent::getInput() . $this->quickadd();
	}

	/**
	 * Add an quick add button & modal
	 *
	 * @return string The quickadd button.
	 */
	public function quickadd()
	{
		// Prepare Element
		$readonly = $this->getElement('readonly', false);
		$disabled = $this->getElement('disabled', false);

		if ($readonly || $disabled)
		{
			return '';
		}

		$container = Container::getInstance();
		$input     = $container->get('input');

		$quickadd         = $this->getElement('quickadd', false);
		$table_name       = $this->getElement('table', '#__' . $this->component . '_' . $this->view_list);
		$key_field        = $this->getElement('key_field', 'id');
		$value_field      = $this->getElement('value_field', 'title');
		$formpath         = WINDWALKER_SOURCE . "/Form/Forms/quickadd/category.xml";
		$quickadd_handler = $this->getElement('quickadd_handler', $input->get('option'));
		$title            = $this->getElement('quickadd_label', 'LIB_WINDWALKER_QUICKADD_TITLE');

		$qid = $this->id . '_quickadd';

		if (!$quickadd)
		{
			return '';
		}

		// Set AKQuickAddOption
		$config['task']             = $this->view_item . '.ajax.legacyquickadd';
		$config['quickadd_handler'] = $quickadd_handler;
		$config['cat_extension']    = (string) $this->element['extension'];
		$config['extension']        = 'com_' . $this->component;
		$config['component']        = $this->component;
		$config['table']            = $table_name;
		$config['model_name']       = 'category';
		$config['key_field']        = $key_field;
		$config['value_field']      = $value_field;
		$config['joomla3']          = (JVERSION >= 3);

		WindwalkerScript::quickadd('#' . $qid, $config);

		// Load Language & Form
		LanguageHelper::loadLanguage('com_' . $this->component, null);

		$formpath = str_replace(JPATH_ROOT, '', $formpath);
		$content  = ModalHelper::getQuickaddForm($qid, $formpath, (string) $this->element['extension']);

		// Prepare HTML
		$html         = '';
		$button_title = $title;
		$modal_title  = $button_title;
		$button_class = 'btn btn-small btn-success quickadd_button';

		$footer = "<button class=\"btn\" type=\"button\" data-dismiss=\"modal\">" . JText::_('JCANCEL') . "</button>";
		$footer .= "<button class=\"btn btn-primary quickadd_submit\" type=\"button\">" . JText::_('JSUBMIT') . "</button>";

		$html .= ModalHelper::modalLink(JText::_($button_title), $qid, array('class' => $button_class, 'icon' => 'icon-new icon-white'));
		$html .= ModalHelper::renderModal($qid, $content, array('title' => JText::_($modal_title), 'footer' => $footer));

		return $html;
	}

	/**
	 * Get Element Value.
	 *
	 * @param string $key     Element attribute key.
	 * @param mixed  $default The default value if not exists.
	 *
	 * @return string The attribute value.
	 */
	public function getElement($key, $default = null)
	{
		if ($this->element[$key])
		{
			return (string) $this->element[$key];
		}
		else
		{
			return $default;
		}
	}
}
