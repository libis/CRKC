<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

$head = array('title' => 'Contribute',
              'bodyclass' => 'contribution');
head($head); ?>
<?php
	echo js('contribution-public-form');
	//get item by id
	if($_GET['id']){
		$item = get_item_by_id($_GET['id']);
	}
?>
<script type="text/javascript">
// <![CDATA[
enableContributionAjaxForm(<?php echo js_escape(uri('contribution/type-form')); ?>);
// ]]>

jQuery(document).ready(function($){
	$('#Elements-50-0-text').val('<?php echo item('Dublin Core','Title',array(),$item);?>');
	$('#Elements-41-0-text').val('<?php echo item('Dublin Core','Description',array(),$item);?>');
	$('#Elements-39-0-text').val('<?php echo item('Dublin Core','Creator',array(),$item);?>');
	$('#Elements-51-0-text').val('<?php echo item('Dublin Core','Type',array("delimiter"=>", "),$item);?>');
	$('#Elements-38-0-text').val('<?php echo item('Dublin Core','Extent',array("delimiter"=>", "),$item);?>');
	$('#Elements-46-0-text').val('<?php echo item('Dublin Core','Temporal Coverage',array("delimiter"=>", "),$item);?>');

});

</script>

<?php

?>

<div id="primary">
	<?php echo Libis_crkc_gray_box();?>
	<?php $tags = get_tags(array('sort'=>'most'),30); echo tag_cloud($tags,uri('items/browse'));  ?>
</div>
<div id="secondary">

    <div class="inhoud">
    <h2>Aangifte diefstal kunstvoorwerpen</h2>
    <?php echo get_option('contribution_consent_text');?>
    <br>
    <?php echo flash(); ?>
    <form method="post" action="" enctype="multipart/form-data">
		<fieldset id="contribution-contributor-metadata" <?php if (!isset($typeForm)) { echo 'style="display: none;"'; }?>>
            <h5>Persoonlijke informatie</h5>
            <div class="field">
                <label for="contributor-name">Naam</label>
                <div class="inputs">
                    <div class="input">
                        <?php echo $this->formText('contributor-name', $_POST['contributor-name'], array('class' => 'textinput')); ?>
                    </div>
                </div>
            </div>
            <div class="field">
                <label for="contributor-email">E-mailadres</label>
                <div class="inputs">
                    <div class="input">
                        <?php echo $this->formText('contributor-email', $_POST['contributor-email'], array('class' => 'textinput')); ?>
                    </div>
                </div>
            </div>
        <?php
        foreach (contribution_get_contributor_fields() as $field) {
            echo $field;
        }
        ?>
        </fieldset>
        <hr>
        <fieldset id="contribution-item-metadata">
			<h5> Beschrijving van het gestolen object</h5>
            <div class="inputs">
                <label for="contribution-type"></label>
                <?php echo contribution_select_type(array( 'name' => 'contribution_type', 'id' => 'contribution-type'), $_POST['contribution_type']); ?>
                <input type="submit" name="submit-type" id="submit-type" value="Select" />
            </div>

            <!-- THE ACTUAL FORM FOR THE OBJECT -->
            <?php $_POST['contribution_type'] = "Object"; ?>
            <div id="contribution-type-form">
            <?php if (isset($typeForm)): echo $typeForm; endif; ?>
            </div>
            <!-- END FORM -->

        </fieldset>

        <fieldset id="contribution-confirm-submit" <?php if (!isset($typeForm)) { echo 'style="display: none;"'; }?>>
            <div id="captcha" class="inputs"><?php echo $captchaScript; ?></div>
            <input type="hidden" name="contribution-public" value="0" />

            <p>Je moet akkoord gaan met onze <a href="<?php echo uri('contribution/terms') ?>" target="_blank">voorwaarden</a>.</p>
            <div class="inputs">
                <?php echo $this->formCheckbox('terms-agree', $_POST['terms-agree'], null, array('1', '0')); ?>
                <?php echo $this->formLabel('terms-agree', 'Ik ga akkoord met de voorwaarden.'); ?>
            </div>
            <?php 
            // Aangepast door Sam. de wijzgingen hier worden niet getoond
            echo $this->formSubmit('form-submit', 'Doe aangifte', array('class' => 'submitinput')); ?>
        </fieldset>
    </form>
    </div>
</div>
<?php foot();
