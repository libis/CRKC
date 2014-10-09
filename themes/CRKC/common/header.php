<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="<?php echo get_theme_option('Style Sheet'); ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo settings('site_title'); echo $title ? ' | ' . $title : ''; ?></title>

<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo settings('description'); ?>" />

<?php echo auto_discovery_link_tag(); ?>

<!-- Plugin Stuff -->
<?php plugin_header(); ?>

<!-- Stylesheets -->
<?php
queue_css('style');
queue_css('styleDropdown');

display_css();
?>

<!-- JavaScripts -->
<?php //echo queue_js('jquery.dropdown');?>
<?php echo queue_js('jquery-1.6.2');?>
<?php echo queue_js('jquery.inputfieldtext');//handles the text in the input fields on the homepage?>
<?php echo queue_js('jquery.nivo.slider');//slideshow on homepage?>
<?php echo queue_js('slides.jquery');//slides 2de poging?>
<?php display_js(); ?>

<!-- default zoekwaarden invullen in de zoekboxen -->
<script type="text/javascript">
jQuery('document').ready(function($){

   $('#top-search input[type=text]').inputFieldText('zoeken', 'hint');
   $('#left-search input[type=text]').inputFieldText('zoeken in de publieke databank', 'hint');

   $('#top-search').focus(function() {
	    if (!$(this).data('zoeken')) {
	        $(this).data('zoeken', $(this).val());
	    }
	    if ($(this).val() == $(this).data('zoeken')) {
	        $(this).val('');
	    }
	}).blur(function(){
	    if ($(this).val() == '') {
	        $(this).val($(this).data('zoeken'));
	    }
	});

  $('#left-search').focus(function() {
	    if (!$(this).data('zoeken in de publieke databank')) {
	        $(this).data('zoeken in de publieke databank', $(this).val());
	    }
	    if ($(this).val() == $(this).data('zoeken in de publieke databank')) {
	        $(this).val('');
	    }
	}).blur(function(){
	    if ($(this).val() == '') {
	        $(this).val($(this).data('zoeken in de publieke databank'));
	    }
	});


   $('#slider').nivoSlider({
	   manualAdvance: true
	}
   );

});

</script>

</head>
<body<?php echo $bodyid ? ' id="'.$bodyid.'"' : ''; ?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>>
	<div id="wrap">
		<div id="header">
    		<div id="site-title">
    			<a href="<?php echo uri('') ?>"><img src="<?php echo img("title.jpg"); ?>" width="500"></img></a>
    		</div>
    		<div id="search-container">
    			<?php echo simple_search("Zoek",array('id' => 'top-search'),'/items/browse?type=Agendapunt'); ?>
    			<div id="search-nav">
    			<a href="<?php echo uri('/') ?>">Home</a> / <a href="/databank/">CRKC-databank</a> / <a href="/faqs/">FAQ's</a> / <a href="/contact/">Contact</a> / <a href="<?php echo uri('/ca_crkc/') ?>">Log in op de CRKC-databank</a>
    			</div>

    		</div>

		</div>

		<div id="primary-nav">
			<ul class="navigation dropdown">
			<li><a href="<?php echo uri('religieus-erfgoed/'); ?>">Religieus Erfgoed</a>
				<ul class="sub_menu">
		        	<li><a href="<?php echo uri('objecten/'); ?>">Objecten</a></li>
		        	<li><a href="<?php echo uri('bibliotheken-archieven/'); ?>">Bibliotheek / Archieven</a></li>
		        	<li><a href="<?php echo uri('immaterieel-erfgoed/'); ?>">Immaterieel erfgoed</a></li>
		        	<li><a href="<?php echo uri('gebouwen/'); ?>">Gebouwen</a></li>

		        </ul>
			</li>
			<li><a href="/organisatie/">Organisatie</a></li>
			<li><a href="/diefstal/">Diefstal</a>
				<ul class="sub_menu">
		        	<li><a href="<?php echo uri('items/browse?collection=1',null,array('sort_field' => 'Item Type Metadata,Periode van de diefstal (dag/maand/jaar)','sort_dir' => 'd')); ?>">Gestolen objecten</a></li>
		        	<li><a href="<?php echo uri('aangifte/'); ?>">Aangifte diefstal</a></li>
		        </ul>
			</li>
			<li><a href="/actualiteit/">Actualiteit</a></li>
			<li><a href="/erfgoededucatie/">Erfgoededucatie</a></li>
			</ul>
		</div>



		<div id="content">

		<div id="content-container" class="center-div">
