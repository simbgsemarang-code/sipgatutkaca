<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Adds the shared presentation layer to HTML responses so every standalone
 * view benefits from the same refinement without duplicating a stylesheet
 * link in each page template.
 */
class MY_Output extends CI_Output
{
	public function _display($output = '')
	{
		if ($output === '')
		{
			$output = $this->final_output;
		}

		if (is_string($output) && stripos($output, '</head>') !== FALSE
			&& stripos($output, 'site-polish.css') === FALSE)
		{
			$base_url = config_item('base_url');
			$stylesheet = "\n<link rel=\"stylesheet\" href=\""
				. htmlspecialchars($base_url.'assets/css/site-polish.css', ENT_QUOTES, 'UTF-8')
				. "\">\n";
			$output = preg_replace('/<\/head>/i', $stylesheet.'</head>', $output, 1);
		}

		// Beranda retains its photographic hero treatment; the remaining pages
		// receive the brighter application-shell background from the shared CSS.
		if (is_string($output) && stripos($output, '<title>Beranda') === FALSE)
		{
			$output = preg_replace('/<body(\s*)>/i', '<body$1 class="is-inner-page">', $output, 1);
		}

		parent::_display($output);
	}
}
