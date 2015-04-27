jQuery(function(){
  jQuery('.new-hash').on('click', function(e){
      var next_index = jQuery('.hash-list li').length;
      var hash_item = '<li><label><input  type="text" name="wa_wufoo_hash_label['+next_index+']" value="" placeholder="Enter the form\'s name" /></label><label><input  type="text" name="wa_wufoo_hash['+next_index+']" value="" placeholder="Enter the form\'s hash" /></label> <a href="#" class="delete-hash">Remove Hash</a></li>';

      jQuery('.hash-list').append(hash_item);
  });

  jQuery('.hash-list').on('click', '.delete-hash', function(e) {
    e.preventDefault();
    jQuery(this).parent().remove();
  });
});
