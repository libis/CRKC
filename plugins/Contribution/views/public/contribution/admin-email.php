Op de website <?php echo get_option('site_title'); ?> is er een gestolen object gemeld.
	
U kan het gestolen object bekijken en publiceren via:

    <?php
        set_theme_base_uri('admin');
        echo abs_item_uri($item);
        set_theme_base_uri();
    ?>