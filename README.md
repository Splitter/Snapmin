Snapmin
=======
Update: recently did some quick testing in Wordpress 4.9.8 and it still seems to function as intended

Easily create themeoption pages for wordpress themes/websites using an intuitive drag/drop interface.

![ScreenShot](http://i.imgur.com/raFTAS2.png)


#### License:

  This project is released under the same license as Wordpress itself - GPLv2
  
  Full License URL - http://wordpress.org/about/gpl/



#### Usage:

1. upload all files to directory named 'Snapmin' within the themes directory.

2. Include main snapmin file within themes 'functions.php'.
  
  require get_template_directory() . '/Snapmin/Snapmin.php';

3. Two new menu items will display within the wordpress admin area 'Snapmin Test' and 'Snap Builder'. Snap Builder is the editor interface for building the theme option pages. The options pages created with Snap Builder will display under the Snapmin Test menu(name can be changed).

4. Each page(ones created by script) will save their options using wordpress' standard get_option/set_option api using the value of 'menu_slug' as the name when you create the pages. 

5. Once done using snap builder to build themeoptions pages then edit Snapmin.php and change the following line
  
	define('SNAPMIN_ADD_EDITOR',1);

  to
  
	define('SNAPMIN_ADD_EDITOR',0);
