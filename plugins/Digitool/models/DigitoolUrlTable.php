<?php
class DigitoolUrlTable extends Omeka_Db_Table
{
	//otherwise return the whole table
    //sort table
    //function cmp($a, $b) {

       //	return $a->pid - $b->pid;
   // }

    public function findDigitoolUrlByItem($item, $findOnlyOne = false)
    {
        $db = get_db();

        if (($item instanceof Item) && !$item->exists()) {
            return array();
        } else if (is_array($item) && !count($item)) {
            return array();
        }

        // Create a SELECT statement for the Location table
        $select = $db->select()->from(array('d' => $db->DigitoolUrl), 'd.*');

        // Create a WHERE condition that will pull down all the digitool info
        if (is_array($item)) {
            $itemIds = array();
            foreach ($item as $it) {
                $itemIds[] = (int)(($it instanceof Item) ? $it->id : $it);
            }
            $select->where('d.item_id IN (?)', $itemIds);
        } else {
            $itemId = (int)(($item instanceof Item) ? $item->id : $item);
            $select->where('d.item_id = ?', $itemId);
        }

        // Get the DigitoolUrls
        $urls = $this->fetchObjects($select);

        // If only a single image is requested, return the first one found.
        if ($findOnlyOne) {
            return current($urls);
        }

        //print_r($urls);
       // usort($urls, "cmp");
		//print_r($urls);
       return $urls;
    }
}

