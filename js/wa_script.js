jQuery('.wufoo-form').on('submit', function(e){
  e.preventDefault();
  var formType = jQuery(this).data('form-type');

  if (formType === undefined) {
    console.log('ERROR: Form type not defined. Include a data-form-type attribute on your form tag');
    return;
  }

  var data = {
    action    : 'wufoo_post',
    fields    : jQuery(this).serialize(),
    form_type : formType
  };

  jQuery.ajax({
    type     : 'POST',
    url      : '/wp-admin/admin-ajax.php',
    data     : data,
    dataType : 'json',
    success  : function(response) {
      console.log(response);
      if (response.Success !== 1) {
        // we have a field error, so loop through the errors
        var errors = response.FieldErrors;
        jQuery.each( errors, function(){
          // in case of multiple errors let's log them with their fieldID's
          console.log(this.ID + ": " + this.ErrorText);
        });
      } else {
        // we're good
        console.log("Post successful");
      }
    },
    error : function(jqXHR, textStatus, errorThrown) {
      // HTML error in communicating with Wufoo
      console.error(errorThrown);
    }
  });
});
