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
		 * See {@link Requirements_Backend::javascript()} for more info
		 *
		 * @param  string $file     file path
		 * @param  string $position group position within stack
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
		 * @param  string $position group position within stack
		 */
		static function javascript_combine($file, $group, $position = 'middle') {
			self::backend()->javascript_combine($file, $group, $position);
		}

		/**
		 * Register the given stylesheet file as required.
		 * See {@link Requirements_Backend::css()}
		 *
		 * @param $file String Filenames should be relative to the base, eg, 'framework/javascript/tree/tree.css'
		 * @param $media String Comma-separated list of media-types (e.g. "screen,projector")
		 * @param string $position group position within stack
		 * @see http://www.w3.org/TR/REC-CSS2/media.html
		 */
		static function css($file, $position = 'middle', $media = null) {
			self::backend()->css($file, $position, $media);
		}

		/**
		 * Combine css files from template
		 * Filenames should be relative to the base, eg, 'framework/javascript/loader.js'
		 *
		 * @param  string $file     file path
		 * @param  string $group    group name without extension
		 * @param  string $position group position within stack
		 */
		static function css_combine($file, $group, $position = 'middle', $media = null) {
			self::backend()->css_combine($file, $group, $position, $media);
		}

		/**
		 * Registers the given themeable stylesheet as required.
		 *
		 * A CSS file in the current theme path name "themename/css/$name.css" is
		 * first searched for, and if that doesn't exist and the module parameter is
		 * set then a CSS file with that name in the module is used.
		 *
		 * NOTE: This API is experimental and may change in the future.
		 *
		 * @param string $name The name of the file - e.g. "/css/File.css" would have
		 *        the name "File".
		 * @param string $position group position within stack
		 * @param string $module The module to fall back to if the css file does not
		 *        exist in the current theme.
		 * @param string $media The CSS media attribute.
		 */
		public static function themedCSS($name, $position = 'middle', $module = null, $media = null) {
			return self::backend()->themedCSS($name, $position, $module, $media);
		}
	}