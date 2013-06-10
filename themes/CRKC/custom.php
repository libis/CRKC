<?php

/**
* Custom function to retrieve any number of random featured items.
*
* @param int $num The number of random featured items to return
* @param boolean $withImage Whether to return items with derivative images. True by default.
* @return array An array of Omeka Item objects.
*/
function Libis_get_random_featured_items($num = '10', $withImage = true)
{
    // Get the database.
    $db = get_db();

    // Get the Item table.
    $table = $db->getTable('Item');

    // Build the select query.
    $select = $table->getSelect();
    $select->from(array(), 'RAND() as rand');
    $select->where('i.featured = 1');
    $select->order('rand DESC');
    $select->limit($num);

    // If we only want items with derivative image files, join the File table.
    if ($withImage) {
        $select->joinLeft(array('f'=>"$db->DigitoolUrls"), 'f.item_id = i.id', array());
        //$select->where('f.has_derivative_image = 1');
    }

    // Fetch some items with our select.
    $items = $table->fetchObjects($select);

    return $items;
}

 /**
  * Returns the HTML markup for displaying a random featured item.  Most commonly
  * used on the home page of public themes.
  *
  * @since 0.10
  * @param boolean $withImage Whether or not the featured item should have an image associated
  * with it.  If set to true, this will either display a clickable square thumbnail
  * for an item, or it will display "You have no featured items." if there are
  * none with images.
  * @return string HTML
  **/
function Libis_display_random_featured_item($withImage=false)
 {
    $featuredItem = random_featured_item($withImage);
 	$html = '<h2>Object in de kijker</h2>';
 	if ($featuredItem) {
 	    $itemTitle = item('Dublin Core', 'Title', array(), $featuredItem);

 	   //$html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $featuredItem) . '</h3>';
 	   if (item_has_thumbnail($featuredItem)) {
 	       $html .= link_to_item(item_square_thumbnail(array(), 0, $featuredItem), array('class'=>'image'), 'show', $featuredItem);
 	   }
 	   $html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $featuredItem) . '</h3>';

 	   // Grab the 1st Dublin Core description field (first 150 characters)
 	   if ($itemDescription = item('Dublin Core', 'Description', array('snippet'=>150), $featuredItem)) {
 	       $html .= '<p class="item-description">' . $itemDescription . '</p>';
       }
 	} else {
 	   $html .= '<p>No featured items are available.</p>';
 	}

     return $html;
 }

 /**
 * Returns the HTML of a random featured exhibit
 *
 * @return exhibit info in HTML
 **/
function Libis_display_random_featured_exhibit()
{
    $html = '<div id="featured-exhibit">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    $html .= '<h2>Verhaal in de kijker</h2>';
    if ($featuredExhibit) {
       $html .= '<h3>' . exhibit_builder_link_to_exhibit($featuredExhibit) . '</h3>'."\n";
       $html .= '<p>'.snippet_by_word_count(exhibit('description', array(), $featuredExhibit)).'</p>';
    } else {
       $html .= '<p>You have no featured exhibits.</p>';
    }
    $html .= '</div>';
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}

/**
* Custom function to retrieve a list of exhibits with a given tag
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @param $tag, the tag used to filter between exhibits
* @return html formatting (with images) of the list
*/
function Libis_get_exhibits($tag)
{
	$exhibits = exhibit_builder_get_exhibits(array('tags' =>$tag));
	//if there were no exhibits found
	if(empty($exhibits)){
		return "<p>We're sorry but there were no stories found with this tag</p>";
	}
	//tag 'algemeen' has different formatting then the others
	if($tag=="algemeen"){
		$html.= '<table class="exhibit_general_list"><tr>';

		foreach($exhibits as $exhibit) {

			$html.= '<td>';
			//set current exhibit
		    exhibit_builder_set_current_exhibit($exhibit);

		    if($exhibit->thumbnail){
				//$item = get_item_by_id($exhibit->thumbnail);
				//set_current_item($item);
				//$html.= digitool_thumbnail($item,true,100,"",item('Dublin Core', 'Title'));
				$html.= exhibit_builder_link_to_exhibit($exhibit,"<img width='150' src='".img($exhibit->thumbnail,"images/verhalen_thumbs")."'>");

		    	//$html.= (item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'))));
		    }
		    //takes care of the link and text
		    $html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '</td>';

		}

		$html.= '</tr></table>';
		return $html;
	}
	else{
		$html.= '<ul class="exhibit_tag_list">';

		foreach($exhibits as $exhibit) {

			$html.= '<li>';
			//set current exhibit
		    exhibit_builder_set_current_exhibit($exhibit);

		    if($exhibit->thumbnail){
		    	//$item = get_item_by_id($exhibit->thumbnail);
				//set_current_item($item);
		   		//$html.= (item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'))));
		    	$html.= exhibit_builder_link_to_exhibit($exhibit,"<img width='200'src='".img($exhibit->thumbnail,"images/verhalen_thumbs")."'>");
		    }
		    //takes care of the link and text
		    $html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '<p>'.snippet_by_word_count(exhibit('description', array(), $exhibit),40).'</p>';

		    $html.= '</li>';

		}
		$html.= '</ul>';
		return $html;
	}
}
/**
* Custom function to retrieve a thumb of an exhibit
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function Libis_get_exhibit_thumb($exhibit,$props=array()){

	if($exhibit->thumbnail){

		//$item = get_item_by_id($exhibit->thumbnail);
		//set_current_item($item);
		$html= '<img class="carousel-image" width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'">';

	    //$html= item_square_thumbnail($props);
	   // $html = digitool_get_thumb($item,true,false,$props["width"],$props["class"],item('Dublin Core', 'Title'));
		//$html= digitool_thumbnail($item,true,$props["width"],$props["class"],item('Dublin Core', 'Title',"",$item));

	}else{

		$html=false;
	}

	return $html;
}

/**
* Custom function to retrieve a thumb of an exhibit section
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function Libis_get_section_thumb($section){

	if($section->thumbnail){
		$item = get_item_by_id($section->thumbnail);

		$html.= digitool_thumbnail($item,true,"100","",item('Dublin Core', 'Title',"",$item));

		//set_current_item($item);
	    //$html= (item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'))));

	}else{
		$html=false;
	}

	return $html;

}

/**
* Custom function to retrieve the left navigation (simple pages)
*
* @return html formatting of the three
*/
function Libis_get_simple_pages_nav($parentId = 0, $currentDepth = null, $sort = 'order', $requiresIsPublished = true)
{
	$html = '';

	$currentPage = get_current_simple_page();
	$ancestorPage = simple_pages_earliest_ancestor_page($currentPage->id);

	//gets all toplevel pages
	$pages = get_db()->getTable('SimplePagesPage')->findBy(array('parent_id' => 0, 'sort' => $sort));
	set_simple_pages_for_loop($pages);
	$html .= "<ul class='first'>";
	//loop through all toplevel pages
	while (loop_simple_pages()):
    	//$html .="<li><a href='".uri(simple_page('slug'))."'>".simple_page('title')."</a></li>";
    	//if menu item equal or is a child of current page display children
    	if(simple_page('id') == $currentPage->id || simple_page('id') == $ancestorPage->id){
    		//get links to child pages
    		$childPageLinks = simple_pages_get_links_for_children_pages($ancestorPage->id, null, $sort, $requiresIsPublished, $requiresIsAddToPublicNav);
			//contruct a nav menu
    		//$html .= "<ul class='second'>";
        	$html .= nav($childPageLinks, $currentDepth);
        	//$html .= "</ul>";

    	}
    endwhile;

    $html .="</ul>";

    simple_pages_set_current_page($currentPage);

    if($html=="<ul class='first'></ul>")
    	return false;
    else
    	return $html;
}

 /**
 * Returns the HTML of a carousel of items, exhibits or themes
 *
 * @param $type string, "exhibit", "items" or "themes", default "items"
 * @return exhibit info in HTML
 **/
function Libis_display_carousel($type, $description)
{
	if($type == 'item'){
		$html.="<div id='item-block'>
	        <h2>Recente objecten</h2>
	        <table><tr><td width='300'>";


	    set_items_for_loop($items = recent_items('10'));

		if (has_items_for_loop()):

	    	$html.="<div id='carousel-container'>
	      <div id='carousel'>";
	        while (loop_items()):

				if (item_has_thumbnail()):
					$html.="<div class='carousel-feature'>";
					$html.= link_to_item(item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'),'class'=>'thumbnail carousel-image')));
					$html.="</div>";
		        endif;

	      	endwhile;
	      	$html.="</div></div>";
		endif;
	}
	if($type == 'exhibit'){
		$html="<div id='item-block'>
	        <h2>Recente verhalen</h2>
	        <table><tr><td width='300'>";

	    $exhibits = exhibit_builder_recent_exhibits(10);
		$html.="<div id='carousel-container'>
	      <div id='carousel'>";
	    foreach($exhibits as $exhibit) {




				if (Libis_get_exhibit_thumb($exhibit)):
					$html.="<div class='carousel-feature'>";
					$html.= exhibit_builder_link_to_exhibit($exhibit,LIBIS_get_exhibit_thumb($exhibit,array('alt'=>item('Dublin Core', 'Title'),'class'=>'thumbnail carousel-image')));

					$html.="</div>";
		        endif;



		}
		$html.="</div></div>";
	}
	$html.="</td><td><p>";
	$html.=$description;
	$html.="</p></td></tr></table></div>";

	return $html;
}

//translated version of the select function (helpers/FormFunctions.php
function Libis_select($attributes, $values = null, $default = null, $label=null, $labelAttributes = array())
{
    $html = '';
    //First option is always the "Select Below" empty entry
    $values = (array) $values;
    $values = array('' => 'Selecteer') + $values;
    //Duplication
	if ($label) {
	    $labelAttributes['for'] = $attributes['name'];
	    $html .= __v()->formLabel($attributes['name'], $label, $labelAttributes);
	}
    $html .= __v()->formSelect($attributes['name'], $default, $attributes, $values);
    return $html;
}

//temp function to convert the pids table to digitool_url objects
/* Custom function to link items to their digitool id's */
function Libis_set_images(){
	set_time_limit(400);

	//get items with a tempid
	$db = get_db();
	$select = $db->query("
		SELECT i.id, etx.text
		FROM crkc_items i
		LEFT JOIN crkc_element_texts etx ON etx.record_id = i.id
		LEFT JOIN crkc_record_types rty ON etx.record_type_id = rty.id
		WHERE etx.text IS NOT NULL
		AND rty.name = 'Item'
		AND etx.element_id =141
		ORDER BY `i`.`id` ASC
		");

	$items = $select->fetchAll();

	//loop trough the items and compare with tempid_pid table
	$i=0;
	foreach($items as $item){
		//get pid from db
		$select = $db->query("SELECT pid FROM crkc_tempid_pid WHERE tempid = '".$item['text']."'");

		$pid = $select->fetchAll();
		$pidNew =array();
		//merge all arrays into one
		foreach($pid as $p){
			$pidNew[] = $p['pid'];
		}
		//chuck out the doubles
		$pidNew = array_unique($pidNew);

		//and throw the rest in the DB
		echo $item['id']." - ";
		foreach($pidNew as $p){
			echo $p.", ";
			$bind = array(
							  'item_id'=>$item['id'],
							  'pid'=> $p
			);

			$db->insert('digitool_urls', $bind);
		}
		echo " OK<br>";echo "<br>";

		//WAT MET DUBBELS?
		//vb. items 3798, 75266, 51906

		//save as digitool obj
		//if(!empty($pid)){
		/*

		*/
		//}
		$i++;

	}
	return $i;

}

function Libis_remove_images(){
	set_time_limit(400);
	//get items with digitool urls in type afbeelding
	/*$elementId = get_db()->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'digitool-update10')->id;
	$params = array('advanced_search' =>
	array(
	array('element_id' => $elementId,
				'type' => 'is not empty'

	)
	)
	);*/

	$db = get_db();
	$select = $db->query("SELECT item_id as id FROM omeka_items_section_pages");

	$ids = $select->fetchAll();


	//echo(sizeof($items));
	$i=0;
	foreach($ids as $id){


		if(get_item_by_id($id['id'])==null){
			$i++;

			//where = $db->quoteInto('item_id = ?', $id);
			//$db->delete('omeka_digitool_urls', $where);

		}
		//check if item has digitool has digitool ids


	}
	return $i;


}

//temp function to convert the pids table to digitool_url objects
function Libis_set_page_images(){
	set_time_limit(400);

	$db = get_db();
	$select = $db->query("SELECT * FROM omeka_items_section_pages");

	$pages = $select->fetchAll();

	//return(sizeof($items));
	$i=0;
	foreach($pages as $page){
		$i++;
		//check if item has digitool has digitool ids

		//get pid from db

		$select = $db->query("SELECT item_id FROM omeka_digitool_urls WHERE pid = '".$page['item_id']."'");

		$itemid = $select->fetch();

		//save as digitool obj
		if(!empty($itemid)){
			//update
			$db->update('omeka_items_section_pages', array('item_id'=>$itemid['item_id']), "page_id =".$page['page_id']);

			echo $i."   ".$itemid['item_id']."<br>";
		}



	}

	//return $i."   ".$itemid['item_id']."<br>";

}

function Libis_get_featured_items_and_exhibits($length=4){
	$items = array();
	// aangepast door Sam ,"type" => "16"
	$items = get_items(array("featured" => true,'recent' => true),$length);

	//print_r($items);die;//TODO:welk type?
	$exhibits = exhibit_builder_recent_exhibits($length);
	$recents = array();

	//get items and exhibits in one array
	for($i=0;$i<=$length;$i++):
		if($items[$i])
			$recents[]=$items[$i];
		if($exhibits[$i])
			$recents[]=$exhibits[$i];
	endfor;
	//var_dump($recents);
	//slice off the needed amount(=$length)
	$recents = array_slice($recents,0,$length);

	$i=0;$html="";
	foreach($recents as $recent){
		//check to see if its the first one
		/*if($i==0)
			$html.="<div class='titel hoofdstuk'>";
		else*/
			$html.="<div class='hoofdstuk'>";

		//check if Item or Exhibit
		if(get_class($recent)=="Item"){
			if (digitool_thumbnail($recent)){
				$html .="<div class='item-img'>".
					digitool_thumbnail($recent).
			        "</div>";
			}

			$html .="<h5>".link_to_item(item('Dublin Core','Title',array(),$recent), array(),'show',$recent)."</h5>";
			$html .="<p>".item('Dublin Core','Description',array('snippet'=>'200'),$recent)."</p>";
			$html .="<p>".link_to_item('Lees meer',array(),'show',$recent)."</p>";
		}
		if(get_class($recent)=="Exhibit"){
			if(Libis_get_first_item_exhibit($recent)):
				$item = Libis_get_first_item_exhibit($recent);
				$html .="<div class='item-img'>";

				$html .= digitool_get_thumb($item,true,false,200);
				$html .= "</div>";

			endif;
			$html .="<h5>".exhibit_builder_link_to_exhibit($recent)."</h5>";
			$html .="<p>".snippet(exhibit("description", array(), $recent),0,300);
			$html .="<p>".exhibit_builder_link_to_exhibit($recent,'Lees meer')."</p>";
		}

		$html.="</div>";
		$i++;
	}

	return $html;

}


//om korte stukken tekst te maken
function Libis_excerpt ($string, $length=10)
{
	$string = strip_tags($string);

	if (strlen($string) > $length) {

	    // truncate string
	    $stringCut = substr($string, 0, $length);

	    // make sure it ends in a word so assassinate doesn't become ass...
	    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
	}
	return $string;

}


/**
* Custom function to retrieve the Agenda.
* @return The html for the 3 most recent items of item type Agendapunt.
*/
function Libis_get_actualiteit($numberOfActualiteit=3,$page="actualiteit"){
	//get recentste openbare agendapunten
	$Aitems = get_items(array("recent" => true,"type" => "8"),$numberOfActualiteit);
	//$Nitems = get_items(array("recent" => true,"type" => "17"),$numberOfActualiteit);

	//NIEUWS
	if($page=="actualiteit"){
 		echo feedCollector_show('actua-nieuws',0);
 		//AGENDA
 		echo "<div class='actua-agenda'><h2>Agenda</h2>";
	}
	if($page=="index"){
		echo "<div class='feed-box'><h2>Agenda</h2>";
	}

	if($Aitems){
            //sort
            usort($Aitems, function($a, $b) {
               return strtotime(item('Item Type Metadata','Startdatum',array(),$a)) - strtotime(item('Item Type Metadata','Startdatum',array(),$b));
            });

            set_items_for_loop($Aitems);
            while(loop_items()){

                echo "<div class='news'>";                   
                    if(item('Dublin Core','Title') != ""){//niet posten zonder titel
                            echo "<h6>".link_to_item(item('Dublin Core', 'Title'))."</h6>";
                             if(item_has_thumbnail()){
                                //return the thumbnail
                                echo item_square_thumbnail(array("class"=>'inline'));
                            }
                            if(item('Item Type Metadata','Startdatum')!=""){
                                if(item('Item Type Metadata','Einddatum') != ""){
                                    echo "<span class='agenda-datum'>Van ".item('Item Type Metadata', 'Startdatum')." </span>";
                                }else{
                                    echo "<span class='agenda-datum'>".item('Item Type Metadata', 'Startdatum')." </span>";
                                }
                            }    
                            if(item('Item Type Metadata','Einddatum') != "")
                                echo "<span class='agenda-datum'>tot ".item('Item Type Metadata', 'Einddatum')."</span>";

                            echo "<p>".Libis_excerpt(item('Dublin Core', 'Description'),50,"...")."</p>";
                            echo "<p>".link_to_item("Lees meer")."</p>";
                    }
                echo "</div>";
            }
       }else{
            echo "<p>Er werden geen agendapunten gevonden.</p>";
       }
       echo"<p><a href='/items/browse?type=Agendapunt'>Lees de volledige agenda</a></p></div>";
}



function Libis_loep_query($title){
	//zoek op titel
	//$elementId = get_db()->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Title')->id;
	/*$params = array('advanced_search' =>
              array(
                  array('element_id' => $elementId,
                        'type' => 'contains',
                        'terms' => $title
                  )
              ), 'type' => '16'
          );*/

	//$items = get_items($params,20);
	$itemsTag = get_items(array('tags' => $title), 20);
	$count = count($itemsTag);


	/*$i=0;
	while($count <20 && !empty($itemsTag[$i])){
		$double = false;
		foreach($items as $item){
			if($item->id == $itemsTag[$i]->id)
				$double = true;
				//echo $item->id." - ".$itemsTag[$i]->id."<br>";
		}
		if(!$double){
			$items[] = $itemsTag[$i];
			//echo $itemsTag[$i]->id."<br>";
		}
		$count++;
		$i++;
	}*/

	if(empty($itemsTag))
		return false;
	else
		return "<div class='show-img'>".digitool_simple_gallery_array($itemsTag)."</div>";

}


function Libis_simplePageMaker(){

	/*QUERY=
	 *
	 * INSERT INTO `omeka_crkc`.`crkc_simple_pages_pages` (
		`id` ,
		`modified_by_user_id` ,
		`created_by_user_id` ,
		`is_published` ,
		`add_to_public_nav` ,
		`title` ,
		`slug` ,
		`text` ,
		`updated` ,
		`inserted` ,
		`order` ,
		`parent_id` ,
		`template` ,
		`use_tiny_mce`
		)
		VALUES (
		NULL , '1', '1', '0', '0', 'TestTitel', 'TestSlug', 'TestText', NOW( ) , '0000-00-00 00:00:00', '0', '0', '', '1'
		);
	 *
	 */

	$db = get_db();

	//get items onder de loep en loop
	$items = get_items(array("type" => "15"),100);
	foreach($items as $item){

		set_current_item($item);
		//get titel
		$titel = item('Dublin Core','Title');
		//get text
		$text = item('Item Type Metadata','SimplePage text');
		//genereer slug uit titel
		$slug = str_replace(' ', '_', $titel);
		$slug = strtolower($slug);

		//insert
		$bind = array(
			'id'=> null,
			'modified_by_user_id' =>1,
			'created_by_user_id' =>1,
			'is_published' =>1,
			'add_to_public_nav' =>0,
			'title' => $titel,
			'slug' => $slug,
			'text' => $text,
			'inserted' => new Zend_Db_Expr('CURDATE()'),
			'order' =>0,
			'parent_id' =>0,
			'use_tiny_mce' => 1
		);

		var_dump($bind);echo"<br><br>";
		$db->insert('simple_pages_pages', $bind);
	}
	//einde loop en einde script

}

function Libis_crkc_slideshow(){?>
	<div class="slides_container">
	<?php
	//get simplepage titles (public?)
	$pages = get_db()->getTable('SimplePagesPage')->findAll();
	//get images
	$directory = dir("themes/CRKC/images/erfgoed_onder_de_loep");

	while($image=$directory->read())
	{
		$title = substr($image,0,-4);
		set_simple_pages_for_loop($pages);
		while (loop_simple_pages()):
			if($title == simple_page('slug')):
			echo "<div class='slide'><img src='".img("erfgoed_onder_de_loep/".$image)."' alt='' />";
			?>
		   		<div class="loep">
		   			<img width="180" src="<?php echo img("loep2.png");?>">
		   			<div class="loep-titel"><a href="<?php echo uri(simple_page('slug')); ?>"><?php echo simple_page('title'); ?></a>

		   				</div>
		   			</div>
		   		</div>
			<?php
			endif;
		endwhile;
	}

	$directory->close();
	?>
	</div>
	<script>
		jQuery('document').ready(function($){
			// Set starting slide to 1
			var startSlide = 1;
			// Get slide number if it exists
			if (window.location.hash) {
				startSlide = window.location.hash.replace('#','');
			}
			jQuery('#slides').slides({
				preload: true,
				preloadImage: 'themes/CRKC/images/loading.gif',
				generatePagination: true,
				play: 5000,
				pause: 2500,
				hoverPause: true,
				// Get the starting slide
				start: startSlide,
				animationComplete: function(current){
					// Set the slide number as a hash
					window.location.hash = '#' + current;
				}
			});
		});
	</script><?php
}

function Libis_crkc_gray_box(){
	?>
	<div class="gray-box">
		<img title="Ontdek het religieus erfgoed" alt="Ontdek het religieus erfgoed" src="<?php echo img('ontdek-titel-zwart.png');?>" ></img>

		<?php echo simple_search("Zoek",array('id' => 'left-search'),uri('items/browse/?type=Erfgoed object')); ?>
    	<div id="box-nav"><a href="/zoeken-meerinfo/">Meer info</a> / <a href="/zoeken-zoektips/">Zoektips</a> / <a href="<?php echo uri('items/advanced-search/?type=Erfgoed object'); ?>">Uitgebreid zoeken</a>
    </div>
	</div>

	<?php
}

function Libis_link_to_related_exhibits($item) {
	require_once "Exhibit.php";
	$db = get_db();

	$select = "
	SELECT DISTINCT e.id,s.id,sp.* FROM {$db->prefix}exhibits e
	INNER JOIN {$db->prefix}sections s ON s.exhibit_id = e.id
	INNER JOIN {$db->prefix}section_pages sp on sp.section_id = s.id
	INNER JOIN {$db->prefix}items_section_pages isp ON isp.page_id = sp.id
	WHERE isp.item_id = ?";

	$pages = $db->getTable("ExhibitPage")->fetchObjects($select,array($item));
                    //var_dump($exhibits);die();
	if(!empty($pages)) {
            echo '<h3>In volgende tentoonstellingen</h3>';
            $html='';
            foreach($pages as $page) {
                $section = exhibit_builder_get_exhibit_section_by_id($page->section_id);
                $exhibit = exhibit_builder_get_exhibit_by_id($section->exhibit_id);
                if($exhibit->public==1){
                    $html .= "<li>".$exhibit->title.':<br>';                    
                    $html .= '<a href="'.exhibit_builder_exhibit_uri($exhibit,$section,$page).'">'.$page->title.'</a></li>';
                }          
            }
            if($html!=''){
                echo "<ul class='pagestable'>";
                echo $html;
                echo "</ul>";
            }    
	}
}

/*this function helps importing extra metadata for existing items
 * Carefull!
 */
function Libis_import_element_for_item($itemID,$elementID,$text){
	set_time_limit(400);

	//get items with a tempid
	$db = get_db();

	$bind = array(
				'record_id'=>$itemID,
				'record_type_id'=> 2,
				'element_id'=> $elementID,
				'html' => 0,
				'text' => $text
		);
	//uncomment this line to turn this function ON
	//$db->insert('element_texts', $bind);

}
/*
 * Function was used to import extra metadata, probably wise not to use it again
 *
 * Use the query below to check for doubles in the element text table (now set for title)
 * SELECT *
FROM `crkc_element_texts`
WHERE `element_id` =50
GROUP BY `record_id`
HAVING (
count( `record_id` ) >1
)
 */
function Libis_import_extra_crkc(){

	//open file
	//uncomment the line below and fill in the corret filename
	//$f = "themes/CRKC/docs/";
	$file = fopen($f, 'r');

	$Espatial = "129";
	$Ecreator = "37";
	$Eindentifier = "42";
	$Edescription = "41";
	$Etitel = "50";

	$i= 0;
	//loop through records
	while(($rijData = fgetcsv($file, 5000,'	',";"))){
		//put field into array

			echo "<pre>";
			print_r($rijData);
			echo "</pre>";

		$itemID = $rijData[0];

		$spatial = $rijData[4];
		if($spatial)
			Libis_import_element_for_item($itemID,$Espatial,$spatial);

		$creator = $rijData[6];
		if($creator)
			Libis_import_element_for_item($itemID,$Ecreator,$creator);

		$indentifier = "RKD: ".$rijData[3];
		if($indetifier)
			Libis_import_element_for_item($itemID,$Eindentifier,$indentifier);

		$description = $rijData[2];
		if($description)
			Libis_import_element_for_item($itemID,$Edescription,$description);

		$titel = $rijData[5];
		if($titel && item('Dublin Core','Title',array(),get_item_by_id($itemID)))
			Libis_import_element_for_item($itemID,$Etitel,$titel);
		$i++;

	}

	fclose($file);
	return $i;

}

function Libis_get_first_item_exhibit($exhibit){
	$itemcount=0;
	$page="";
	$section = $exhibit->getFirstSection();
	if(!empty($section)){
		$page= $exhibit->getFirstSection()->getPageByOrder(1);
		$itemcount = count($page['ExhibitPageEntry']);

		$itempageobject = $page['ExhibitPageEntry'];
		$found=false;
		if($itemcount>0){
			for ($i=1; $i <= $itemcount; $i++) {
				$item = $itempageobject[$i]['Item'];
				if(!empty($item)){
					if (digitool_item_has_digitool_url($item)):
						return $item;
					endif;
				}

			}
		}
	}

	return false;
}
