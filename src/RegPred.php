<?php


/**
 * A regular expression predicate that holds the last match.
 */
class RegPred
{
  private $reg;
  private $matches;

  public function __construct($reg) {
    $this->reg = $reg;
  }

  public function evaluate($request) {
    $server = $request->getServerVars();
    $path_info = $server['PATH_INFO'];
    $b = preg_match_all($this->reg, $path_info, $this->matches, PREG_PATTERN_ORDER);
    return $b > 0;
  }

  public function getMatches() {
    return $this->matches;
  }

  public function get() {
    $tmp = $this;
    return function ($request) use ($tmp) {
      return $tmp->evaluate($request);
    };
  }

}

?>