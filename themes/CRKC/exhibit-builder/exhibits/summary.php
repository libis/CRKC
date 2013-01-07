<?php head(array('title' => html_escape('Summary of ' . exhibit('title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">

	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>
<div id="secondary">
	<div class="inhoud">

		<h1><?php echo html_escape(exhibit('title')); ?></h1>
		<?php echo exhibit_builder_section_nav(); ?>



		<div class="show-img">
			<?php
				if(Libis_get_first_item_exhibit($exhibit))
					echo digitool_get_thumb(Libis_get_first_item_exhibit($exhibit),true,false,200);
			?>
		</div>

		<h3><?php echo __('Description'); ?></h3>

		<?php echo exhibit('description'); ?>

		<h3 style="clear:both">Auteurs<?php
		//echo __('Credits');
		?></h3>
		<p><?php echo html_escape(exhibit('credits')); ?></p>

		<div id="exhibit-sections">
			<?php set_exhibit_sections_for_loop_by_exhibit(get_current_exhibit()); ?>
			<h3>Hoofdstukken<?php
			//echo __('Sections');
			?></h3>
			<?php while(loop_exhibit_sections()): ?>
			<?php if (exhibit_builder_section_has_pages()): ?>
		    <h3><a href="<?php echo exhibit_builder_exhibit_uri(get_current_exhibit(), get_current_exhibit_section()); ?>"><?php echo html_escape(exhibit_section('title')); ?></a></h3>
			<?php echo exhibit_section('description'); ?>
			<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>
<?php foot(); ?>
