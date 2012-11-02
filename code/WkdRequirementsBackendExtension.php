<?php
	class WkdRequirementsBackendExtension extends Requirements_Backend {
		/**
		 * Paths to all required .js files relative to the webroot.
		 * These .js files will be positioned top of stack.
		 *
		 * @var array $javascript
		 */
		protected $javascriptTop = array();

		/**
		 * Paths to all required .js files relative to the webroot.
		 * These .js files will be positioned bottom of stack.
		 *
		 * @var array $javascript
		 */
		protected $javascriptBottom = array();


		/**
		 * Paths to all required combined files relative to the webroot.
		 * These files will be combined and positioned top of stack.
		 *
		 * @var array $javascript
		 */
		public $combine_files_top = array();

		/**
		 * Paths to all required combined files relative to the webroot.
		 * These files will be combined and positioned bottom of stack.
		 *
		 * @var array $javascript
		 */
		public $combine_files_bottom = array();

		/**
		 * Register the given javascript file as required.
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file file path
		 * @param  string $position group position within head when <!--JS--> is present in page.ss
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		public function javascript($file, $position = 'middle') {
			if($position == 'top') {
				$this->javascriptTop[$file] = true;
			} else if($position == 'bottom') {
				$this->javascriptBottom[$file] = true;
			} else {
				$this->javascript[$file] = true;
			}
		}

		/**
		 * Combine javascript files from template
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file     file path
		 * @param  string $group    group name including extension
		 * @param  string $position position within stack
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		function javascript_combine($file, $group, $position = 'middle') {
			$this->javascript($file, $position);

			switch($position){
				case 'top':
					$this->combine_files_top[$group . '.js'][] = $file;
					break;
				case 'middle':
					$this->combine_files[$group . '.js'][] = $file;
					break;
				case 'bottom':
					$this->combine_files_top[$group . '.js'][] = $file;
					break;
			}
		}

		/**
		 * Combine css files from template
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file     file path
		 * @param  string $group    group name including extension
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		function css_combine($file, $group) {
			$this->css($file);
			$this->combine_files[$group . '.css'][] = $file;
		}

		/**
		 * Update the given HTML content with the appropriate include tags for the registered
		 * requirements. Needs to receive a valid HTML/XHTML template in the $content parameter,
		 * including a <head> tag. The requirements will insert before the closing <head> tag automatically.
		 *
		 * @todo Calculate $prefix properly
		 *
		 * @param string $templateFilePath Absolute path for the *.ss template file
		 * @param string $content HTML content that has already been parsed from the $templateFilePath through {@link SSViewer}.
		 * @return string HTML content thats augumented with the requirements before the closing <head> tag.
		 */
		function includeInHTML($templateFile, $content) {
			if(isset($_GET['debug_profile'])) Profiler::mark("Requirements::includeInHTML");

			if((strpos($content, '</head>') !== false || strpos($content, '</head ') !== false) && ($this->css || $this->javascript || $this->customCSS || $this->customScript || $this->customHeadTags)) {
				$requirements = '';
				$jsRequirements = '';

				// Merge javascript positioned arrays
				$this->javascript = array_merge($this->javascriptTop, $this->javascript, $this->javascriptBottom);

				// Merge positioned combined arrays
				$this->combine_files = array_merge($this->combine_files_top, $this->combine_files, $this->combine_files_bottom);

				// Combine files - updates $this->javascript and $this->css
				$this->process_combined_files();

				foreach(array_diff_key($this->javascript,$this->blocked) as $file => $dummy) {
					$path = $this->path_for_file($file);
					if($path) {
						$jsRequirements .= "<script type=\"text/javascript\" src=\"$path\"></script>\n";
					}
				}

				// add all inline javascript *after* including external files which
				// they might rely on
				if($this->customScript) {
					foreach(array_diff_key($this->customScript,$this->blocked) as $script) {
						$jsRequirements .= "<script type=\"text/javascript\">\n//<![CDATA[\n";
						$jsRequirements .= "$script\n";
						$jsRequirements .= "\n//]]>\n</script>\n";
					}
				}

				foreach(array_diff_key($this->css,$this->blocked) as $file => $params) {
					$path = $this->path_for_file($file);
					if($path) {
						$media = (isset($params['media']) && !empty($params['media'])) ? " media=\"{$params['media']}\"" : "";
						$requirements .= "<link rel=\"stylesheet\" type=\"text/css\"{$media} href=\"$path\" />\n";
					}
				}

				foreach(array_diff_key($this->customCSS, $this->blocked) as $css) {
					$requirements .= "<style type=\"text/css\">\n$css\n</style>\n";
				}

				foreach(array_diff_key($this->customHeadTags,$this->blocked) as $customHeadTag) {
					$requirements .= "$customHeadTag\n";
				}

				if($this->write_js_to_body) {
					// Remove all newlines from code to preserve layout
					$jsRequirements = preg_replace('/>\n*/', '>', $jsRequirements);

					// We put script tags into the body, for performance.
					// If your template already has script tags in the body, then we put our script
					// tags just before those. Otherwise, we put it at the bottom.
					$p1 = strripos($content, '<script');
					$p2 = stripos($content, '<body');
					if($p1 !== false && $p1 > $p2) {
						$content = substr($content,0,$p1) . $jsRequirements . substr($content,$p1);
					} else {
						$content = preg_replace("/(<\/body[^>]*>)/i", $jsRequirements . "\\1", $content);
					}

					// Put CSS at the bottom of the head
					$content = preg_replace("/(<\/head>)/i", $requirements . "\\1", $content);
				} else {
					if(strpos($content, '<!--CSS-->')) {
						$content = str_replace("<!--CSS-->", $requirements, $content);
					} else {
						$content = preg_replace("/(<\/head>)/i", $requirements . "\\1", $content);
					}

					if(strpos($content, '<!--JS-->')) {
						$content = str_replace("<!--JS-->", $jsRequirements, $content);
					} else {
						$content = preg_replace("/(<\/head>)/i", $jsRequirements . "\\1", $content);
					}
				}
			}

			if(isset($_GET['debug_profile'])) Profiler::unmark("Requirements::includeInHTML");

			return $content;
		}
	}