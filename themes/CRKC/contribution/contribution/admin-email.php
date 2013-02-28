<p>Op de website <?php echo get_option('site_title'); ?> is er een gestolen object gemeld.</p>
<h3>Beschrijving:</h3>

<?php if(item('Item Type Metadata','P.V. Nummer') != ""){?>
    <h4>P.V. Nummer</h4>
    <p><?php echo (item('Item Type Metadata','P.V. Nummer'))?></p>
<?php } ?>

<?php if(item('Item Type Metadata','Politiezone') != ""){?>
    <h4>Politiezone</h4>
    <p><?php echo (item('Item Type Metadata','Politiezone'))?></p>
<?php } ?>
    
<?php if(item('Item Type Metadata','Datum en plaats van vaststelling door de politie') != ""){?>
    <h4>Datum en plaats van vaststelling door de politie</h4>
    <p><?php echo (item('Item Type Metadata','Datum en plaats van vaststelling door de politie'))?></p>
<?php } ?>
    
<?php if(item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)') != ""){?>
    <h4>Periode van de diefstal</h4>
    <p><?php echo (item('Item Type Metadata','Periode van de diefstal (dag/maand/jaar)'))?></p>
<?php } ?>
    
<?php if(item('Item Type Metadata','Plaats van de diefstal (postcode, gemeente, naam gebouw of instelling)') != ""){?>
    <h4>Plaats van de diefstal (postcode, gemeente, naam gebouw of instelling)</h4>
    <p><?php echo (item('Item Type Metadata','Plaats van de diefstal (postcode, gemeente, naam gebouw of instelling)'))?></p>
<?php } ?>
    
<?php if(item('Dublin Core','Title') != ""){?>
    <h4>Objectnaam</h4>
    <p><?php echo (item('Dublin Core','Title'))?></p>
<?php } ?>    
    
<?php if(item('Dublin Core','Description') != ""){?>
    <h4>Titel of voorstelling</h4>
    <p><?php echo (item('Dublin Core','Description'))?></p>
<?php } ?>
    
<?php if(item('Dublin Core','Creator') != ""){?>
    <h4>Vervaardiger</h4>
    <p><?php echo (item('Dublin Core','Creator'))?></p>
<?php } ?>

<?php if(item('Item Type Metadata','Physical Dimensions') != ""){?>
    <h4>Afmetingen</h4>
    <p><?php echo (item('Item Type Metadata','Physical Dimensions'))?></p>
<?php } ?>
    
<?php if(item('Dublin Core','Type') != ""){?>
    <h4>Materialen</h4>
    <p><?php echo (item('Dublin Core','Type'))?></p>
<?php } ?>

<?php if(item('Dublin Core','Identifier') != ""){?>
    <h4>Unieke kenmerken</h4>
    <p><?php echo (item('Dublin Core','Identifier'))?></p>
<?php } ?>    
    
<?php if(item('Dublin Core','Date') != ""){?>
    <h5>Datering object</h5>
    <p><?php echo (item('Dublin Core','Date'))?></p>
<?php } ?>

<?php if(item('Item Type Metadata','Gegevens contactpersoon (naam, telefoonnummer en e-mailadres)') != ""){?>
    <br><h3>Contactpersoon</h3>
    <p><?php echo (item('Item Type Metadata','Gegevens contactpersoon (naam, telefoonnummer en e-mailadres)'))?></p>
<?php } ?>
	
<p>U kan het gestolen object bekijken en publiceren via:</p>

<?php
    set_theme_base_uri('admin');
    echo abs_item_uri($item);
    set_theme_base_uri();
?>
