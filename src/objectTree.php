<?php
require_once('LoginControllerNode.php');
require_once('SignupControllerNode.php');
require_once('UploadControllerNode.php');
require_once('FileUploadControllerNode.php');
require_once('FileListControllerNode.php');
require_once('DownloadControllerNode.php');
require_once('AddGroupNode.php');
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
require_once('GroupManager.php');
require_once('PredicateUtil.php');
require_once('database.php');

function getTree() {
  global $databaseSupplier;
  $isGet = function ($request) {return $request->isGet();};
  $isPost = function ($request) {return $request->isPost();};

  $isSubmit = function ($request) {return array_key_exists('submit', $request->getPostVars());};
  $isPreview = function ($request) {$p = $request->getPostVars(); return strcmp($p['submit'], "Preview") == 0;};

  $isPostSubmitPreview = a($isPost, $isSubmit, $isPreview);
  
  $pathManager = PathManager::get();
  $userManager = new UserManager($databaseSupplier);
  $loginControllerNode = new LoginControllerNode($userManager);
  $signupControllerNode = new SignupControllerNode($userManager);
  

  $templateSupplier = new TemplateSupplier(__DIR__, array($pathManager->templateDir()));

  $commentPreviewNode = new Node(a(regReqPred("/addentry$/"), $isPostSubmitPreview),
				 previewFunc('addentry.tpl', $templateSupplier));


  $loginDisplayNode = new DisplayNode("/login$/", $isGet,
				      'login.tpl', $templateSupplier);
  $signupDisplayNode = new DisplayNode("/signup$/", $isGet,
				       'signup.tpl', $templateSupplier);
  $uploadDisplayNode = new DisplayNode("/upload$/", $isGet,
				       'upload.tpl', $templateSupplier);
  $uploadDisplayNode = new DisplayNode("/addgroup$/", $isGet,
				       'addgroup.tpl', $templateSupplier);
  $addEntryDisplayNode = new DisplayNode("/addentry$/", $isGet,
				       'addentry.tpl', $templateSupplier);

  $fileManager = new FileManager($databaseSupplier);
  $groupManager = new GroupManager($databaseSupplier);

  $uploadManager = new UploadManager('upload/');
  $uploadControllerNode = new UploadControllerNode($uploadManager);
  $downloadControllerNode = new DownloadControllerNode($fileManager);
  $addGroupNode = new AddGroupNode($groupManager);
  $userDependentControllers = array();
  $userSupplierNode = new UserSupplierNode(&$userDependentControllers);
  $fileUploadControllerNode = new FileUploadControllerNode($uploadControllerNode, $fileManager, $uploadManager,$userSupplierNode);
  $fileListControllerNode = new FileListControllerNode($userSupplierNode, $fileManager);

  array_push(&$userDependentControllers, $fileUploadControllerNode, $fileListControllerNode, $addGroupNode);

  $userSupplierNode->setControllerNodes(&$userDependentControllers);



  $controllerNodes = array($commentPreviewNode, $addEntryDisplayNode, $loginControllerNode, $loginDisplayNode, $signupControllerNode, $signupDisplayNode, $uploadDisplayNode, $downloadControllerNode, $userSupplierNode);
  
  return new ControllerTree($controllerNodes);
}

?>