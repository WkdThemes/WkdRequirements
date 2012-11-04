#WKD Requirements#
http://www.wkdthemes.com
***

WKD Requirements allows you to position javascript requirements with more precision and combine requirements from within templates.

***
###NOTE###

**To use WKD Requirements you must make one tiny little change in the original Requirements.php source**

**Change the class name from** `Requirements` **to** `Requirements_Frontend`
***

##Precise Requirements Stack Placement##
***

Inserting requirements automatically before the closing head tag doesn't allow for the positioning of external JavaScript or Stylesheets.

To achieve a more precise stack placement you can insert `<!--JS-->` and `<!--CSS-->` HTML comments within **Page.ss** precisely where you want your requirements to appear.

```
<!DOCTYPE html>
<!--[if !IE]><!-->
<html>
<!--<![endif]-->
<!--[if IE 7 ]><html class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8"><![endif]-->
<head>
	<title>WKD Requirements</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" />
	<% require themedCSS(mytheme_styles) %>
	<!--CSS-->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="themes/mytheme/js/html5.js"></script>
	<![endif]-->
	<% require javascript(themes/mytheme/js/myscript.js, bottom) %>
	<!--JS-->
</head>
<body>
...
</body>
</html>
```

##Positioning within JavaScript stack##
***

WKD Requirements allows you to position your scripts within the stack. There are three (3) possible positions - `top`, `middle` and `bottom`. Simply pass the `javascript` method a script and position.

```
	<% require javascript(themes/mytheme/js/myscript.js, top) %>
```

Scripts are placed in the middle by default if no position is provide.

##Positioning within Stylesheet stack##
***

WKD Requirements allows you to position your stylesheets within the stack. There are three (3) possible positions - `top`, `middle` and `bottom`. Simply pass the `css` method a stylesheet and position.

```
	<% require css(themes/mytheme/css/mystyles.css, top) %>
```

Stylesheets are placed in the middle by default if no position is provide.

##Combine JavaScript requirements in your templates##
***

Combining your JavaScript requirements from the template is a snap. Provide the `javascript_combine` method with a script, combined file name (no .js extension) and a position.

Providing a position allows you to control where you script is positioned within the combined output. There are three (3) possible positions - `top`, `middle` and `bottom`.

The following will produce a sing script called **myscripts.js**.
```
	<% require javascript_combine(themes/mythme/js/file-1.js, myscripts) %>
	<% require javascript_combine(themes/mythme/js/file-2.js, myscripts) %>
	<% require javascript_combine(themes/mythme/js/file-3.js, myscripts) %>
```

Scripts are placed in the middle by default if no position is provide.

##Combine Stylesheet requirements in your templates##
***

Combining your Stylesheets requirements from the template is just as easy. Provide the `css_combine` method with a stylesheet, combined file name (no .css extension) and a position.

Providing a position allows you to control where you stylesheet is positioned within the combined output. There are three (3) possible positions - `top`, `middle` and `bottom`.

The following will produce a sing stylesheet called **mystylesheets.css**.
```
	<% require css_combine(themes/mythme/css/file-1.css, mystylesheets) %>
	<% require css_combine(themes/mythme/css/file-2.css, mystylesheets) %>
	<% require css_combine(themes/mythme/css/file-3.css, mystylesheets) %>
```

Stylesheets are placed in the middle by default if no position is provide.