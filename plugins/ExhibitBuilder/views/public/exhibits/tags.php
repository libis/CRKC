<?php
$title = __('Browse Exhibits by Tag');
head(array('title' => $title, 'bodyid' => 'exhibit', 'bodyclass' => 'tags'));
?>
<div id="primary">

	<div class="gray-box">
		<img title="Ontdek het religieus erfgoed" alt="Ontdek het religieus erfgoed" src="<?php echo img('ontdek-titel-zwart.png');?>" ></img>

		<?php echo simple_search("Zoek",array('id' => 'left-search'),uri('items/browse/?type=Afbeelding')); ?>
    	<div id="box-nav"><a href="">Meer info</a> / <a href="">Zoektips</a> / <?php echo link_to_advanced_search("Uitgebreid zoeken"); ?>
    	</div>
	</div>
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
</div></div>
<?php foot(); ?>
