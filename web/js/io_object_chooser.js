io_object_chooser_placeholder_li = '<li class="placeholder">None</li>';

jQuery(document).ready(function () {
  
  // check each widget on the page
  jQuery.each(
    jQuery('.io_object_chooser_wrapper'),
    function (indexInArray, valueOfElement) {
      // if this widget has relations already, update their previews accordingly
      update_object_selection($(this));
    });
  
  // listen to any "viewing" links the user attempts (like pagination or browsing etc..)
  jQuery('.io_object_chooser_button a, .io_object_chooser_pagination a').live('click', function () {
    var response_div = jQuery(this).parents('.io_object_chooser_wrapper').find('div.io_object_chooser_response');
    
    if ($(this).parent().hasClass('io_object_chooser_button'))
    {
      response_div.toggle();
    }
    
    var url = jQuery(this).attr('href');
    
    jQuery.ajax({
      url: url,
      success: function (data, textStatus, XMLHttpRequest) {
        response_div.html(data);
      }
    });
    
    return false;
  });
  
  // listen to any "selection" links the user clicks (like picking an object from the menu)
  jQuery('.io_object_chooser_selection a').live('click',function () {
    var wrapper = jQuery(this).parents('.io_object_chooser_wrapper');
    var object_id = jQuery(this).parent().attr('rel');
    add_object_to_selection(wrapper, object_id);
    
    var response_div = jQuery(this).parents('.io_object_chooser_wrapper').find('div.io_object_chooser_response');
    response_div.hide();
    
    return false;
  });
  
  // (in the case of one related object) listen to any "deletion" links the user may click on
  jQuery('.io_object_chooser_wrapper.choose_one div.io_object_chooser_preview a.delete').live('click', function () {
    var object_id = $(this).parent().attr('rel');
    $(this).parents('.io_object_chooser_wrapper').find('.io_object_chooser_holder input[value='+object_id+']').val('');
    $(this).parents('ul').html(io_object_chooser_placeholder_li);
    return false;
  });
  
  // (in the case of many related objects) listen to any "deletion" links the user may click on
  jQuery('.io_object_chooser_wrapper.choose_many div.io_object_chooser_preview a.delete').live('click', function () {
    var object_id = $(this).parent().attr('rel');
    $(this).parents('.io_object_chooser_wrapper').find('.io_object_chooser_holder input[value='+object_id+']').remove();
    if ($(this).parents('ul').children().length == 1)
    {
      $(this).parents('ul').html(io_object_chooser_placeholder_li);
    }
    else
    {
      $(this).parent().remove();
    }
    return false;
  });
  
  /*
   * helper function to add an object with "object_id" to the relation list of this widget
   */
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
  
  /*
   * updates the preview holder to show what objects are really related
   */
  function update_object_selection(wrapper) {
    var selections = wrapper.find('.io_object_chooser_holder input');
    var url = wrapper.find('.io_object_chooser_preview').attr('rel');
    var preview = wrapper.find('.io_object_chooser_preview ul');
    
    preview.html(io_object_chooser_placeholder_li);
    
    jQuery.each(selections, function (indexInArray, valueOfElement) {
      var object_id = $(this).val();
      if (!preview.find('li[rel='+object_id+']').length && object_id)
      {
        preview.find('li.placeholder').remove();
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
