<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Elfinder\View;

use JURI;
use Windwalker\Registry\Registry;
use Windwalker\View\Html\AbstractHtmlView;

/**
 * Elfinder Display View
 *
 * @since 2.0
 */
class DisplayView extends AbstractHtmlView
{
	/**
	 * The elFinder default toolbar buttons.
	 *
	 * @var  array
	 */
	protected $defaultToolbar = array(
		array('back', 'forward'),
		array('reload'),
		// array('home', 'up'),
		array('mkdir', 'mkfile', 'upload'),
		// array('open', 'download', 'getfile'),
		array('info'),
		array('quicklook'),
		array('copy', 'cut', 'paste'),
		array('rm'),
		array('duplicate', 'rename', 'edit', 'resize'),
		// array('extract', 'archive'),
		array('search'),
		array('view'),
		array('help')
	);

	/**
	 * Render this view.
	 *
	 * @return string
	 */
	public function render()
	{
		// Init some API objects
		// ================================================================================
		$container  = $this->getContainer();
		$input      = $container->get('input');
		$asset      = $container->get('helper.asset');
		$lang       = $container->get('language');
		$lang_code  = $lang->getTag();
		$lang_code  = str_replace('-', '_', $lang_code);

		$com_option = $this->option ? : $input->get('option');
		$config     = new Registry($this->data->config);

		// Script
		$this->displayScript($com_option, $config);

		// Get Request
		$finder_id  = $input->get('finder_id');
		$modal      = ($input->get('tmpl') === 'component') ? : false;
		$callback   = $input->get('callback');
		$root       = $config->get('root', $input->getPath('root', '/'));
		$start_path = $config->get('start_path', $input->getPath('start_path', '/'));
		$site_root  = JURI::root(true) . '/';
		$height     = $config->get('height', 445);

		$toolbar = $config->get('toolbar', $this->defaultToolbar);
		$toolbar = json_encode($toolbar);

		$onlymimes = $config->get('onlymimes', $input->getString('onlymimes', null));

		if ($onlymimes)
		{
			$onlymimes = is_array($onlymimes) ? $onlymimes : explode(',', $onlymimes);
			$onlymimes = array_map('trim', $onlymimes);
			$onlymimes = array_map(array('Windwalker\String\StringHelper', 'quote'), $onlymimes);
			$onlymimes = implode(',', $onlymimes);
		}

		// Get INI setting
		$upload_max = ini_get('upload_max_filesize');
		$upload_num = ini_get('max_file_uploads');

		$upload_limit = 'Max upload size: ' . $upload_max;
		$upload_limit .= ' | Max upload files: ' . $upload_num;

		// Set Script
		$getFileCallback = !$callback ? '' : "
            ,
            getFileCallback : function (files) {
                window.parent.$callback(WindwalkerFinderSelected, window.elFinder, '$site_root');
            }";

		$script = <<<SCRIPT
		var WindwalkerFinderSelected ;
        var elFinder ;

		// Init elFinder
        jQuery(document).ready(function($) {
            elFinder = $('#elfinder').elfinder({
                url         : 'index.php?option={$com_option}&task=finder.elfinder.connect&root={$root}&start_path={$start_path}' ,
                width       : '100%' ,
                height      : {$height} ,
                onlyMimes   : [$onlymimes],
                lang        : '{$lang_code}',
                uiOptions   : {
                    toolbar : {$toolbar}
                },
                handlers    : {
                    select : function(event, elfinderInstance)
                    {
                        var selected = event.data.selected;

                        if (selected.length)
                        {
                            WindwalkerFinderSelected = [];

                            jQuery.each(selected, function(i, e){
                                    WindwalkerFinderSelected[i] = elfinderInstance.file(e);
                            });
                        }

                    }
                }

                {$getFileCallback}

            }).elfinder('instance');

            elFinder.ui.statusbar.append('<div class="akfinder-upload-limit">{$upload_limit}</div>');
        });
SCRIPT;

		$asset->internalJS($script);
		$buttonText = \JText::_('LIB_WINDWALKER_FORMFIELD_FINDER_INSERT_URL_BUTTON');
		$hint = \JText::_('LIB_WINDWALKER_FORMFIELD_FINDER_INSERT_URL_PLACEHOLDER');

		return <<<HTML
<script>
function getUrlInputValue() {
    return jQuery('#finder-url-input').val();
}

function getUrlInputMime() {
    var url = getUrlInputValue();
    var type = url.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].indexOf(type) !== -1) {
        return 'image/' + type;
    }
    
    return 'url';
}
</script>
		
<div class="row-fluid">
    <div id="elfinder" class="span12 windwalker-finder"></div>
    
    <div style="clear: both;"></div>
    <hr/>
    <div class="url-input-wrapper">
        <div class="input-append">
            <input id="finder-url-input" type="text" class="input-xxlarge" placeholder="{$hint}"/>
            <button type="button" class="btn btn-default" onclick="window.parent.{$callback}([{hash: getUrlInputValue(), name: getUrlInputValue(), mime: getUrlInputMime()}], window.elFinder, '$site_root');">
                {$buttonText}
			</button>
		</div>
	</div>
</div>
HTML;
	}

	/**
	 * Display elFinder script.
	 *
	 * @param string $com_option Component option name.
	 * @param array  $config     Config array.
	 *
	 * @return void
	 */
	private function displayScript($com_option, $config)
	{
		$lang      = $this->container->get('language');
		$lang_code = $lang->getTag();
		$lang_code = str_replace('-', '_', $lang_code);

		// Include elFinder and JS
		// ================================================================================

		$asset = $this->container->get('helper.asset');

		// JQuery
		$asset->jquery();
		$asset->bootstrap();

		// ElFinder includes
		$asset->addCss('jquery-ui/smoothness/jquery-ui-1.8.24.custom.css');
		$asset->addCss('js/elfinder/css/elfinder.min.css');
		$asset->addCss('js/elfinder/css/theme.css');

		$asset->addJs('jquery/jquery-ui.min.js');
		$asset->addJs('elfinder/js/elfinder.min.js');

		if (is_file(WINDWALKER . '/asset/js/elfinder/js/i18n/elfinder.' . $lang_code . '.js'))
		{
			$asset->addJs('js/elfinder/js/i18n/elfinder.' . $lang_code . '.js');
		}
	}
}
