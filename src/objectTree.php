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
require_once('Node.php');
require_once('ControllerTree.php');
require_once('TemplateSupplier.php');
require_once('PathManager.php');
require_once('UserSupplier.php');
require_once('UserManager.php');
require_once('UploadManager.php');
require_once('FileManager.php');
require_once('CommentManager.php');
require_once('GroupManager.php');
require_once('PredicateUtil.php');
require_once('RegPred.php');
require_once('database.php');


//define useful predicates
global $databaseSupplier;
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
$loginControllerNode = new LoginControllerNode($userManager);
$signupControllerNode = new SignupControllerNode($userManager);


$templateSupplier = new TemplateSupplier(__DIR__, array($pathManager->templateDir()));



$loginDisplayNode = new DisplayNode("/login$/", $isGet,
				    'login.tpl', $templateSupplier);
$signupDisplayNode = new DisplayNode("/signup$/", $isGet,
				     'signup.tpl', $templateSupplier);
$uploadDisplayNode = new DisplayNode("/upload$/", $isGet,
				     'upload.tpl', $templateSupplier);
$addGroupDisplayNode = new DisplayNode("/addgroup$/", $isGet,
				       'addgroup.tpl', $templateSupplier);
$addEntryDisplayNode = new DisplayNode("/addentry$/", $isGet,
				       'addentry.tpl', $templateSupplier);

$fileManager = new FileManager($databaseSupplier);
$groupManager = new GroupManager($databaseSupplier);

$uploadManager = new UploadManager('upload/');
$uploadControllerNode = new UploadControllerNode($uploadManager);
$downloadControllerNode = new DownloadControllerNode($fileManager);


$userDependentControllers = array();
$userSupplierNode = new UserSupplierNode(&$userDependentControllers);

$fileUploadControllerNode = new FileUploadControllerNode($uploadControllerNode, $fileManager, $uploadManager,$userSupplierNode);
$fileListControllerNode = new FileListControllerNode($userSupplierNode, $fileManager);
$addGroupNode = new AddGroupNode($groupManager);

$commentHtmlDisplay = new CommentHtmlDisplayNode($commentManager, $userSupplierNode, $templateSupplier, $databaseSupplier);
$commentsHtmlDisplay = new CommentsHtmlDisplay($commentHtmlDisplay);
$commentsDisplayPred = new RegPred("/^\/comments$/");
$commentsDisplayNode = new CommentsDisplayNode($commentManager, $commentsHtmlDisplay, a($commentsDisplayPred->get(), $isGet));


$commentPreviewNode = new Node(a(regReqPred("/addentry$/"), $isPostSubmitPreview),
			       previewFunc('addentry.tpl', $templateSupplier));

$commentDeleteRegPred = new RegPred("/^\/deletecomment\/(\d+)$/");
$commentDeleteNode = new Node(a($commentDeleteRegPred->get(), $isGet),
			      commentDelete($commentManager, $commentDeleteRegPred));

$commentAddNode = new CommentAddNode($commentManager, $userSupplierNode, a(regReqPred("/addentry$/"), $isPostFinalSubmit));
$commentEditRegPred = new RegPred("/^\/update\/comment\/(\d+)$/");
$commentEditNode = new CommentEditNode($commentManager, $templateSupplier, $commentEditRegPred, a($commentEditRegPred->get(), $isGet));

$commentUpdateNode = new CommentUpdateNode($commentManager, a($commentEditRegPred->get(), $isPost));

$commentDisplayRegPred = new RegPred("/^\/view\/comment\/(\d+)$/");
$commentDisplayNode = new CommentDisplayNode($commentManager, $userSupplierNode, $templateSupplier, $databaseSupplier, 
					     $commentDisplayRegPred,
					     a($commentDisplayRegPred->get(), $isGet));

array_push(&$userDependentControllers, $fileUploadControllerNode, $fileListControllerNode, $addGroupNode, $commentAddNode,
	   $commentDisplayNode, $commentDeleteNode, $commentEditNode, $commentUpdateNode, $commentsDisplayNode);

  $userSupplierNode->setControllerNodes(&$userDependentControllers);



$controllerNodes = array($commentPreviewNode, $addEntryDisplayNode, $loginControllerNode, $loginDisplayNode, $signupControllerNode, $signupDisplayNode, $uploadDisplayNode, $downloadControllerNode, $userSupplierNode);

$objectTree = new ControllerTree($controllerNodes);


?>