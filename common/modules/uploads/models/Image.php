<?php
namespace common\modules\uploads\models;

use Yii;
/**
 * Image class.
 * Image class is the class for working
 * with image data.
 */
class Image 
{	
	public $error = '';
	
	// PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $errorMessages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height',
        'abort' => 'File upload aborted',
        'image_resize' => 'Failed to resize image'
    );
    
    protected function getErrorMessage($error) 
    {
        return isset($this->errorMessages[$error]) ? $this->errorMessages[$error] : $error;
    }
    
    public static function getConfigBytes($val) 
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        
        switch($last) 
        {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return self::fixIntegerOverflow($val);
    }
    
    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected static function fixIntegerOverflow($size) 
    {
        if($size < 0) 
        {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        
        return $size;
    }
    
    public function validateFile($model) 
    {
		if(isset($_FILES[$model]) && !empty($_FILES[$model])) 
		{
			if($_FILES[$model]['error'] > 0)
			{
				$this->error = $this->getErrorMessage($_FILES[$model]['error']);
				return false;
			}
			
			//Get of image size
			$imageSize = $_FILES[$model]['size'];
			
			$postMaxSize = $this->getConfigBytes(ini_get('post_max_size'));
			
			if ($postMaxSize && ($imageSize > $postMaxSize)) 
			{
				$this->error = self::getErrorMessage('post_max_size');
				return false;
			}
			
			$validTypes = (isset(Yii::$app->params['valid_image_types'])) ? Yii::$app->params['valid_image_types'] : [];
			
			if(!empty($validTypes))
			{        
				$sourceFile = $_FILES[$model]['name'];
				
				//Extension of image source.
				$ext = substr($sourceFile, 1 + strrpos($sourceFile, "."));
				
				$validTypes = array_flip($validTypes);
				
				if(!isset($validTypes[$ext])) 
				{
					$this->error = $this->getErrorMessage('accept_file_types');
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		
		$this->error = $this->getErrorMessage(4);
		return false;
	}
	
	public static function renameFileInHash($fileName) 
    {
		$result = '';
		
		if($fileName !== '')
		{
			$parts = explode(".", $fileName);
			
			if(!empty($parts)) 
			{	
				$result = mb_strtolower(\Yii::$app->security->generateRandomString(12), 'UTF-8').'.'.$parts[1];
			}
		}
		
		return $result;
	}
	
	public function singleRawUpload($model, $uploadDir)
	{
		$result = false;
		$fileName = '';
		
		if(@is_uploaded_file($_FILES[$model]['tmp_name'])) 
		{	
			if($this->validateFile($model))
			{	
				$fileName = self::renameFileInHash(basename($_FILES[$model]["name"]));
				
				if($fileName != '')
				{	
					$file = \Yii::getAlias('@content_uploads_dir').DIRECTORY_SEPARATOR.$uploadDir.DIRECTORY_SEPARATOR.$fileName;
					echo $file;
					if(@move_uploaded_file($_FILES[$model]['tmp_name'], $file))
					{	
						$result = true;
					}
				}
			}
		}
		
		return [$result, $this->error, $fileName];
	}
	
	/**
	* Calculate size of image.
	* @param $width passes width of image.
	* @param $big define big or small size of image to calculate.
	* @return array.
	*/
	public static function calculateImageSize($width, $height, $maxWidth, $maxHeight)
	{
		$size = array();
		
		if($maxWidth > 0)
		{
			$proportion = ($width > $height) ? round($width / $height, 2) : round($height / $width, 2);
			
			if($maxWidth == $maxHeight)
			{
				if($width > $height)
				{	
					$size[0] = round($maxHeight * $proportion, 0);
					$size[1] = $maxHeight;
				}
				else
				{
					$size[0] = $maxWidth;
					$size[1] = round($maxWidth * $proportion, 0);
				}
			}
			else
			{
				if($width > $height)
				{	
					$size[0] = $maxWidth;
					$size[1] = round($maxWidth / $proportion, 0);
				}
				else
				{
					$size[0] = round($maxHeight / $proportion, 0);
					$size[1] = $maxHeight;
				}
			}
		}
		else
		{
			$size[0] = $width;
			$size[1] = $height;
		}
		
		return $size;
	}
	
	/**
	* Make image in server side from temporary directory.
	* @param $sourceFile passes filename to upload.
	* @param $tmpDir passes path of temporary directory.
	* @param $uploadDir  passes path of uploaded directory.
	* @param $fileID passes id of filename to upload.
	* @param $maxWidth passes maximal width of image for calculating of image size.
	* @param $quality passes quality of image in jpeg format.
	* method is static
	* @return bool.
	*/
	public function makeImageFromTmpDir($sourceFile, $tmpDir, $uploadDir, $maxWidth, $maxHeight, $resize = true, $crop = false, $copy = false, $quality = 100)
	{	
		$result = false;
		
		//Extension of image source.
		$ext = substr($sourceFile, 1 + strrpos($sourceFile, "."));
		
		//Source file path
		$filetmp = $tmpDir.DIRECTORY_SEPARATOR.$sourceFile;
		
		//Get of image size
		$imageSize = getimagesize($filetmp);
		
		//Set image size
		if($maxWidth == 0)
		{
			$maxWidth = $imageSize[0];
		}
		
		//Set image size
		if($maxHeight == 0)
		{
			$maxHeight = $imageSize[1];
		}
		
		if((($maxWidth >= $imageSize[0]) && ($maxHeight >= $imageSize[1])) || ($copy))
		{	
			//echo $uploadDir.DIRECTORY_SEPARATOR.$sourceFile.'Work!'.'<br/>';
			if(copy($filetmp, $uploadDir.DIRECTORY_SEPARATOR.$sourceFile))
			{	
				$result = true;
			}
		}
		else
		{	
			if($resize)
			{
				//Create and resize image in server side from temp directory
				$result =  $this->resizeImage($filetmp, $uploadDir.DIRECTORY_SEPARATOR.$sourceFile, $imageSize, $maxWidth, $maxHeight, $ext, $quality);
			}
			
			if($crop)
			{	
				//Crop image
				$result = $this->cropImage($uploadDir.DIRECTORY_SEPARATOR.$sourceFile, $maxWidth, $maxHeight, $ext, $quality);
			}
		}
		
		return $result;
	}
	
	public function cropImage($uploadfile, $maxWidth, $maxHeight, $ext, $quality)
	{
		$result = false;
		
		//Creating of dummy image
		$sourceFile = $this->createDummyImage($uploadfile, $ext);
		
		//If dummy jpg image is created	
		if($sourceFile)
		{
			//Get of image size
			$imageSize = getimagesize($uploadfile);

			if(($imageSize[0] >= $maxWidth) && ($imageSize[1] >= $maxHeight))
			{
				//Set crop coordinates
				$x = ($imageSize[0] > $imageSize[1]) ? ($imageSize[0] - $maxWidth) / 2 : 0;
				$y = ($imageSize[0] > $imageSize[1]) ? 0 : ($imageSize[1] - $maxHeight) / 2;
				$cropArray = array('x' => $x , 'y' => $y, 'width' => $maxWidth, 'height'=> $maxHeight);
				$cropFile = imagecrop($sourceFile, $cropArray);
				
				//If image is croped	
				if($cropFile)
				{
					$result = $this->createRealImage($cropFile, $ext, $uploadfile, $quality);
				}
			}
			else
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	* Create and resize image in server side from temp directory.
	* @param $filetmp passes image data.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $maxWidth passes maximal width of image for calculating of image size.
	* @param $ext passes extension of image source.
	* @param $quality passes quality of image in jpeg format.
	* @return bool.
	*/
	public function resizeImage($filetmp, $uploadfile, $imageSize, $maxWidth, $maxHeight, $ext, $quality)
	{
		$result = false;
		
		//Calculate of image size	
		$size = self::calculateImageSize($imageSize[0], $imageSize[1], $maxWidth, $maxHeight);
		
		//Create and resize image from image source
		$result = $this->createRealImageFromImageSource($filetmp, $uploadfile, $ext, $size, $imageSize, $quality);
		
		return $result;
	} 
	
	/**
	* Create and resize image from image source.
	* @param $filetmp passes image data.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $ext passes extension of image source.
	* @param $size passes calculated size of image source.
	* @param $imageSize passes size of image source.
	* @param $quality passes quality of image in jpeg format.
	* @return bool.
	*/
	public function createRealImageFromImageSource($filetmp, $uploadfile, $ext, $size = array(), $imageSize = array(), $quality)
	{
		$result = false;
		
		if(count($size) > 0 && count($imageSize) > 0 && $filetmp != '' && $uploadfile != '' && $ext != '')
		{
			$dstImage = imagecreatetruecolor($size[0], $size[1]);
			
			//If dummy image is created
			if($dstImage)
			{	
				//Creating of dummy image
				$srcImage = $this->createDummyImage($filetmp, $ext);
						
				//If dummy jpg image is created	
				if($srcImage)
				{	
					//If image is resized
					if(imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $size[0], $size[1], $imageSize[0], $imageSize[1]))
					{	
						$realImage = $this->createRealImage($dstImage, $ext, $uploadfile, $quality);
							
						//Create real image from dummy
						if($realImage)
						{	
							//Destroy image source
							if(imagedestroy($dstImage))
							{	
								$result = true;
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	/*
	* Create dummy image from image source.
	* @param $filetmp passes image data.
	* @param $ext passes extension of image source.
	* @return bool.
	*/
	public function createDummyImage($filetmp, $ext)
	{
		$result = false;
		
		//Create image dummy by its extension
		switch($ext)
		{
			case 'jpg':
			$result = imagecreatefromjpeg($filetmp);
			break;
			
			case 'jpeg':
			$result = imagecreatefromjpeg($filetmp);
			break;
			
			case 'png':
			$result = imagecreatefrompng($filetmp);
			break;
			
			case 'gif':
			$result = imagecreatefromgif($filetmp);
			break;
			
			default:
			$result = imagecreatefromjpeg($filetmp);
		}
		
		return $result;
	}
	
	/*
	* Create real image from image source.
	* @param $filetmp passes image data.
	* @param $ext passes extension of image source.
	* @param $uploadfile passes pathname of uploaded file.
	* @param $quality passes quality of jpeg image.
	* @return bool.
	*/
	public function createRealImage($dstImage, $ext, $uploadfile, $quality)
	{
		$result = false;
		
		//Create real image by its extension
		switch($ext)
		{
			case 'jpg':
			$result = imagejpeg($dstImage, $uploadfile, $quality);
			break;
			
			case 'jpeg':
			$result = imagejpeg($dstImage, $uploadfile, $quality);
			break;
			
			case 'png':
			$result = imagepng($dstImage, $uploadfile);
			break;
			
			case 'gif':
			$result = imagegif($dstImage, $uploadfile);
			break;
			
			default:
			$result = imagejpeg($dstImage, $uploadfile, $quality);
		}
		
		return $result;
	}
}
