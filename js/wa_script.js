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
    },
    error : function(jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);
    }
  });
});
