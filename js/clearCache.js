jQuery(document).ready(function() {
    console.log("Document Ready");
    var clearCacheListItem = jQuery('li.clear-redis-cache');
    var clearCacheUrl = clearCacheListItem.find('a.clear-redis-url').attr('href');
    clearCacheListItem.find('div').on('click', function() {
        // jQuery.get(clearCacheUrl, function(){ 
        //     console.log("Cache Cleared");
        // });
        		var data = {
			'action': 'my_action',
			'whatever': 1234
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(error) {
			if(error) {
                console.error(error);
            } else {
                console.log("Cleared the Starward Cache!");
            }
		});
    });
});
