<?php

class FileSystem extends CComponent
{
	public $storagePath = null;
	public $storageUrl = null;

	public function init(){
		if (is_null($this->storagePath)) {
			$this->storagePath = Yii::app()->basePath.'/../www/storage';
		}
		if (is_null($this->storageUrl)) {
			$this->storageUrl = '/storage/';
		}
		if (!file_exists($this->storagePath))
			mkdir($this->storagePath, 0777, true);
		$this->storagePath = realpath($this->storagePath).'/';
		if (is_dir($this->storagePath))
			throw new CException('FileSystem->storagePath is not dir ('.$this->storagePath.')');
	}

	private function getUniqId() {
		return uniqid();
	}

	/**
	 * @param string $uid
	 * @return string full path in filesystem to file
	 */
	public function getFilePath($uid) {
		return $this->storagePath.$uid;
	}

	/**
	 * @param $uid
	 * @return string Url to file
	 */
	public function getFileUrl($uid) {
		return $this->storageUrl.$uid;
	}

	/**
	 * @param string $fileName
	 * @return string Uid of new file
	 */
	public function publishFile($fileName) {
		$ext = strtolower(CFileHelper::getExtension($fileName));
		$uid = $this->getUniqId().'.'.$ext;
		copy($fileName, $this->storagePath.$uid);

		return $uid;
	}

	/**
	 * @param string $uid
	 * @return bool
	 */
	public function removeFile($uid) {
		return unlink($this->getFilePath($uid));
	}
}
