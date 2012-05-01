<?php

class UploadManager
{
  private $uploadDir;

  public function __construct($uploadDir) {
    $this->uploadDir = $uploadDir;
  }

  /**
   * returns a unique file system path given a filename.
   *
   */
  public function getPath($filename) {
    return $this->getUploadPath() . $filename;
  }

  public function getNewFilename($filename) {
    return strval(rand()) . '-' . $filename;
  }

  public function getURL($filename, $baseURL) {
    return "{$baseURL}/{$uploadDir}/{$filename}";
  }

  public function getUploadPath() {
    return $this->uploadDir;
  }

}
