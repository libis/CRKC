<?php
//als gestolen objecten collectie -> andere titel
if($_GET['collection'] == 1)
	$pageTitle = "Gestolen Objecten";
else
	$pageTitle = __('Browse Items');

head(array('title'=>$pageTitle,'bodyid'=>'items','bodyclass' => 'browse'));
?>
<div id="primary">
	<div class="gray-box">
		<img title="Ontdek het religieus erfgoed" alt="Ontdek het religieus erfgoed" src="<?php echo img('ontdek-titel-zwart.png');?>" ></img>

		<?php echo simple_search("Zoek",array('id' => 'left-search'),uri('items/browse/?type=Erfgoed object')); ?>
    	<div id="box-nav"><a href="">Meer info</a> / <a href="">Zoektips</a> / <?php echo link_to_advanced_search("Uitgebreid zoeken"); ?>
    	</div>
	</div>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>

<div id="secondary">
	<div class="inhoud">
    <h1><?php echo $pageTitle;?> <?php echo __('(%s total)', total_results()); ?></h1>

    <div id="pagination-top" class="pagination-browse"><?php echo pagination_links(); ?></div>

    <?php while (loop_items()): ?>
    <div class="item hentry">

        <div class="item-meta">

		<!-- GESTOLEN OBJECT -->
		<?php if(item_has_type('Gestolen Object')){?>

			<?php if (item_has_thumbnail()): ?>
	        <div class="item-img">
	            <?php echo link_to_item(item_thumbnail()); ?>
	        </div>
	        <?php endif; ?>

			<p><b>Objectnaam:</b> <?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></p>

			<?php if(item('Dublin Core','Description') != ""){?>
				<p><b>Titel of voorstelling</b>
				<?php echo (item('Dublin Core','Description'))?></p>
			<?php } ?>
			<?php if(item('Dublin Core','Creator') != ""){?>
				<p><b>Vervaardiger</b>
				<?php echo (item('Dublin Core','Creator'))?></p>
			<?php } ?>
			<?php if(item('Dublin Core','Type') != ""){?>
				<p><b>Materialen</b>
				<?php echo (item('Dublin Core','Type'))?></p>
			<?php } ?>

			<?php if(item('Item Type Metadata','Physical Dimensions') != ""){?>
				<p><b>Afmetingen:</b>
				<?php echo (item('Item Type Metadata','Physical Dimensions'))?></p>
			<?php } ?>

			<?php if(item('Dublin Core','Date') != ""){?>
				<p><b>Datering object:</b>
				<?php echo (item('Dublin Core','Date'))?></p>
			<?php } ?>
			<?php if(item('Dublin Core','Identifier') != ""){?>
				<p><b>Unieke kenmerken:</b>
				<?php echo (item('Dublin Core','Identifier'))?></p>
			<?php } ?>

			<?php if(item('Item Type Metadata','Politiezone') != ""){?>
				<p><b>Gemeente:</b>
				<?php echo (item('Item Type Metadata','Politiezone'))?></p>
			<?php } ?>

			<?php if(item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)') != ""){?>
				<p><b>Periode van de diefstal:</b>
				<?php echo (item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)'))?></p>
			<?php } ?>

			<p><a href="<?php echo uri('aangifte?id='.get_current_item()->id);?>">Signaleer object</a></p>

		<?php }
		elseif(item_has_type('Nieuwsbericht')) {
		?>
			<p><b>Titel:</b> <?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></p>

			<?php if(item('Dublin Core','Description') != ""){?>
				<p><b>Samenvatting:</b></p>
				<?php echo Libis_excerpt(item('Dublin Core', 'Description'),250,"..."); ?>
			<?php } ?>
		<?php } elseif(item_has_type('Agendapunt')) {
		?>
			<p><b>Titel:</b> <?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></p>

			<?php if(item('Item Type Metadata','Startdatum') != "")?>
				<span class='agenda-datum'>Van <?php echo item('Item Type Metadata', 'Startdatum')?></span>

			<?php if(item('Item Type Metadata','Einddatum') != "")?>
				<span class="agenda-datum">tot <?php echo item('Item Type Metadata', 'Einddatum')?></span>

			<p><?php echo Libis_excerpt(item('Dublin Core', 'Description'),250,"..."); ?></p>
				<p><?php echo link_to_item("Lees meer"); ?></p>
		<?php

		//ERFGOED OBJECT
		}elseif(item_has_type('Erfgoed object')) {
		?>
			<h2><?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></h2>

	       <?php if (digitool_thumbnail(get_current_item())){ ?>
				<div class="item-img">
					<?php 	echo link_to_item(digitool_thumbnail(get_current_item()));?>
				</div>
			<?php } ?>

			<p><b>Objectnaam:</b> <?php echo link_to_item(item('Dublin Core', 'Subject'), array('class'=>'permalink')); ?></p>

			<?php if(item('Dublin Core','Description') != ""){?>
				<p><b>Titel of voorstelling:</b>
				<?php echo (item('Dublin Core','Description'))?></p>
			<?php } ?>
			<?php if(item('Dublin Core','Creator') != ""){?>
				<p><b>Vervaardiger:</b>
				<?php echo (item('Dublin Core','Creator'))?></p>
			<?php } ?>
			<?php if(item('Dublin Core','Type') != ""){?>
				<p><b>Materialen:</b>
				<?php echo (item('Dublin Core','Type'))?></p>
			<?php } ?>

			<?php if(item('Dublin Core','Extent') != ""){?>
				<p><b>Afmetingen:</b>
				<?php echo (item('Dublin Core','Extent'))?></p>
			<?php } ?>

			<?php if(item('Dublin Core','Temporal Coverage') != ""){?>
				<p><b>Datering object:</b>
				<?php echo (item('Dublin Core','Temporal Coverage'))?></p>
			<?php } ?>
		<?php }
		?>
        </div><!-- end class="item-meta" -->
    </div><!-- end class="item hentry" -->
    <?php endwhile; ?>

    <div id="pagination-bottom" class="pagination-browse"><?php echo pagination_links(); ?></div>

    <?php echo plugin_append_to_items_browse(); ?>
</div>
</div>

<?php foot(); ?>
