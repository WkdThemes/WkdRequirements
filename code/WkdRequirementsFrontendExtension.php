<?php
	/**
	 * IMPORTANT: Reanme 'Requirements' class to
	 * 'Requirements_Frontend' in Requirements.php
	 *
	 * This minor hack of the original source code is
	 * the only way we can easily and cleanly add
	 * custom mehods to the Require class so they are
	 * accessible within .ss templates
	 */
	class Requirements extends Requirements_Frontend {
		/**
		 * Register the given javascript file as required.
		 *
		 * See {@link Requirements_Backend::javascript()} for more info
		 *
		 * @param  string $file     file path
		 * @param  string $position group position within head when <!--JS--> is present in page.ss
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		static function javascript($file, $position = 'middle') {
			self::backend()->javascript($file, $position);
		}

		/**
		 * Combine javascript files from template
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file     file path
		 * @param  string $group    group name without extension
		 * @param  string $position group position within head when <!--JS--> is present in page.ss
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		static function javascript_combine($file, $group, $position = 'middle') {
			self::backend()->javascript_combine($file, $group, $position);
		}

		/**
		 * Combine css files from template
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file     file path
		 * @param  string $group    group name without extension
		 * @param  string $position group position within head when <!--CSS--> is present in page.ss
		 *
		 * @author Kirk Bentley <kirk@wkdthemes.com>
		 */
		static function css_combine($file, $group) {
			self::backend()->css_combine($file, $group);
		}
	}