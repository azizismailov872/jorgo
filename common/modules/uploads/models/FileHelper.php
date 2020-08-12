<?php
namespace common\modules\uploads\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\base\InvalidParamException;

class FileHelper extends BaseFileHelper
{
	/**
	* Remove all files in directory.
	* Method is static.
	* @param $dir passes pathname of directory.
	* @return bool.
	*/
	public static function removeAllFilesInDir($dir, $deleteOwnDir = false)
	{
		$result = true;
		
		if(is_dir($dir))
		{	
			$objects = scandir($dir);
			
			foreach ($objects as $object) 
			{
				if ($object != "." && $object != "..") {
					
					if (filetype($dir."/".$object) == "dir") 
					{	echo $dir."/".$object;
					   self::removeAllFilesInDir($dir."/".$object, true); 
					}
					else 
					{
						unlink($dir."/".$object);
					}
				}
			}
			
			reset($objects);
			
			if($deleteOwnDir)
			{
				rmdir($dir);
			}
		}
		
		return $result;
	}
	
	/**
     * Returns the files found under the specified directory and subdirectories.
     * @param string $dir the directory under which the files will be looked for.
     * @param array $options options for file searching. Valid options are:
     *
     * - filter: callback, a PHP callback that is called for each directory or file.
     *   The signature of the callback should be: `function ($path)`, where `$path` refers the full path to be filtered.
     *   The callback can return one of the following values:
     *
     *   * true: the directory or file will be returned (the "only" and "except" options will be ignored)
     *   * false: the directory or file will NOT be returned (the "only" and "except" options will be ignored)
     *   * null: the "only" and "except" options will determine whether the directory or file should be returned
     *
     * - except: array, list of patterns excluding from the results matching file or directory paths.
     *   Patterns ending with '/' apply to directory paths only, and patterns not ending with '/'
     *   apply to file paths only. For example, '/a/b' matches all file paths ending with '/a/b';
     *   and '.svn/' matches directory paths ending with '.svn'.
     *   If the pattern does not contain a slash /, it is treated as a shell glob pattern and checked for a match against the pathname relative to $dir.
     *   Otherwise, the pattern is treated as a shell glob suitable for consumption by fnmatch(3) with the FNM_PATHNAME flag: wildcards in the pattern will not match a / in the pathname.
     *   For example, "views/*.php" matches "views/index.php" but not "views/controller/index.php".
     *   A leading slash matches the beginning of the pathname. For example, "/*.php" matches "index.php" but not "views/start/index.php".
     *   An optional prefix "!" which negates the pattern; any matching file excluded by a previous pattern will become included again.
     *   If a negated pattern matches, this will override lower precedence patterns sources. Put a backslash ("\") in front of the first "!"
     *   for patterns that begin with a literal "!", for example, "\!important!.txt".
     *   Note, the '/' characters in a pattern matches both '/' and '\' in the paths.
     * - only: array, list of patterns that the file paths should match if they are to be returned. Directory paths are not checked against them.
     *   Same pattern matching rules as in the "except" option are used.
     *   If a file path matches a pattern in both "only" and "except", it will NOT be returned.
     * - caseSensitive: boolean, whether patterns specified at "only" or "except" should be case sensitive. Defaults to true.
     * - recursive: boolean, whether the files under the subdirectories should also be looked for. Defaults to true.
     * @return array files found under the directory. The file list is sorted.
     * @throws InvalidParamException if the dir is invalid.
     */
    public static function findFiles($dir, $options = [])
    {
        if(!is_dir($dir)) 
        {
            throw new InvalidParamException("The dir argument must be a directory: $dir");
        }
        
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        
        if (!isset($options['basePath'])) 
        {
            // this should be done only once
            $options['basePath'] = realpath($dir);
        }
        
        $result = [];
        $list = [];
        $handle = opendir($dir);
        
        if ($handle === false) 
        {
            throw new InvalidParamException("Unable to open directory: $dir");
        }
        
        while (($file = readdir($handle)) !== false) 
        {
            if ($file === '.' || $file === '..') 
            {
                continue;
            }
            
            $path = $dir.DIRECTORY_SEPARATOR.$file;
           
            if(static::filterPath($path, $options)) 
            {
				if(isset($options['dir_structure']) && ($options['dir_structure']))
				{	
					if(is_file($path)) 
					{	
						if(isset($options['real_path']))
						{
							$list[$dir][] = ($options['real_path'] == 'filename') ? $file : $options['real_path'].DIRECTORY_SEPARATOR.$file;
						}
						else
						{
							$list[$dir][] = $path;
						}
					}
					elseif (!isset($options['recursive']) || $options['recursive']) 
					{	
						$list = array_merge($list, static::findFiles($path, $options));
					}
				}
				else
				{
					if(is_file($path)) 
					{	
						if(isset($options['real_path']))
						{
							$list[] = ($options['real_path'] == 'filename') ? $file : $options['real_path'].DIRECTORY_SEPARATOR.$file;
						}
						else
						{
							$list[] = $path;
						}
					}
					elseif (!isset($options['recursive']) || $options['recursive']) 
					{	
						$list = array_merge($list, static::findFiles($path, $options));
					}
				}
            }
        }
       
        closedir($handle);
       
        return $list;
    }
    
    public static function getFileFromUploadDir($url, $id = 0, $options = [])
    {
		$url = ($id > 0) ? Yii::getAlias('@backend_uploads').DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR.$id : $url;
		$file =  '';
		$files = self::findFiles($url, $options);
		$file = (!empty($files)) ? end($files) : $file;
        
		return $file;
	}
}
