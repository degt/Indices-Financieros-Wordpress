jQuery(document).ready(function(){
	jQuery.ajax({
		url: "/?action=indices",
		dataType: 'json',
		//data: data,
		success: function(e){
			jQuery.each(e, function(index, val){
				jQuery("#"+index).text(val);
			});
		}
	});

});
