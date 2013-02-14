<?php head(array('title' => item('Dublin Core', 'Title'), 'bodyid'=>'items','bodyclass' => 'show')); ?>

<div id="primary">
	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>

<div id="secondary">
	<div class="inhoud">

	<h2><?php echo item('Dublin Core', 'Title'); ?></h2>

	<!-- ERFGOED -->
	<? if(item_has_type('Erfgoed object') || item_has_type('Onder de loep')){?>

	    <div class="show-img">
			<?php echo digitool_simple_gallery(get_current_item());?>
		</div>

        <div class="element">
        	<?php if ($subj = item('Dublin Core', 'Subject')): ?>

					<h5>Onderwerp</h5>
					<p><?php echo $subj; ?></p>

			<?php endif; ?>

			<?php if ($descr = item('Dublin Core', 'Description')): ?>

					<h5>Beschrijving</h5><p><?php echo $descr; ?></p>

			<?php endif; ?>

			<?php if ($extent = item('Dublin Core', 'Extent')): ?>

					<h5>Afmetingen</h5><p><?php echo $extent; ?></p>

			<?php endif; ?>

			<?php if ($temp = item('Dublin Core', 'Temporal Coverage', array('delimiter' => ', '))): ?>

					<h5>Periode</h5><p><?php echo $temp; ?></p>

			<?php endif; ?>

			<?php if ($type = item('Dublin Core', 'Type', array('delimiter' => ', '))): ?>

					<h5>Materiaal en technieken</h5><p><?php echo $type; ?></p>

			<?php endif; ?>

			<p>
				<?php echo Libis_link_to_related_exhibits(get_current_item()->id);?>
			</p>
		</div>



		<!-- The following prints a list of all tags associated with the item -->
	    <?php if (item_has_tags()): ?>
	    <div id="item-tags" class="element">
	        <h3><?php echo __('Tags'); ?></h3>
	        <div class="element-text"><?php echo item_tags_as_string(); ?></div>
	    </div>
	    <?php endif;?>



	<?php }?>
	<!-- GESTOLEN OBJECT -->
	<? if(item_has_type('Gestolen Object')){?>
		<?php if (item_has_thumbnail()): ?>
	        <div class="item-img">
	            <?php echo link_to_item(item_thumbnail()); ?>
	        </div>
	        <?php endif; ?>
	<?php if(item('Dublin Core','Description') != ""){?>
		<h4>Titel of voorstelling</h4>
		<p><?php echo (item('Dublin Core','Description'))?></p>
	<?php } ?>
	<?php if(item('Dublin Core','Creator') != ""){?>
		<h4>Vervaardiger</h4>
		<p><?php echo (item('Dublin Core','Creator'))?></p>
	<?php } ?>
		<?php if(item('Dublin Core','Type') != ""){?>
			<h4>Materialen</h4>
			<p><?php echo (item('Dublin Core','Type'))?></p>
		<?php } ?>

		<?php if(item('Item Type Metadata','Physical Dimensions') != ""){?>
			<h4>Afmetingen</h4>
			<p><?php echo (item('Item Type Metadata','Physical Dimensions'))?></p>
		<?php } ?>

		<?php if(item('Dublin Core','Date') != ""){?>
			<h5>Datering object</h5>
			<p><?php echo (item('Dublin Core','Date'))?></p>
		<?php } ?>
		<?php if(item('Dublin Core','Identifier') != ""){?>
			<h4>Unieke kenmerken</h4>
			<p><?php echo (item('Dublin Core','Identifier'))?></p>
		<?php } ?>

		<?php if(item('Item Type Metadata','Politiezone') != ""){?>
			<h4>Gemeente</h4>
			<p><?php echo (item('Item Type Metadata','Politiezone'))?></p>
		<?php } ?>

		<?php if(item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)') != ""){?>
			<h4>Periode van de diefstal</h4>
			<p><?php echo (item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)'))?></p>
		<?php } ?>

		<p><a href="<?php echo uri('aangifte?id='.get_current_item()->id);?>">Signaleer object</a></p>

	<?php }?>

	<!-- AGENDAPUNT -->
	<?php if(item_has_type('Agendapunt')){ ?>
		<?php if(item('Item Type Metadata','Startdatum') != ""){?>
			<span class='agenda-datum'>Van <?php echo item('Item Type Metadata', 'Startdatum')?></span>
		<?php } ?>
		<?php if(item('Item Type Metadata','Einddatum') != ""){?>
			<span class="agenda-datum">tot <?php echo item('Item Type Metadata', 'Einddatum')?></span>
		<?php } ?>
		<br><br>
		<p><?php echo item('Dublin Core', 'Description');?></p>
		<?php if(item('Item Type Metadata','URL') != ""){?>
			<p><a href="<?php echo item('Item Type Metadata', 'URL')?>">Meer info</a> <p>
		<?php } ?>
		<?php if(item('Item Type Metadata','Openingsuren') != ""){?>
			<p><b>Openingsuren:</b> <?php echo item('Item Type Metadata', 'Openingsuren')?><p>
		<?php } ?>
		<?php if(item('Item Type Metadata','Location') != ""){?>
			<p><b>Locatie:</b> <?php echo item('Item Type Metadata', 'Location')?><p>
		<?php } ?>
		<?php if(item('Item Type Metadata','Toegangsprijs') != ""){?>
			<p><b>Toegangsprijs:</b> <?php echo item('Item Type Metadata', 'Toegangsprijs')?><p>
		<?php } ?>
	<? } ?>

	<!-- NIEUWBERICHT -->
	<?php if(item_has_type('Nieuwsbericht')){ ?>
            <div class="show-img"><?php echo display_files_for_item(); ?></div>
		<p><?php echo item('Dublin Core', 'Description');?></p>
		<?php if(item('Item Type Metadata','URL')):?>
				<p><label>Meer informatie: </label>
				<a href="<?php echo item('Item Type Metadata','URL');?>">
				<?php echo item('Item Type Metadata','URL');?>?></a>
			<?php endif;

	}?>

    <?php echo plugin_append_to_items_show(); ?>

    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item("Vorige"); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item("Volgende"); ?></li>
        <?php if(item_has_type('Gestolen Object')){?>
			<li><a href="<?php echo uri('/items/browse?collection=1') ?>">/ Terug naar de lijst</a></li>
		<?php } ?>
		<?php
			if($_SERVER["HTTP_REFERER"]){
				$back = htmlspecialchars($_SERVER["HTTP_REFERER"]);

				//if previous url has 4 slashes and contains de hostname it will most likely be a simple page
				if(substr_count($back, '/') == 4 && strpos($back,$_SERVER["HTTP_HOST"])){
					echo "<li>/ <a href='".$back."'>Terug naar overzicht</a></li>";
				}
			}
		?>
    </ul>

</div>
</div>
<?php foot(); ?>