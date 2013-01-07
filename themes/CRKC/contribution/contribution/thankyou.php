<?php head(); ?>
<div id="primary">
	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>
<div id="secondary">


	<h1>Succes</h1>
	<p>Uw aangifte werd correct ontvangen en zal spoedig behandeld worden door de politie.</p>

</div>
<?php foot(); ?>