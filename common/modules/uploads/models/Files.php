<?php
namespace common\modules\uploads\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\uploads\models\FileHelper;
use common\modules\uploads\models\Image;

/**
* Files is the model to work with file uploading
*/
class Files extends Model
{
	/**
	* @var UploadedFile|Null file attribute
	*/
	public $file;

	/**
	* @return array the validation rules.
	*/
	public function rules()
	{
		return [
			[['file'], 'file'],
		];
	}
	
	public static function deleteFile($category, $file, $id, $fileType)
    {
		$result = false;
		$params = Yii::$app->params['upload_dir'][$category];
		$alias = isset($params['alias']) ? $params['alias'] : 'frontend_uploads';
		
		switch($fileType)
		{
			case 'dir':
			$path = $params['uploads'].DIRECTORY_SEPARATOR.$id;
			break;
			
			case 'file':
			$path = ($id > 0) ? $id.DIRECTORY_SEPARATOR.$file : $file;
			break;
			
			case 'tmp':
			$path = $params['tmp'].DIRECTORY_SEPARATOR.$file;
			break;
		}
		
		if($fileType == 'dir')
		{
			$dir = \Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
			
			if(is_dir($dir))
			{	
				FileHelper::removeDirectory($dir);
				$result = true;
			}
		}
		elseif($fileType == 'dir_relative_path')
		{
			if(is_dir($file))
			{	
				FileHelper::removeDirectory($file);
				
				if(!is_dir($file))
				{
					$result = true;
				}
			}
		}
		elseif($fileType == 'tmp')
		{
			$dir = Files::getUploadDir($category, true);
			$dir = Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir;
			
			if(is_dir($dir))
			{	
				if(file_exists($dir.DIRECTORY_SEPARATOR.$file))
				{	
					$result = (unlink($dir.$file)) ? true : false;
				}
			}
		}
		else
		{	
			if(isset($params['image_sizes']))
			{	
				$unlinkResult = true;
				
				foreach($params['image_sizes'] as $sizeName => $imageData)
				{				
					if(isset($imageData['dir']))
					{
						$dir = Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.(self::getUploadDirUrl($id, $category, false, $sizeName));
						
						if(is_dir($dir))
						{
							$filePath = $dir.DIRECTORY_SEPARATOR.$file;
							
							if(file_exists($filePath))
							{	
								if(!unlink($filePath))
								{	
									$unlinkResult = false;
									break;
								}
							}
						}
					}
				}
				
				$result = $unlinkResult;
			}
			elseif(isset($params['uploads']))
			{	
				$path = $params['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$file;
			
				if(file_exists($path))
				{
					if(unlink($path))
					{
						$result = true;
					}
				}	
			}
		}
		
		return $result;
	}
	
	public static function getFiles($category, $tmp = false, $id = 0)
    {
		$result = [];
		
		if(isset(Yii::$app->params['upload_dir'][$category]))
		{
			$params = Yii::$app->params['upload_dir'][$category];
			$dir = ($tmp && isset(Yii::$app->params['upload_dir'][$category]['tmp'])) ? \Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : \Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR;
			
			if(is_dir($dir) && $dir != '/')
			{	
				$result = FileHelper::findFiles($dir);
			}
		}
		
		return $result;
	}
	
	public static function uploadImageFromTmpDir($category, $objectID, $multi = false, $separateFolder = false)
	{
		$result = false;
		$params = Yii::$app->params['upload_dir'];
		
		if($category != '' && $objectID > 0 && isset($params[$category]['dir']) && isset($params[$category]['tmp']) && isset($params[$category]['uploads']))
		{	
			$tmpDir = \Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$params[$category]['tmp'];
			$uploadDir = \Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$params[$category]['uploads'];
			
			if(is_dir($tmpDir) && is_dir($uploadDir))
			{	
				$files = FileHelper::findFiles($tmpDir);
				
				if(!empty($files))
				{	
					if($separateFolder)
					{
						$uploadDir.= DIRECTORY_SEPARATOR.$objectID;
							
						if(!is_dir($uploadDir))
						{
							if(!FileHelper::createDirectory($uploadDir))
							{	
								return false;
							}
						}
						else
						{
							if(!$multi)
							{
								if(!FileHelper::removeAllFilesInDir($uploadDir))
								{	
									return false;
								}
							}
						}
					}
						
					if($multi)
					{	
						$result = self::multiUploadFromTmpDir($params, $category, $uploadDir, $tmpDir, $files);
					}
					else
					{	
						$result = self::singleUploadFromTmpDir($params, $category, $tmpDir, $uploadDir, $files);
					}
						
					if($result)
					{
						$result = FileHelper::removeAllFilesInDir($tmpDir);
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function singleUploadFromTmpDir($params, $category, $tmpDir, $uploadDir, $files)
	{
		$result = false;
		$image = new Image;
		$file = end($files);
		$file = explode('/', $file);
		$file = end($file); 
		
		if(isset($params[$category]['image_sizes']['origin_image']['width']) && isset($params[$category]['image_sizes']['origin_image']['width']))
		{	
			$maxWidth = $params[$category]['image_sizes']['origin_image']['width'];
			$maxHeight = $params[$category]['image_sizes']['origin_image']['height'];
		}
		else
		{
			$maxWidth = 2000;
			$maxHeight = 600;
		}
		
		$result = $image->makeImageFromTmpDir($file, $tmpDir, $uploadDir, $maxWidth, $maxHeight, false, false, true);
		
		return $result;
	}
	
	public static function multiUploadFromTmpDir($params, $category, $uploadDir, $tmpDir, $files)
	{
		$result = false;
		
		if(isset($params[$category]['image_sizes']))
		{
			$image = new Image;
			
			$sizes = $params[$category]['image_sizes'];
							
			foreach($sizes as $type => $size)
			{
				if(isset($size['dir']))
				{
					$maxWidth = $size['width'];
					$maxHeight = $size['height'];
					$dir = $uploadDir.DIRECTORY_SEPARATOR.$size['dir'];
									
					if(!is_dir($dir))
					{
						if(!FileHelper::createDirectory($dir))
						{
							return false;
						}
					}
									
					foreach($files as $key=>$file)
					{
						$file = explode('/', $file);
						$file = end($file);
						$crop = (isset($size['crop'])) ? $size['crop'] : false;
						$resize = (isset($size['resize'])) ? $size['resize'] : false;
										
						$result = $image->makeImageFromTmpDir($file, $tmpDir, $dir, $maxWidth, $maxHeight, $resize, $crop);
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function createTextFile($file, $text, $mode = "w+")
    {
		$fp = fopen($file, $mode);
		
		if($fp)
		{	
			if(fwrite($fp, $text))
			{	
				if(fclose($fp))
				{	
					return true;
				}
			}
		}
		
		return false;
	}
	
	public static function readTextFile($file)
    {
		$result = file_get_contents($file);
		
		if(!$result)
		{
			return '';
		}
		
		return $result;
	}
	
	public static function getFileFromFileList($files)
    {
		$result = '';
		
		if(is_array($files) && !empty($files))
		{
			$file = end($files);
			$file = explode('/', $file);
			
			if(is_array($file) && !empty($file))
			{
				$result = end($file);
			}
		}
		
		return $result;
	}
	
	public static function getUploadDir($category, $tmp = false, $id = 0)
    {
		$result = '';
		
		if(isset(Yii::$app->params['upload_dir'][$category]['dir']))
		{	
			if($tmp && isset(Yii::$app->params['upload_dir'][$category]['tmp']))
			{
				$result = Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR;
			}
			elseif(!$tmp && $id > 0 && isset(Yii::$app->params['upload_dir'][$category]['uploads']))
			{
				$result = Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
			}
		}
		
		return $result;
	}
	
	public static function getUploadDirUrl($id, $category, $listview = true, $dirName = 'thumbnail')
    {
		$result = '';
		
		if($id > 0)
		{
			if($listview)
			{
				if(isset(Yii::$app->params['upload_dir'][$category]['uploads']))
				{
					if(isset(Yii::$app->params['upload_dir'][$category]['image_sizes'][$dirName]['dir']) && isset(Yii::$app->params['upload_dir'][$category]['dir']) && isset(Yii::$app->params['upload_dir'][$category]['uploads']))
					{
						$result = Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes'][$dirName]['dir'];
					}
				}
			}
			else
			{
				if(isset(Yii::$app->params['upload_dir'][$category]['uploads']))
				{
					if(isset(Yii::$app->params['upload_dir'][$category]['dir']) && isset(Yii::$app->params['upload_dir'][$category]['uploads']))
					{
						$result = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes'][$dirName]['dir'])) ? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes'][$dirName]['dir'] : Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
					}
				}
			}
		}
		else
		{
			if(isset(Yii::$app->params['upload_dir'][$category]['tmp']))
			{
				$result = Yii::$app->params['upload_dir'][$category]['tmp'];
			}
		}
		
		return $result;
	}
	
	public static function getThumbnailParams($thumbnail, $dir, $file)
    {
		$result = [];
		$imageParams = true;
		
		if(empty($thumbnail))
		{
			$imageParams = false;
		}
		else
		{
			if($thumbnail['width'] <= 0 || $thumbnail['height'] <= 0)
			{
				$imageParams = false;
			}
		}
		
		if(!$imageParams)
		{	
			if(is_file(Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file))
			{	
				list($thumbnail['width'], $thumbnail['height'], $thumbnail['type'], $thumbnail['attr']) = getimagesize(Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file);
				$result = $thumbnail;
			}
		}
		else
		{
			$result = $thumbnail;
		}
		
		return $result;
	}
	
	public static function getFilesByParams($id, $category, $dirName, $listview = true, $dir = '')
    {
		$result = [];
		$dir = ($dir != '') ? $dir : Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.(self::getUploadDirUrl($id, $category, $listview, $dirName));
		
		if(is_dir($dir))
		{
			$result = FileHelper::findFiles($dir);
		}
		
		return $result;
	}
	
	public static function getFileByCategory($id, $category, $dirName)
    {
		$result = '';
		
		$thumbnail = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'])) ? Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail'] : [];
		
		if(!empty($thumbnail))
		{	
			$dir = Files::getUploadDirUrl($id, $category, true, $dirName);
			
			if($dir != '')
			{	
				$files = self::getFilesByParams($id, $category, $dirName);
				$file = (!empty($files)) ? $files[0] : '';
				
				if($file != '')
				{
					$thumbnail = self::getThumbnailParams($thumbnail, Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir, $file);
					$file = explode('/', $file);
					$file = end($file);
					
					$result = Html::img(Url::to('@upload_dir'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file), ['alt'=>'', 'width'=>((isset($thumbnail['width'])) ? $thumbnail['width'].'px' : ''), 'height'=>((isset($thumbnail['height'])) ? $thumbnail['height'].'px' : '')]);
				}
			}
		}
		
		return $result;
	}
}
?>
