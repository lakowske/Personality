<?php
require_once('site_config.php');
require_once('Database.php');
require_once('Supplier.php');
require_once('CommentManagerV1.php');
require_once('CommentManagerV2.php');

echo "Converts comments from a database (f) containing version 1 " .
"comments to a database (t) supporting version 2 comments";

echo "Usage: fusername fpassword fdatabase fhost fport tusername tpassword tdatabase thost tport";
echo var_dump($argv);
$fuser = $argv[1];
$fpass = $argv[2];
$fdatabase = $argv[3];
$fhost = $argv[4];
$fport = $argv[5];

$tuser = $argv[6];
$tpass = $argv[7];
$tdatabase = $argv[8];
$thost = $argv[9];
$tport = $argv[10];


$f = new Database($fuser, $fpass, $fdatabase, $fhost, $fport);
$fromDatabaseSupplier = new Supplier($f);

$t = new Database($tuser, $tpass, $tdatabase, $thost, $tport);
$toDatabaseSupplier = new Supplier($t);

$fCommentManager = new CommentManagerV1($fromDatabaseSupplier);
$tCommentManager = new CommentManagerV2($toDatabaseSupplier);

$fromCommentCids = $fCommentManager->all_entries();
echo var_dump($fromCommentCids);

foreach($fromCommentCids as $cid) {
  $comment = $fCommentManager->load_entry($cid);
  $tCommentManager->copy_entry($comment);
}

// Now build relations
foreach($fromCommentCids as $cid) {
  $comment = $fCommentManager->load_entry($cid);
  if (sizeof($comment->children) > 0) {
    foreach ($comment->children as $rcid) {
      $tCommentManager->reference_comment($rcid, $comment->cid);
    }
  }
}

?>