<?php if (!$type): ?>
<p>You must choose a contribution type to continue.</p>
<?php 
else:
if ($type->isFileRequired()):
    $required = true;
?>
<div class="field">
        <?php 
        // aangepast door Sam, de aanpaasingen voor dit onderdeel in de plugin dir worden niet gebruikt.
        echo $this->formLabel('contributed_file', 'Voeg bestand toe'); ?>
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
        <?php 
        // aangepast door Sam, de aanpaasingen voor dit onderdeel in de plugin dir worden niet gebruikt.
        echo $this->formLabel('contributed_file', 'Voeg bestand toe (Optioneel)'); ?>
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>
<?php 
endif;
// Allow other plugins to append to the form (pass the type to allow decisions
// on a type-by-type basis).
fire_plugin_hook('contribution_append_to_type_form', $type);
endif;