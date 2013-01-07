<?php if (!$type): ?>
<p>You must choose a contribution type to continue.</p>
<?php
else:
if ($type->isFileRequired()):
    $required = true;
?>
<div class="field">
        <?php 
        // aangepast door Sam
        echo $this->formLabel('contributed_file', 'Voeg afbeelding toe'); ?>
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>
<?php
endif;
foreach ($type->getTypeElements() as $element) {
    echo $this->elementForm($element, $item);
}
if (!isset($required) && $type->isFileAllowed()):
?>
<div class="field">
        <?php echo $this->formLabel('contributed_file', 'Een bestand toevoegen (Optioneel)'); ?>
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>
<?php
endif;
// Allow other plugins to append to the form (pass the type to allow decisions
// on a type-by-type basis).
fire_plugin_hook('contribution_append_to_type_form', $type);
endif;