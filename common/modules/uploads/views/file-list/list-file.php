<?php
use yii\helpers\Url;
use yii\helpers\FileHelper;
use common\modules\uploads\models\Files;

if(isset($category) && isset($tmp) && isset($thumbnail)): 
	$dir = Files::getUploadDir($category, $tmp);
	
	if($dir != ''):
		$files = Files::getFiles($category, $tmp);
		$wrap_id = (isset($wrap_id)) ? $wrap_id : 1;
		?>
		<div id="files-upload" file_type="<?= ($tmp) ? 'tmp' : 'dir'; ?>" class="files_<?= $category?>" category="<?= $category; ?>" url="<?= Url::base(); ?>" wrap_id="<?= $wrap_id; ?>" item_id="<?= $id; ?>"> 
		<?php
		if(!empty($files)):
			foreach($files as $key => $file):
				$file = explode('/', $file);
				$file = end($file);
				
				if($file != ''):
				$thumbnail = Files::getThumbnailParams($thumbnail, $dir, $file);
				?>
				<div class="file_wrap" file_type="<?= ($tmp) ? 'tmp' : 'dir'; ?>" file="<?= $file?>" <?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?>style="width:<?= $thumbnail['width'] ?>px; height:<?= ($thumbnail['height'] + 20) ?>px;"<?php else: ?>style="width:auto; height:auto; margin:10px;"<?php endif; ?>>
					<div class="file_delete_wrap" align="right">
						<a class="file_delete" href="#">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</div>
					<div class="image-wrap">
						<img src="<?= Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file ?>"<?php if($thumbnail['width'] > 0 && $thumbnail['height'] > 0): ?> width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;"<?php endif; ?>>
					</div>
				</div>
				<?php
				endif;
			endforeach;
		endif;
		?>
		</div> 
		<?php
	endif;
?>
<div class="clear"></div>
<?php
	if($id > 0):
		$dir = Files::getUploadDirUrl($id, $category, false);
		
		if(is_dir(Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir)):
			$files = FileHelper::findFiles(Yii::getAlias('@uploads_dir').DIRECTORY_SEPARATOR.$dir); 
			?>
			<div id="files-upload" file_type="dir" class="files_<?= $category?>" category="<?= $category; ?>" url="<?= Url::base(); ?>" wrap_id="<?= $wrap_id; ?>" item_id="<?= $id; ?>"> 		
				<?php 
				if(!empty($files)):
					foreach($files as $key => $file):
						$file = explode('/', $file);
						$file = end($file);
						$thumbnail = Files::getThumbnailParams($thumbnail, $dir, $file);
						?>
						<div class="file_wrap" file_type="file" file="<?= $file?>" style="width:<?= $thumbnail['width'] ?>px; height:<?= $thumbnail['height'] ?>px;">
							<div align="right">
								<a class="file_delete" href="#">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
							</div>
							<div>
								<img src="<?= Yii::getAlias('@upload_dir').DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file; ?>" width="<?= $thumbnail['width'] ?>px;" height="<?= $thumbnail['height'] ?>px;">
							</div>
						</div>
					<?php
					endforeach; 
				endif;
				?>
			</div>
			<?php 
		endif;
	endif;
endif; 
?>
