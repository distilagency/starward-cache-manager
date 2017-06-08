jQuery(document).ready(function() {
    var clearCacheListItem = jQuery('li.clear-redis-cache');
    var label = clearCacheListItem.find('.dashicons-before');
    var clearCacheUrl = clearCacheListItem.find('a.clear-redis-url').attr('href');
    clearCacheListItem.find('div').on('click', function() {
        // jQuery.get(clearCacheUrl, function(){ 
        //     console.log("Cache Cleared");
        // });
        var data = {
			'action': 'clear_starward_cache',
		};

        var hideAfter = function(seconds) {
            return function () {
                setTimeout(function() {
                    label.attr('class', 'dashicons-before dashicons-yes go-invisible');
                }, seconds)
            }
        }

        var hideAftertwoSeconds = hideAfter(2000);
		
        label.attr('class', 'dashicons-before dashicons-image-rotate rotating');
		jQuery.post(ajaxurl, data, function(error) {
			if(error) {
                console.error(error);
                label.attr('class', 'dashicons-before dashicons-no');
                hideAftertwoSeconds();
            } else {
                console.log('Cleared the Starward Cache!');
                label.attr('class', 'dashicons-before dashicons-yes');
                hideAftertwoSeconds();
            }
		}).fail(function(error) {
            label.attr('class', 'dashicons-before dashicons-no');
            hideAftertwoSeconds();
        })
    });

    // clearCacheListItem.append('<span class="flush-icon"></span>')
});
