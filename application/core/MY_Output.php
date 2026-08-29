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
			// Cache-bust with the file's mtime so a new deploy is picked up
			// immediately even behind Cloudflare / browser caches (the plain
			// URL is cached hard, max-age 7 days).
			$css_path = FCPATH.'assets/css/site-polish.css';
			$css_ver  = is_file($css_path) ? filemtime($css_path) : 1;
			$base_url = config_item('base_url');
			$stylesheet = "\n<link rel=\"stylesheet\" href=\""
				. htmlspecialchars($base_url.'assets/css/site-polish.css?v='.$css_ver, ENT_QUOTES, 'UTF-8')
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
