<?php
require_once('LoginControllerNode.php');
require_once('SignupControllerNode.php');
require_once('UploadControllerNode.php');
require_once('FileUploadControllerNode.php');
require_once('FileListControllerNode.php');
require_once('DownloadControllerNode.php');
require_once('AddGroupNode.php');
require_once('CommentAddNode.php');
require_once('CommentDisplayNode.php');
require_once('CommentEditNode.php');
require_once('CommentUpdateNode.php');
require_once('CommentHtmlDisplay.php');
require_once('CommentsHtmlDisplay.php');
require_once('CommentsDisplayNode.php');
require_once('UserSupplierNode.php');
require_once('DisplayNode.php');
require_once('ThreeColDisplay.php');
require_once('Node.php');
require_once('ControllerTree.php');
require_once('TemplateSupplier.php');
require_once('PathManager.php');
require_once('UserSupplier.php');
require_once('UserManager.php');
require_once('UploadManager.php');
require_once('FileManager.php');
require_once('FileTypeManager.php');
require_once('CommentManager.php');
require_once('GroupManager.php');
require_once('PredicateUtil.php');
require_once('RegPred.php');
require_once('database.php');


//define useful predicates
global $databaseSupplier;
global $staticURLSupplier;

$isGet = function ($request) {return $request->isGet();};
$isPost = function ($request) {return $request->isPost();};

$isSubmit = function ($request) {return array_key_exists('submit', $request->getPostVars());};
$isPreview = function ($request) {$p = $request->getPostVars(); return strcmp($p['submit'], "Preview") == 0;};
$isFinal = function ($request) {$p = $request->getPostVars(); return strcmp($p['submit'], "Submit") == 0;};
$isPostSubmitPreview = a($isPost, $isSubmit, $isPreview);
$isPostFinalSubmit = a($isPost, $isSubmit, $isFinal);


  
$pathManager = PathManager::get();
$userManager = new UserManager($databaseSupplier);
$commentManager = new CommentManager($databaseSupplier);
$fileManager = new FileManager($databaseSupplier);
$fileTypeManager = new FileTypeManager();
$groupManager = new GroupManager($databaseSupplier);
$uploadManager = new UploadManager('upload/');

$templateSupplier = new TemplateSupplier(__DIR__, array($pathManager->templateDir()), $staticURLSupplier->get());

//comment predicates
$commentsDisplayPred         = new RegPred("/^\/comments$/");
$codeDisplayPred             = new RegPred("/^\/code$/");
$defaultDisplayPred          = new RegPred("/^\/$/");
$commentEditRegPred          = new RegPred("/^\/edit\/comment\/(\d+)$/");
$commentDeleteRegPred        = new RegPred("/^\/delete\/comment\/(\d+)$/");
$commentDisplayRegPred       = new RegPred("/^\/view\/comment\/(\d+)$/");
$commentAddRegPred           = new RegPred("/^\/create\/comment$/");
$downloadRegPred             = new RegPred("/^\/file\/(.*)/");
$filesReadRegPred            = new RegPred("/^\/files$/");
$fileCreateRegPred           = new RegPred("/^\/create\/file$/");

$menuDisplayNode = new DisplayNode("/menu$/", $isGet,
				   'menu.tpl', $templateSupplier);
$loginDisplayNode = new DisplayNode("/login$/", $isGet,
				    'login.tpl', $templateSupplier);
$signupDisplayNode = new DisplayNode("/signup$/", $isGet,
				     'signup.tpl', $templateSupplier);
$uploadDisplayNode = new DisplayNode("/^\/create\/file$/", $isGet,
				     'upload.tpl', $templateSupplier);
$addGroupDisplayNode = new DisplayNode("/addgroup$/", $isGet,
				       'addgroup.tpl', $templateSupplier);
$addEntryDisplayNode = new DisplayNode("/addentry$/", $isGet,
				       'addentry.tpl', $templateSupplier);

$loginControllerNode = new LoginControllerNode($userManager);

$signupControllerNode = new SignupControllerNode($userManager);

$userDependentControllers = array();

$userSupplierNode = new UserSupplierNode(&$userDependentControllers);

$downloadControllerNode = new DownloadControllerNode($fileManager, $fileTypeManager, $downloadRegPred, $downloadRegPred->get());

$uploadControllerNode = new UploadControllerNode($uploadManager);

$fileUploadControllerNode = new FileUploadControllerNode($uploadControllerNode, $fileManager, $uploadManager,$userSupplierNode,a($fileCreateRegPred->get(), $isPost));

$fileListControllerNode = new FileListControllerNode($userSupplierNode, $fileManager, $filesReadRegPred->get());

$addGroupNode = new AddGroupNode($groupManager);

$commentHtmlDisplay = new CommentHtmlDisplayNode($commentManager, $userSupplierNode, $templateSupplier, $databaseSupplier);

$commentsHtmlDisplay = new CommentsHtmlDisplay($commentHtmlDisplay);

$commentsDisplayNode = new CommentsDisplayNode('comment', $commentManager, $commentsHtmlDisplay, a(o($commentsDisplayPred->get(), $defaultDisplayPred->get()), $isGet));

$codeDisplayNode = new CommentsDisplayNode('code', $commentManager, $commentsHtmlDisplay, a($codeDisplayPred->get(), $isGet));


$commentPreviewNode = new Node(a($commentAddRegPred->get(), $isPostSubmitPreview),
			       previewFunc('addentry.tpl', $templateSupplier));

$commentDeleteNode = new Node(a($commentDeleteRegPred->get(), $isGet),
			      commentDelete($commentManager, $commentDeleteRegPred));

$commentAddNode = new CommentAddNode($commentManager, $userSupplierNode, a($commentAddRegPred->get(), $isPostFinalSubmit));

$commentEditNode = new CommentEditNode($commentManager, $templateSupplier, $commentEditRegPred, a($commentEditRegPred->get(), $isGet));

$commentUpdateNode = new CommentUpdateNode($commentManager, a($commentEditRegPred->get(), $isPost));

$commentDisplayNode = new CommentDisplayNode($commentHtmlDisplay, $commentDisplayRegPred, a($commentDisplayRegPred->get(), $isGet));



array_push(&$userDependentControllers, $fileUploadControllerNode, $fileListControllerNode, $addGroupNode, $commentAddNode,
	   $commentDisplayNode, $commentDeleteNode, $commentEditNode, $commentUpdateNode, $commentsDisplayNode, $codeDisplayNode);

$userSupplierNode->setControllerNodes(&$userDependentControllers);



$controllerNodes = array($commentPreviewNode, $addEntryDisplayNode, $loginControllerNode, $loginDisplayNode, $signupControllerNode, $signupDisplayNode, $uploadDisplayNode, $userSupplierNode);

$htmlNodes = new ControllerTree($controllerNodes);

$emptyNode = new Node(f(),emptyFunc());

$threeColDisplay = new ThreeColDisplay(n($downloadRegPred->get()), $templateSupplier, "Seth's home", $menuDisplayNode, $htmlNodes, $emptyNode);

$displayXorDownloadNodes = array($threeColDisplay, $downloadControllerNode);

$objectTree = new ControllerTree($displayXorDownloadNodes);

?>