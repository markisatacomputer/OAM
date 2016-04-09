function update_eg(value,option,result){
		jQuery.ajax({
		   type: "POST",
		   url: "<?php echo(get_bloginfo('template_directory')); ?>/eg.php",
		   data: "value="+value+"&option="+option+"&result="+result,
		   dataType: "html",
		   success: function(html){
			 document.getElementById(result).innerHTML = html;
		   }
		 });
	}
	function clear_cache(){
		jQuery.ajax({
		   type: "POST",
		   url: "<?php echo(get_bloginfo('template_directory')); ?>/cache.php",
		   dataType: "html",
		   success: function(html){
			 document.getElementById('cache_result').innerHTML = html;
		   }
		 });
	}
	jQuery(document).ready(function($) {
	
	//  ajax uploader
	 new AjaxUpload('font_upload', {
	 action: '<?php echo(get_bloginfo('template_directory')); ?>/upload.php',
		 onSubmit : function(file , ext){
			 if (! (ext && /^(ttf)$/i.test(ext))){
					 // extension is not allowed
					 alert('Error: invalid file extension');
					 // cancel upload
					 return false;
			 }
		 },
		 onComplete : function(file,response){
			 document.getElementById('upload_result').innerHTML += file + response;				
		 }
	 });
	//color picker
	
	 var f = $.farbtastic('#picker');
	 var p = $('#picker').css('opacity', 0.25);
	 var selected;
	 $('.colorwell')
	   .each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
	   .focus(function() {
		 if (selected) {
		   $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
		 }
		 f.linkTo(this);
		 p.css('opacity', 1);
		 $(selected = this).css('opacity', 1).addClass('colorwell-selected');
	   });
      
    });