## Asset Manager for Kohana 3.3

Setup
----------
Install like any other Kohana module:

1. Clone or download it into your Kohana modules folder.
2. Add it to the Kohana::modules call in bootstrap.php in your application folder.
[See the Kohana documentation for more detailed instructions](http://kohanaframework.org/3.3/guide/kohana/modules)
3. To use the minify features, install cssmin and jsmin in the vendor folder. 
  [JSMin](https://github.com/rgrove/jsmin-php)
  [CSSMin](https://code.google.com/p/cssmin/)

Your folder should look like:
vendor/cssmin/CssMin.php etc.
vendor/jsmin/jsmin.php etc.

4. Once that is set up, use ./minion --task=compile

Usage
----------
In your views:
	Scripts::output();
	Styles::output();

Use the config files to add styles and scripts.
