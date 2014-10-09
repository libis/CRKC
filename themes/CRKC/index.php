<?php head(array('bodyid'=>'home')); ?>

	<div id="primary">
		<div class="blue-top">
			<div class="blue-box blue-box-1">
				<img title="Wat is Religieus Erfgoed?" alt="Wat is Religieus Erfgoed?" src="<?php echo img('wat-titel.png');?>" ></img>

				<p>Religieus erfgoed is het geheel van cultuurgoederen,
				 materieel of immaterieel, roerend of onroerend, dat in een religieuze,
				  godsdienstige of devotionele  context of met dit doel tot stand kwam
				   of werd  verworven, hiernaar verwijst of hiermee in verband  staat.</p>
			</div>
			<div class="blue-box" style="display:none;">
				<img title="Ontdek het religieus erfgoed" alt="Ontdek het religieus erfgoed" src="<?php echo img('ontdek-titel.png');?>" ></img>
				<?php echo simple_search("Zoek",array('id' => 'left-search'),uri('items/browse/?type=Erfgoed object')); ?>
	    		<div id="box-nav"><a href="/zoeken-meerinfo/">Meer info</a> / <a href="/zoeken-zoektips/">Zoektips</a> / <a href="<?php echo uri('items/advanced-search/?type=Erfgoed object'); ?>">Uitgebreid zoeken</a>
	    		</div>
			</div>
		</div>
		<?php echo feedCollector_show("feed-box",3); ?>
		<?php //echo Libis_get_nieuws(3);?>


		<!-- AGENDA -->
		<?php echo Libis_get_actualiteit(3,"index");?>

		<div class="gray-box politie-box">
			<div class="aangifte"><span class="span-aangifte"><a href="<?php echo uri('aangifte');?>">Aangifte diefstal</a></span> kunstvoorwerpen uit kerken of kloosters</div>
			<img src="<?php echo img("politie.png");?>" width="60" />
	    </div>

	</div><!-- end primary -->

	<div id="secondary">
		<div id="onder-de-loep" class="slider-wrapper theme-default">

			<!-- title image has to be a simple page as well, otherwise it won't show up in the slideshow -->
			<div id="slides">
		 		<?php echo Libis_crkc_slideshow();?>
		  	</div>
		</div>

		<div class="inhoud">
			<h2>Erfgoed in breedbeeld</h2>
			<!-- 4 featured objecten met afbeeldingen (1ste groter dan de volgende 5)-->
			<?php echo Libis_get_featured_items_and_exhibits(4);?>
                        <div class="lees-meer-index"><a href="<?php echo uri('items/browse?type=17');?>">Lees meer erfgoed in breedbeeld</a>	</div>
	</div><!-- end secondary -->

<?php foot(); ?>
