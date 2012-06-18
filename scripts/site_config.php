<?php
// setup the include path
$GLOBALS['prefix'] = __DIR__;
$GLOBALS['libs'] = $GLOBALS['prefix'] . '/../src';

// add to our include path the path to the smarty and pear libs
$libs = $GLOBALS['libs'];
$include_path = ini_get('include_path');
ini_set('include_path', $libs . ':' . $include_path);


?>
