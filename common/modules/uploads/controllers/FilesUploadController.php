<?php
namespace common\modules\uploads\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\modules\uploads\models\Files;
use common\modules\uploads\models\Image;

class FilesUploadController extends Controller
{
	/**
     * Switch off CSRF
    */
    public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false;
		 
		return parent::beforeAction($action); 
	}
	
	public function actionUpload()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$get = Yii::$app->request->get();
		$result = [];
		
		$category = (isset($get['category'])) ? $get['category'] : '';
		$uploadType = (isset($get['upload_type'])) ? $get['upload_type'] : '';
		$params = Yii::$app->params['upload_dir'];
		
		if(isset($params[$category]) && isset($params[$category]['dir']) && isset($params[$category]['model']) && isset($_FILES[$params[$category]['model']]) && $uploadType != '') 
		{
			$model = new Files();
			$model->file = \yii\web\UploadedFile::getInstancesByName($params[$category]['model']);
			$dir = ($uploadType == 'tmp') ? Files::getUploadDir($category, true) : $params[$category]['dir'].DIRECTORY_SEPARATOR;
			$uploadDir = Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir;
			
			if(is_dir($uploadDir))
			{	
				foreach($model->file as $file) 
				{	
					if($file->saveAs($uploadDir.$file->baseName.'.'.$file->extension))
					{	
						$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : Files::getThumbnailParams([], $dir, $file->baseName.'.'.$file->extension);
						$result['name'] = \Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file->baseName.'.'.$file->extension;
						$result['file'] = $file->baseName.'.'.$file->extension;
						$result['thumbnail'] = $thumbnail;
						$result['upload_type'] = $uploadType;
					}
				}
			}
		}
		
		return $result;
	}
	
	 public function actionContentFileUpload()
    {
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$model = new Files();
		
		if(Yii::$app->request->isPost) 
		{	
			$image = new Image;
			
			//Get upload category
			$uploadDir = (isset($_GET['category'])) ? $_GET['category'] : '';
			
			// Required: anonymous function reference number as explained above.
			$funcNum = (isset($_GET['CKEditorFuncNum'])) ? $_GET['CKEditorFuncNum'] : '';
				
			// Optional: instance name (might be used to load a specific configuration file or anything else).
			$CKEditor = (isset($_GET['CKEditor'])) ? $_GET['CKEditorFuncNum'] : '';
			
			// Optional: might be used to provide localized messages.
			$langCode = (isset($_GET['langCode'])) ? $_GET['langCode'] : '';
				 
			// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
			$url = '';
			
			// Usually you will only assign something here if the file could not be uploaded.
			$message = Yii::t('messages', 'Файл не загружен!');
            
            if($langCode != '' && $CKEditor != '' && $funcNum != '' && $uploadDir != '')
            {	echo 'Work!';
				$result = $image->singleRawUpload('upload', $uploadDir);
				Var_dump($result);
				if($result[0])
				{
					$url = \Yii::getAlias('@content_uploads_dir_no_root').DIRECTORY_SEPARATOR.$uploadDir.DIRECTORY_SEPARATOR.$result[2];
					$message = Yii::t('messages', 'Файл загружен!');
				}
			}
			
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$funcNum."', '".$url."', '".$message."');</script>";
        }
	}
	
	public function actionDeleteFile()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$post = Yii::$app->request->post();
		$category = (isset($post['category'])) ? $post['category'] : '';
		$file = (isset($post['file'])) ? $post['file'] : '';
		$id = (isset($post['id'])) ? $post['id'] : 0;
		$wrapID = (isset($post['wrap_id'])) ? $post['wrap_id'] : 0;
		$fileType = (isset($post['file_type'])) ? $post['file_type'] : '';
		$result = false;
		
		if($category != '' && $file != '' && isset(Yii::$app->params['upload_dir'][$category]))
		{	
			$result = Files::deleteFile($category, $file, $id, $fileType);
		}
		
		return ['result'=>$result, 'wrap_id'=>$wrapID, 'file_type'=>$fileType, 'file'=>$file];
	}
}
