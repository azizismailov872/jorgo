<?php
$this->title = $meta_title;
$this->registerMetaTag([
    'name' => 'description',
    'content' => $meta_desc,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $meta_keys,
]);
?>
<section class="box_content box_content_half_top">
	<div class="container">
		<h1 class="page_title"><?= $meta_title; ?></h1>
		<div class="clearfix page_content">
			<?= ($data !== null) ? $data->content : '' ?>
		</div>
	</div>
</section>
