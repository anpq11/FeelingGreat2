<?php
// ===========================================================================================
//
// viewsource.php
//
// Description: An implementation of a PHP pagecontroller for a web-site.
// Shows a directory listning.
//
// Author: Mikael Roos, edited and re-used by Annelie S&auml;lj&ouml;
//

// -------------------------------------------------------------------------------------------
//
// Settings for this pagecontroller.
//

// Separator between directories and files, change between Unix/Windows
$SEPARATOR = '/'; 	// Unix, Linux, MacOS, Solaris
//$SEPARATOR = '\\'; 	// Windows 

// Show the content of files named config.php, except the rows containing DB_USER, DB_PASSWORD
//$HIDE_DB_USER_PASSWORD = FALSE; 
$HIDE_DB_USER_PASSWORD = TRUE; 

// Which directory to use as basedir, end with separator
$BASEDIR = '.' . $SEPARATOR;

// The link to this page, usefull to change when using this pagecontroller for other things,
// such as showing stylesheets in a separate directory, for example.
$HREF = 'viewsource.php?';


// -------------------------------------------------------------------------------------------
//
// Page specific code
//

$html = <<<EOD
<h1>K&auml;llkod</h1>
<p>
Nedanst&aring;ende filer finns i denna katalogen. Klicka p&aring; en fil f&ouml;r att visa dess inneh&aring;ll.
</p>
EOD;


// -------------------------------------------------------------------------------------------
//
// Verify the input variable _GET, no tampering with it
//
$currentdir	= isset($_GET['dir']) ? $_GET['dir'] : '';

$fullpath1 	= realpath($BASEDIR);
$fullpath2 	= realpath($BASEDIR . $currentdir);
$len = strlen($fullpath1);
if(	strncmp($fullpath1, $fullpath2, $len) !== 0 ||
	strcmp($currentdir, substr($fullpath2, $len+1)) !== 0 ) {
	die('Tampering with directory?');
}
$fullpath = $fullpath2;
$currpath = substr($fullpath2, $len+1);


// -------------------------------------------------------------------------------------------
//
// Show the name of the current directory
//
$start		= basename($fullpath1);
$dirname 	= basename($fullpath);
$html .= <<<EOD
<p>
<a href='{$HREF}dir='>{$start}</a>{$SEPARATOR}{$currpath}
</p>

EOD;


// -------------------------------------------------------------------------------------------
//
// Open and read a directory, show its content
//
$dir 	= $fullpath;
$curdir1 = empty($currpath) ? "" : "{$currpath}{$SEPARATOR}";
$curdir2 = empty($currpath) ? "" : "{$currpath}";

$list = Array();
if(is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	if($file != '.' && $file != '..' && $file != '.svn') {
        		$curfile = $fullpath . $SEPARATOR . $file;
        		if(is_dir($curfile)) {
          	  		$list[$file] = "<a href='{$HREF}dir={$curdir1}{$file}'>{$file}{$SEPARATOR}</a>";
          	  	} else if(is_file($curfile)) {
          	  	  $list[$file] = "<a href='{$HREF}dir={$curdir2}&amp;file={$file}'>{$file}</a>";
          	  	}
          	 }
        }
        closedir($dh);
    }
}

ksort($list);

$html .= '<p>';
foreach($list as $val => $key) {
	$html .= "{$key}<br />\n";
}
$html .= '</p>';


// -------------------------------------------------------------------------------------------
//
// Show the content of a file, if a file is set
//
$dir 	= $fullpath;
$file	= "";

if(isset($_GET['file'])) {
	$file = basename($_GET['file']);

	$content = htmlspecialchars(file_get_contents($dir . $SEPARATOR . $file, 'FILE_TEXT'));

	// Remove password and user from config.php, if enabled
	if($HIDE_DB_USER_PASSWORD == TRUE && $file == 'config.php') {

		$pattern[0] 	= '/(DB_PASSWORD|DB_USER)(.+)/';
		$replace[0] 	= '/* <em>\1,  is removed and hidden for security reasons </em> */ );';
		
		$content = preg_replace($pattern, $replace, $content);
	}
	
	$html .= <<<EOD
<legend><a href='{$HREF}'>{$file}</a></legend>
<pre>
{$content}
</pre>
EOD;
}

?>

<!doctype html>
<html lang='en'> 
<head>
  <meta charset='utf-8'/>
  <title>Annelie S&auml;lj&ouml;</title>
	<link rel='shortcut icon' href='http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/phpmvc/family.jpg'/>
  <link rel='stylesheet' href='http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/themes/core/style.css'/>
</head>
<body>
  <div id='wrap-header'>
    <div id='header'>
    <div id='banner'>
     <a href='http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/'>
        <img class='site-logo' src='http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/phpmvc/me1.jpg' alt='logo' width='80' height='80' />
      </a>
      <p class='site-title'>phpmvc</p>
      <p class='site-slogan'>PHP/MVC kurs</p>
    </div>
    <nav id = 'navbar'>
<a href="http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/main.php">SiDa om MiG</a>
<a href="http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/redovisning.php">Redovisning</a>
<a href="http://www.student.bth.se/~anpq11/phpmvc/FeelingGreat2/viewsource.php">K&auml;llkod</a>
</nav>
    </div>
  </div>
  <div id='wrap-main'>
    <div id='main' role='main'>
<?=$html?>
 </div>
  </div>
  <div id='wrap-footer'>
    <div id='footer'>
            <p>&copy; Annelie S&auml;lj&ouml; 2013</p>
    </div>
  </div>
</body>
</html>