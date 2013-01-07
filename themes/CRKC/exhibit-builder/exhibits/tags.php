<?php
$title = __('Browse Exhibits by Tag');
head(array('title' => $title, 'bodyid' => 'exhibit', 'bodyclass' => 'tags'));
?>
<div id="primary">

	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>
<div id="secondary">
	<div class="inhoud">
<h1><?php echo $title; ?></h1>
<ul class="navigation exhibit-tags" id="secondary-nav">
    <?php echo nav(array(
        __('Browse All') => uri('exhibits/browse'),
        __('Browse by Tag') => uri('exhibits/tags')
    )); ?>
</ul>

<?php echo tag_cloud($tags,uri('exhibits/browse')); ?>
</div></div><?php foot(); ?>
