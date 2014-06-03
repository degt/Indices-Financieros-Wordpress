jQuery(document).ready(function(){
	jQuery.ajax({
		url: "/?action=indices",
		dataType: 'json',
		//data: data,
		success: function(e){
			var indices = '';
			jQuery.each(e, function(index, val){
				indices += val.name+' <span>'+val.value+'</span>';
			});
			jQuery('#indices').html(indices);
		}
	});

});
