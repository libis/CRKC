<?php
/* OMEKA PLUGIN ADDTHIS:
 * This plug-in will add the Feed Collector to Omeka,
 * You are able to add a feed in the configuration menu *
 */

// add plugin hooks (configuration)
add_plugin_hook('config_form', 'feedCollector_config_form');
add_plugin_hook('config', 'feedCollector_config');

//install and uninstall
add_plugin_hook('install', 'feedCollector_install');
add_plugin_hook('uninstall', 'feedCollector_uninstall');

//set the location
//add_plugin_hook(get_option('addThis_hook'),'feedCollector_add');

//link to config_form.php
function feedCollector_config_form() {
	include('config_form.php');
}

//process the config_form
function feedCollector_config() {
	//get the POST variables from config_form and set them in the DB
	if($_POST["rss"])
		set_option('feedCollector_rss',$_POST['rss']);

	if($_POST["proxy"])
		set_option('feedCollector_proxy',$_POST['proxy']);

	if($_POST["limit"])
		set_option('feedCollector_limit',$_POST['limit']);
}

//handle the installation
function feedCollector_install() {
	set_option('feedCollector_rss','');
   	set_option('feedCollector_proxy','');
   	set_option('feedCollector_limit','2');
}

//handle the uninstallation
function feedCollector_uninstall() {
    // Delete the plugin options
    delete_option('feedCollector_rss');
    delete_option('feedCollector_proxy');
    delete_option('feedCollector_limit');
}

//converts the rss feed into html and returns it
function feedCollector_show($class = "feed-box",$newlimit = null) {

	$html="";

	$rss = get_option('feedCollector_rss');

	$proxy = get_option('feedCollector_proxy');
	if($newlimit == null || $newlimit== 0){
		$limit = get_option('feedCollector_limit');
	}
	else{
		$limit = $newlimit;
	}


	if(!empty($rss))
		$html .= feedCollector_convertToHtml($rss,$proxy,$limit,$class);

	if($html=="")
		return "<p>No news feeds found</p>";
	else
		return $html;

}

function feedCollector_convertToHtml($feed,$proxy,$limit,$class) {
	//$compression='gzip',$proxy='icts-http-gw.cc.kuleuven.be:8080') {
	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml';
	$headers[] = 'Connection: Keep-Alive';
	$headers[] = 'Content-type: application/xml;charset=UTF-8';
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';

	$ch = curl_init($feed);

	//set options
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

	curl_setopt($ch, CURLOPT_HEADER, 0);

	//PROXY icts-http-gw.cc.kuleuven.be:8080
	if(!empty($proxy))
		curl_setopt($ch, CURLOPT_PROXY,$proxy);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

	//get data and close connection
	$data = curl_exec($ch);

	//die(curl_error($ch));

	curl_close($ch);

	try {
	  $doc = @new SimpleXmlElement($data, LIBXML_NOCDATA);
	} catch (Exception $e) {
	  echo "There is something wrong with the xml.";
	}


	if(isset($doc->channel))
	{
		$html = parseRSS($doc,$limit,$class);
	}
	if(isset($doc->entry))
	{
		$html = parseAtom($doc,$limit,$class);
	}

	return $html;
}

function parseRSS($xml,$cnt,$class)
{

	$html= "<div class='".$class."'>";
	$html.= "<h2>".$xml->channel->title."</h2>";
	$html .= "<div class='news'>";
	//$cnt = count($xml->channel->item);
	for($i=0; $i<$cnt; $i++)
	{
		$html .= "<div class='news-entry'>";
		$url 	= $xml->channel->item[$i]->link;
		$title 	= $xml->channel->item[$i]->title;
		$desc = $xml->channel->item[$i]->description;

		if($xml->channel->item[$i]->image->url)
			$image= "<img width=70 class='inline' src='".$xml->channel->item[$i]->image->url."'>";
		else
			$image="";

		$html.= '<h6><a href="'.$url.'">'.$title.'</a></h6><p>'.$image.'</p><p>'.$desc.'</p><br>';
	}
	$html .= "</div><p><a href='".$xml->channel->link."'>Alle berichten</a></p></div>";
	return $html;
}

function parseAtom($xml,$cnt,$class)
{

	$html= "<div class='".$class."'>";
	$html .= "<h2>Nieuws</h2>";
	$html .= "<div class='news'>";
	//$cnt = count($xml->entry);
	$i==1;
	foreach($xml->entry as $entry)
	{
		$html .= "<div class='news-entry'>";
		//var_dump ($entry);//$urlAtt = $entry->link[$i]->attributes;
		//echo"<br><br>";
		/*echo '<pre>';
		var_dump($entry);
		echo '</pre>';*/
		$url	= $entry->link['href'];
		$title 	= $entry->title;
		//$image = $entry->image;
		$desc	= Libis_excerpt(strip_tags($entry->content),100);
		foreach($entry->link as $link){
			if($link['type'] == 'image/jpeg')
				$image = "<img width=70 class='inline' src=".$link['href'].">";
		}

		$html.= '<h6><a href="'.$url.'">'.$title.'</a></h6>
		<span class="agenda-datum">'.substr_replace($entry->published,"",-10).'</span>
		<p>'.$image.$desc.'</p>';
		$html .= "<a href=".$url.">Lees meer</a>";
		$html .= "</div>";
		$i++;
		if($i==$cnt){break;}


	}
	$html .= "</div>";
	//add link to all news
	// aangepast door Sam, was .$xml->link['href'].
	//echo $_SERVER['REQUEST_URI'];
	if($_SERVER['REQUEST_URI'] != '/actualiteit/'){
		$html .= '<p><a href="/actualiteit/">Lees alle nieuwsberichten</a></p>';
	}
	$html .= "</div>";
	return $html;
}
