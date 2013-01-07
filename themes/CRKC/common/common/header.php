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
<!-- JavaScripts -->
<?php echo queue_js('jquery.dropdown');?>
<?php echo queue_js('jquery.inputfieldtext');?>

<?php display_js(); ?>

<!-- default zoekwaarden invullen in de zoekboxen -->
<script type="text/javascript">
jQuery('document').ready(function($){	

   $('#top-search input[type=text]').inputFieldText('zoeken', 'hint');
   $('#left-search input[type=text]').inputFieldText('zoeken in de publieke databank', 'hint');

});
</script>

</head>
<body<?php echo $bodyid ? ' id="'.$bodyid.'"' : ''; ?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>>
	<div id="wrap">
		<div id="header">				
    		<div id="site-title">
    			<img src="<?php echo img("title.jpg"); ?>" width="550"></img>
    		</div>
    		<div id="search-container">
    			<?php echo simple_search("Zoek",array('id' => 'top-search')); ?>
    			<div id="search-nav">Home / Over de Website / FAQ's / Contact / Login databank 
    			</div>
    		
    		</div>
			
		</div>

		<div id="primary-nav">
			<ul class="navigation dropdown">
			<li><a href="<?php echo uri('religieus-erfgoed/'); ?>">Religieus Erfgoed</a>
				<ul class="sub_menu">
		        	<li><a href="<?php echo uri('objecten/'); ?>">Objecten</a></li>
		        	<li><a href="<?php echo uri('bibliotheek-archieven/'); ?>">Bibliotheek / Archieven</a></li>
		        	<li><a href="<?php echo uri('immaterieel-erfgoed/'); ?>">Immaterieel erfgoed</a></li>
		        	<li><a href="<?php echo uri('gebouwen/'); ?>">Gebouwen</a></li>
		        	
		        </ul>
			</li>
			<li><a href="">Organisatie</a></li>
			<li><a href="">Diefstal</a></li>
			<li><a href="">Actualiteit</a></li>
			<li><a href="">Erfgoededucatie</a></li>
			</ul>
		</div>
		
		
			
		<div id="content">
			
		<div id="content-container" class="center-div">