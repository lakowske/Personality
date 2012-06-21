<?php

/*
 * FileTypeManager returns the type using the first matching regular expression
 * defined in $regMatches.
 */
class FileTypeManager
{
  private $regMatches = array( "/\.jpg$/" => "image/jpeg",
			       "/\.png$/" => "image/png",
			       "/\.pdf$/" => "application/pdf");
  private $default = "application/octet-stream";

  public function filetype($path) {
    $matches = array();
    $pathLower = strtolower($path);
    foreach ($this->regMatches as $reg=>$type) {
      $c = preg_match_all($reg, $pathLower, $matches, PREG_PATTERN_ORDER);
      if ($c > 0) {
	return $type;
      }
    }
    return $this->default;
  }

}