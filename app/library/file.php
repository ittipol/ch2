<?php

namespace App\library;

class File
{
	private $file;
	private $filename;
	private $fileType;
	private $maxFileSizes;
  private $acceptedFileTypes;

	public function __construct($file = null) {

		if(!empty($file)) {

		  $this->file = $file;

		 dd(getimagesize($this->file->getRealPath()));

		  $this->generateFileName();

		  foreach (array('image','pdf') as $type) {
		   	if(!empty(strstr($this->file->getMimeType(), $type))) {
		   		$this->fileType = $type;
		   	}
		  }

		  switch ($this->fileType) {
		  	case 'image':
		  		
		  		$model = Service::loadModel('Image');
		  		$this->maxFileSizes = $model->getMaxFileSizes();
		  		$this->acceptedFileTypes = $model->getAcceptedFileTypes();

		  		break;
		  	
		  	case 'pdf':
		  		
		  		break;

		  }

		}

	}

	private function generateFileName() {
	  $this->filename = time().Token::generateNumber(15).$this->file->getSize().'.'.$this->file->getClientOriginalExtension();
	}

	public function getFileName() {
		return $this->filename;
	}

	public function getOriginalFileName() {
		return $this->file->getClientOriginalName();
	}

	public function getFileType() {
		return $this->fileType;
	}

	public function getRealPath() {
		return $this->file->getRealPath();
	}

	public function checkFileType() {
		return in_array($this->file->getMimeType(), $this->acceptedFileTypes);
	}

	public function checkFileSize() {
		if($this->file->getSize() <= $this->maxFileSizes){
			return true;
		}
		return false;
	}

	// public function saveTemporaryFile() {
	//   return $this->file->move(storage_path(Service::loadModel('TemporaryFile')->getTemporaryPath()), $this->filename);
	// }

}