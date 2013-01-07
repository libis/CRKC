<?php

$bodyclass = 'page simple-page';
if (simple_pages_is_home_page(get_current_simple_page())) {
    $bodyclass .= ' simple-page-home';
} ?>

<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(simple_page('slug')))); ?>
<div id="primary">

	<div class="simple-pages-navigation">
		<?php if(Libis_get_simple_pages_nav())
					echo Libis_get_simple_pages_nav();
		?>
	</div>

	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>
<?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
<div id="secondary">
	<?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
	    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
	    <?php endif; ?>
	<div class="inhoud">
		<h2><?php echo html_escape(simple_page('title')); ?></h2>

			<?php echo Libis_loep_query(html_escape(simple_page('title')));?>

		<div>
		<?php echo eval('?>' . simple_page('text')); ?>
		</div>
    </div>
</div>
<?php endif; ?>
<?php echo foot(); ?>
