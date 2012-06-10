<?php

function n($toNegate) {
  $predicate = function ($argument) use ($toNegate) {
    return !$toNegate($argument);
  };
  
  return $predicate;
}

function t() {
  $predicate = function ($argument) {
    return true;
  };

  return $predicate;
}

function f() {
  $predicate = function ($argument) {
    return false;
  };

  return $predicate;
}

function emptyFunc() {
  $function = function($argument) {
    return '';
  };

  return $function;
}

function a() {
  $numargs = func_num_args();
  if ($numargs < 2) {
    throw new Exception('predicate generator requires at least two arguments');
  }
  $arg_list = func_get_args();

  $predicate = function ($argument) use ($numargs, $arg_list) {
    for ($i = 0; $i < $numargs; $i++) {
      $p = $arg_list[$i];
      if (!$p($argument)) {
	return false;
      }
    }
    return true;
  };
  
  return $predicate;
}  

function o() {
  $numargs = func_num_args();
  if ($numargs < 2) {
    throw new Exception('predicate generator requires at least two arguments');
  }
  $arg_list = func_get_args();

  $predicate = function ($argument) use ($numargs, $arg_list) {
    for ($i = 0; $i < $numargs; $i++) {
      $p = $arg_list[$i];
      if ($p($argument)) {
	return true;
      }
    }
    return false;
  };
  
  return $predicate;
}  


class PredicateUtil
{
  
  /**
   * a predicate that returns true if and only if both p1 and p2 returns
   * true.
   */
  public static function getAndPredicate($p1, $p2) {
    $predicate = function ($argument) use ($p1, $p2) {return ($p1($argument) AND $p2($argument));};
    return $predicate;
  }

  /**
   * a predicate that returns true if p1 or p2 returns true
   */
  public static function getOrPredicate($p1, $p2) {
    $predicate = function ($argument) use ($p1, $p2) {return ($p1($argument) OR $p2($argument));};
    return $predicate;
  }
  
}

?>