jQuery(document).ready(function () {
  
  jQuery.each(
    jQuery('.io_object_chooser_wrapper'),
    function (indexInArray, valueOfElement) {
      update_object_selection($(this));
    });
  
  jQuery('.io_object_chooser_selection a').live('click',function () {
    var wrapper = jQuery(this).parents('.io_object_chooser_wrapper');
    var object_id = jQuery(this).parent().attr('rel');
    add_object_to_selection(wrapper, object_id);
    
    var popup = jQuery(this).parents('.io_object_chooser_wrapper').find('div.io_object_chooser_popup');
    popup.hide();
    
    return false;
  });
  
  jQuery('.io_object_chooser_button a, .io_object_chooser_pagination a').live('click', function () {
    var popup_div = jQuery(this).parents('.io_object_chooser_wrapper').find('div.io_object_chooser_popup');
    popup_div.show();
    
    var url = jQuery(this).attr('href');
    
    jQuery.ajax({
      url: url,
      success: function (data, textStatus, XMLHttpRequest) {
        popup_div.html(data);
      }
    });
    
    return false;
  });
  
  jQuery('.io_object_chooser_wrapper.choose_one a.delete').live('click', function () {
    var object_id = $(this).parent().attr('rel');
    $(this).parents('.io_object_chooser_wrapper').find('.io_object_chooser_holder input[value='+object_id+']').val('');
    $(this).parent().remove();
    return false;
  });
  
  jQuery('.io_object_chooser_wrapper.choose_many a.delete').live('click', function () {
    var object_id = $(this).parent().attr('rel');
    $(this).parents('.io_object_chooser_wrapper').find('.io_object_chooser_holder input[value='+object_id+']').remove();
    $(this).parent().remove();
    return false;
  });
  
  function add_object_to_selection (wrapper, object_id) {
    if (wrapper.hasClass('choose_one'))
    {
      var input_element = wrapper.find('div.io_object_chooser_holder input');
      input_element.val(object_id);
    }
    else if (wrapper.hasClass('choose_many'))
    {
      var holder = wrapper.find('div.io_object_chooser_holder');
      var field_name = wrapper.attr('rel')+'[]';
      if (!holder.find('input[value='+object_id+']').length)
      {
        holder.append('<input type="hidden" name="' + field_name + '" value="' + object_id + '">');
      }
    }
    
    update_object_selection(wrapper);
  }
  
  function update_object_selection(wrapper) {
    var selections = wrapper.find('.io_object_chooser_holder').find('input');
    var url = wrapper.find('.io_object_chooser_preview').attr('rel');
    var preview = wrapper.find('.io_object_chooser_preview ul');
    
    if (wrapper.hasClass('choose_one'))
    {
      preview.html('');
    }
    
    jQuery.each(selections, function (indexInArray, valueOfElement) {
      var object_id = $(this).val();
      if (!preview.find('li[rel='+object_id+']').length)
      {
        jQuery.ajax({
          url: url,
          data: { id: object_id },
          success: function (data, textStatus, XMLHttpRequest) {
            preview.append('<li rel="'+object_id+'">'+data+' <a href="#" class="delete">X</a></li>');
          }
        });
      }
    })
  }
  
});
