<?php head(); ?>
<h1><?php echo settings('simple_contact_form_contact_page_title'); ?></h1>
<div id="primary">
    
<div id="simple-contact">
	<div id="form-instructions">
		<?php echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
	</div>
	<?php echo flash(); ?>
	<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
        
        <fieldset>
            
        <div class="field">
		<?php 
		// aangepast door Sam
		    echo $this->formLabel('name', 'Uw naam: ');
		    echo $this->formText('name', $name, array('class'=>'textinput')); ?>
		</div>
		
        <div class="field">
            <?php 
            // aangepast door Sam
            echo $this->formLabel('email', 'Uw e-mailadres: ');
		    echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
        </div>
        
		<div class="field">
		  <?php 
		  // aangepast door Sam
		    echo $this->formLabel('message', 'Uw bericht: ');
		    echo $this->formTextarea('message', $message, array('class'=>'textinput')); ?>
		</div>    
		
		</fieldset>
		
		<fieldset>
		    
		<div class="field">
		  <?php echo $captcha; ?>
		</div>		
		
		<div class="field">
		  <?php 
		  // aangepast door Sam
		  echo $this->formSubmit('send', 'Verstuur bericht'); ?>
		</div>
	    
	    </fieldset>
	</form>
</div>

</div>
<?php foot(); ?>