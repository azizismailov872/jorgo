<?php
use yii\helpers\Url;
use common\components\FileHelper;
use common\modules\uploads\models\Files;

$wrap_id = (isset($wrap_id)) ? $wrap_id : 1;
$alias = isset(Yii::$app->params['upload_dir'][$category]['alias']) ? Yii::$app->params['upload_dir'][$category]['alias'] : 'frontend_uploads';

if(isset(Yii::$app->params['upload_dir'][$category])):
	$files = Files::getFiles($category, $tmp);
	
	if(!empty($files)):
	$dir = ($tmp) ? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['tmp'].DIRECTORY_SEPARATOR : ((Yii::$app->params['upload_dir'][$category]['dir'] != '') ? Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR : '');
	$file = $files[0];
	$file = explode('/', $file);
	$file = end($file);
	
	if($thumbnail['width'] <= 0 && $thumbnail['height'] <= 0)
	{
		if(is_file(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file))
		{	
			list($thumbnail['width'], $thumbnail['height'], $type, $attr) = getimagesize(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$file);
		}
	}
	?>
	<div class="file_wrap" file_type="<?= ($tmp) ? 'tmp' : 'dir'; ?>" file="<?= $file?>" <?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?>style="width:<?= $thumbnail['width'] ?>px; height:<?= $thumbnail['height'] ?>px; overflow:hidden; margin:10px;"<?php else: ?>style="width:auto; height:auto; margin:10px;"<?php endif; ?>>
		<div align="right">
			<a class="file_delete" href="#">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</div>
		<div class="image-wrap">
			<img src="<?= \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir.$file ?>"<?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?> width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;"<?php endif; ?>>
		</div>
	</div>
	<?php 
	endif;
endif;
?>
<div class="clear"></div>
<?php
if($id > 0):
	$files = Files::getFiles($category, false, $id);
	$url = (isset(Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'])) ? Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.Yii::$app->params['upload_dir'][$category]['image_sizes']['thumbnail']['dir'] : Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id;
	if(!empty($files)):
	?>
	<div id="files-upload" class="files_<?= $category?>" url="<?= Url::base(); ?>" wrap_id="<?= $wrap_id; ?>" item_id="<?= $id; ?>"> 		
		<?php
			$dir = ((Yii::$app->params['upload_dir'][$category]['uploads'] != '') ? Yii::$app->params['upload_dir'][$category]['uploads'].DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR : '');
			$file = $files[0];
			$file = explode('/', $file);
			$file = end($file);
			
			if($thumbnail['width'] <= 0 && $thumbnail['height'] <= 0)
			{	
				if(is_file(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file))
				{	
					list($thumbnail['width'], $thumbnail['height'], $type, $attr) = getimagesize(\Yii::getAlias('@'.$alias).DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file);
				}
			}
			
			$dir = Yii::$app->params['upload_dir'][$category]['dir'].DIRECTORY_SEPARATOR.$dir;
		?>
		<div class="file_wrap" file_type="<?= $file_type?>" file="<?= $file?>" style="width:<?= $thumbnail['width'] ?>px; height:<?= $thumbnail['height'] ?>px; overflow:hidden; margin:10px;">
			<div align="right">
				<a class="file_delete" href="#">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
			<div>
				<img src="<?= \Yii::getAlias('@web').DIRECTORY_SEPARATOR.\Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir.$file; ?>" width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;">
			</div>
		</div>
	</div>
	<?php 
	endif; 
endif; ?>
