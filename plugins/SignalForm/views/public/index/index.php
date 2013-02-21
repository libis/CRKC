<?php head(); ?>
<h1><?php echo settings('signal_form_contact_page_title'); ?></h1>
<div id="primary">
    
<div id="simple-contact">
	<div id="form-instructions">
		<?php echo get_option('signal_form_contact_page_instructions'); // HTML ?>
	</div>
	<?php echo flash(); ?>
	<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
        
            <fieldset>
                <div class="field">
                    <?php 
                    // aangepast door Sam
                    echo $this->formLabel('name', 'Uw naam (niet verplicht): ');
                    echo $this->formText('name', $name, array('class'=>'textinput')); ?>
                </div>

                <div class="field">
                    <?php
                    echo $this->formLabel('email', 'Uw e-mailadres (niet verplicht): ');
                    echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
                </div>
                
                <?php if($_GET['id']){?>
                <div class="field">
                    <?php                    
                    echo $this->formLabel('link', 'Link naar object:');
                    echo $this->formText('link', item_uri('show',get_item_by_id($_GET['id'])), array('class'=>'textinput'));  ?>
                </div>
                    
                <?}?>

                <div class="field">
                    <?php 
                    // aangepast door Sam
                    echo $this->formLabel('message', 'Tip omtrent het gestolen object (verplicht): ');
                    echo $this->formTextarea('message', $message, array('class'=>'textinput')); ?>
                </div>    

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