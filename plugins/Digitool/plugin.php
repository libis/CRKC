<?php
require_once 'DigitoolUrl.php';

// Add plugin hooks.
add_plugin_hook('install', 'digitool_install');
add_plugin_hook('uninstall', 'digitool_uninstall');
add_plugin_hook('admin_append_to_items_show_secondary', 'digitool_admin_show_item_map');

//add_plugin_hook('after_save_item', 'digitool_save_url');
add_plugin_hook('after_save_form_item', 'digitool_save_url');
add_filter('admin_items_form_tabs', 'digitool_item_form_tabs');

function digitool_install()
{
   $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS $db->DigitoolUrl (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `item_id` BIGINT UNSIGNED NOT NULL ,
    `pid` VARCHAR(100) NOT NULL ,
    INDEX (`item_id`)) ENGINE = MYISAM";
    $db->query($sql);
}

/**
 * Uninstall the plugin.
 */
function digitool_uninstall(){
    // Drop the url table.
	$db = get_db();
	$db->query("DROP TABLE $db->DigitoolUrl");
}

function digitool_admin_form($item){
	ob_start();
	echo js("jquery.pagination");
	?><link rel="stylesheet" href="<?php echo css('pagination'); ?>" /><?php
		//$html.="<form method='post' action=''>";
	if(digitool_item_has_digitool_url($item)){
	?>
	<div>
	<b>Digitool images currently associated with this item:</b><br>
	<br><?php echo digitool_get_thumb($item,false,true,'100px');?>
	<br><br><label>Remove current digitool images?</label><input type="checkbox" name="delete" value="yes"/>
	</div>
    <br><br>
    <?php }?>

    <label>Search digitool (case sensitive)</label>
	<br>
    <input name='fileUrl' id='fileUrl' type='text' class='fileinput' />
    <button style="float:none;" class="digi-search">Search</button>
    <br><br>
    <div id="wait" style="display:none;">Please wait, this might take a few seconds.</div>

    <div id="Pagination"></div>
    <br style="clear:both;" />
    <div id="Searchresult">
    This content will be replaced when pagination inits.
    </div>

    <!-- Container element for all the Elements that are to be paginated -->
    <div id="hiddenresult" style="display:none;">
     <div class="result">TEST</div>
    </div>


	<script>
	( function($) {
		jQuery('.digi-search').click(function(event) {
			event.preventDefault();
			jQuery('#Searchresult').hide('slow');
			jQuery('#Pagination').hide('slow');
			jQuery('#wait').show('slow');

			jQuery.get('<?php echo uri("digitool/index/cgi/");?>',{ search: jQuery('#fileUrl').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('#hiddenresult').html(data);
				initPagination();
				pageselectCallback(0);
				jQuery('#Pagination').show('slow');
				jQuery('#Searchresult').show('slow');
			});

		});

		jQuery('.digi-child').live("click", function(event) {
			event.preventDefault();
			jQuery('#wait').show('slow');
			jQuery.get('<?php echo uri("digitool/index/childcgi/");?>',{ child: jQuery('.digi-child').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('.result-child').html(data);
			});

		});

		// This demo shows how to paginate elements that were loaded via AJAX
		// It's very similar to the static demo.

		/**
		 * Callback function that displays the content.
		  *
		* Gets called every time the user clicks on a pagination link.
		*
		* @param {int}page_index New Page index
		* @param {jQuery} jq the container with the pagination links as a jQuery object
		*/
		function pageselectCallback(page_index, jq){
			var new_content = jQuery('#hiddenresult div.result:eq('+page_index+')').clone();
			jQuery('#Searchresult').empty().append(new_content);
		                return false;
		}

		/**
		* Callback function for the AJAX content loader.
		*/
		function initPagination() {
			var num_entries = jQuery('#hiddenresult div.result').length;
			// Create pagination element
			jQuery("#Pagination").pagination(num_entries, {
				num_edge_entries: 0,
				num_display_entries: 5,
				callback: pageselectCallback,
			                    items_per_page:4
			});
		}

	} ) ( jQuery );
		// Load HTML snippet with AJAX and insert it into the Hiddenresult element
		// When the HTML has loaded, call initPagination to paginate the elements

	</script>



	<?php
	$ht .= ob_get_contents();
	ob_end_clean();

	return $ht;

}

function digitool_save_url($item){

	//handle delete first
	if(isset($_POST['delete']) && ($_POST['delete'] == 'yes'))
	{
		$urlToDelete = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
		foreach($urlToDelete as $u){
			$u->delete();
		}

	}

	if(!$_POST['pid']){
		return;
	}

	$post = $_POST;

	//TODO:zie files-form.php voor code meerdere digitool files

	//create view url out of thumb url


	//save to db
	$url = new DigitoolUrl;
	$url->item_id = $item->id;

	$url->saveForm($post);
}



/**
* Add a Map tab to the edit item page
* @return array
**/
function digitool_item_form_tabs($tabs)
{
	// insert the map tab before the Miscellaneous tab
	$item = get_current_item();
	$ttabs = array();
	foreach($tabs as $key => $html) {
		if ($key == 'Tags') {
			$ht = '';
			$ht .= digitool_admin_form($item);
			$ttabs['Digitool'] = $ht;
		}
		$ttabs[$key] = $html;
	}
	$tabs = $ttabs;
	return $tabs;
}

/**
* Returns the html for loading the javascripts used by the plugin.
*
* @param bool $pageLoaded Whether or not the page is already loaded.
* If this function is used with AJAX, this parameter may need to be set to true.
* @return string
*/
function digitool_scripts()
{
	$ht = '';
	$ht .= js('jquery.pagination');
	return $ht;
}

/**
* Shows the digitool urls on the admin show page in the secondary column
* @param Item $item
* @return void
**/
function digitool_admin_show_item_map($item)
{
	$html = digitool_scripts()
	. '<div class="info-panel">'
	. '<h2>Digitool</h2>'
	. digitool_get_thumb($item,false,true,'100px')
	. '<br><br></div>';
	echo $html;
}


/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb($item, $findOnlyOne = false, $linkToView = false,$width="",$class="",$alt=""){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);
	//print_r($url);

	if(!empty($url)){

		if(!$linkToView){
			if($findOnlyOne){
				$thumb = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$url->pid;

				return "<img src='".$thumb."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
			}
			//if more then one thumbnail was found
			else{
				$i=0;

				foreach($url as $u){

					$thumb = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$u->pid;
					if($i==0)
						$html.="<img src='".$thumb."' class='first' width='".$width."' /> ";
					else
						$html.="<img src='".$thumb."' width='".$width."' /> ";

					$i++;
				}
				return $html;
			}
		}else{
			if($findOnlyOne){
				$thumb = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$url->pid;
				$view = "http://resolver.lias.be/get_pid?stream&usagetype=VIEW_MAIN,VIEW&pid=".$u->pid;

				return "<a href='".$view."' target='_blank'><img src='".$thumb."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'></a>";
			}
			//if more then one thumbnail was found
			else{

				foreach($url as $u){

					$thumb = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$u->pid;
					$view = "http://resolver.lias.be/get_pid?stream&usagetype=VIEW_MAIN,VIEW&pid=".$u->pid;

					$html.="<a href='".$view."' target='_blank'><img src='".$thumb."' width='".$width."'/></a> ";
				}
				return $html;
			}

		}
	}

}

function digitool_get_thumb_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);
	//print_r($url);

	if(!empty($url)){
		$thumb = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$url->pid;
		return $thumb;
	}else{
		return false;
	}

}


/**
* Shows an item's digitool url views
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the views
**/
function digitool_get_view($item, $findOnlyOne = false,$width="",$height=""){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
		if($findOnlyOne){
			$view = "http://resolver.lias.be/get_pid?stream&usagetype=VIEW_MAIN,VIEW&pid=".$url->pid;
			return "<img src='".$view."' width=".$width." height=".$height.">";
		}
		//if more then one thumbnail was found
		else{
			foreach($url as $u){
				$view = "http://resolver.lias.be/get_pid?stream&usagetype=VIEW_MAIN,VIEW&pid=".$u->pid;
				$html.="<img src='".$view."' width=".$width." height=".$height."/>";
			}
			return $html;
		}
	}
	else{
		return "<p>There are no digitool images associated with this item.</p>";
	}

}

/**
* Checks if item has a digitool url
* @param Item $item
* @return true or false
**/
function digitool_item_has_digitool_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
		return true;

	}
	else{
		return false;
	}

}

/**
* Returns a digitool thumbnail
* @param Item $item, boolean $fileFirst, int $size
* $fileFirst indicates which type of thumbnail will be returned, $size will set the width of the image
* @return true or false
**/
function digitool_thumbnail($item,$fileFirst = true, $size = "150",$class="",$alt=""){
	//show the thumbnail of a file object if present
	if($fileFirst && item_has_thumbnail($item)){
		//return the thumbnail (default size as in omeka settings)
		return item_square_thumbnail(array("class" => $class, "alt" => $alt, "width" => $size),"",$item);
	}
	//show the digitool url if there is one
	if(digitool_item_has_digitool_url($item))
		return digitool_get_thumb($item, true,false, $size," ", $class, $alt);

	//return false if there are no thumbnails
	return false;
}

function digitool_simple_gallery($item){
	$i=0;
        $thumbs = array();
	$urls = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
        foreach($urls as $url):
            $thumbs[] = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$url->pid;
        endforeach;
        
        $files = $item->Files;
        foreach($files as $file):
            $thumbs[] = WEB_THUMBNAILS."/".$file->archive_filename;
        endforeach;
        
        foreach($thumbs as $thumb):
            if($i==0){
                $html.="<div id='image'><img src='".$thumb."' /></div>";
            }
            $width = 100/sizeof($url);
            $width = 50;
            $html.= "<a href='#' rel='".$thumb."' class='image'><img src='".$thumb."' class='thumb' width='".$width."' height='60' border='0'/></a>";
            $i++;
        endforeach;        
	
	?>

	<script>
	jQuery(document).ready(function() {

		jQuery(function() {
			jQuery(".image").click(function() {
				var image = jQuery(this).attr("rel");
				jQuery('#image').hide();
				jQuery('#image').fadeIn('slow');
				jQuery('#image').html('<img src="' + image + '"/>');
				return false;
			});
		});
	});
	</script>

	<?php
	return $html;
}

function digitool_simple_gallery_array($items){
	$i=0;
	$html="";

	/*set_items_for_loop($items);
	while(loop_items()):
	echo item('Dublin Core', 'Title');
	endwhile;*/

	if(sizeof($items)==1){
		if(digitool_item_has_digitool_url($items)){
			$html.="<div id='image'><img src='".digitool_get_thumb_url($items[0])."' width=200 height=100% /></div>";
			//$html.= digitool_get_view(get_current_item(), false,150);
			return $html;
		}else{
			return false;
		}
	}else{
		foreach($items as $item){
			if(digitool_thumbnail($item)){
				if($i==0){
					$html.="<div id='image'><a href=".item_uri('show',$item)."><img src='".digitool_get_thumb_url($item)."' width='200px' height=100% /></a></div>";;

				}
				// width toegevoegd door Sam
				$width = 50;
				$link = item_uri('show',$item);
				$html.= "<a href='#' name='".$link."' rel='".digitool_get_thumb_url($item)."' class='image'><img src='".digitool_get_thumb_url($item)."' class='thumb' width='" . $width ."' height='50' border='0'/></a>";

				$i++;
			}
		}

	}

	?>
	<script>
	jQuery(document).ready(function() {

		jQuery(function() {
			jQuery(".image").click(function() {
				var image = jQuery(this).attr("rel");
				var url = jQuery(this).attr("name");
				jQuery('#image').hide();
				jQuery('#image').fadeIn('slow');
				jQuery('#image').html('<a href="'+ url +'"><img width=200 src="' + image + '"/></a>');
				return false;
			});
		});
	});
	</script>
	<?php

	return $html;
}

?>
