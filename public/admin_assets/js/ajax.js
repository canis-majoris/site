
var loading_overlay_block = '<div class="preloader-overlay" style="">' +
                                '<div class="preloader-full" style="display: block;">' +
                                    '<div class="progress" style="margin: 0;">' +
                                        '<div class="indeterminate"></div>' + 
                                    '</div>' +
                                '</div>' +
                            '</div>';
var loading_overlay_progress = '<div class="preloader-full custom-preloader-progress-1" style="display: block;">' +
                                    '<div class="progress">' +
                                        '<div class="indeterminate"></div>' + 
                                    '</div>' +
                                '</div>';
var loading_overlay_block_2 = '<div class="preloader-wrapper big active svg-blur-0">' +
      '<div class="spinner-layer spinner-blue">' +
       ' <div class="circle-clipper left">' +
          '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
          '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
          '<div class="circle"></div>' +
        '</div>' +
      '</div>' +
      '<div class="spinner-layer spinner-red">' +
        '<div class="circle-clipper left">' +
          '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
          '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
          '<div class="circle"></div>' +
        '</div>' +
      '</div>' +
      '<div class="spinner-layer spinner-yellow">' +
        '<div class="circle-clipper left">' +
          '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
          '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
          '<div class="circle"></div>' +
        '</div>' +
      '</div>' +
      '<div class="spinner-layer spinner-green">' +
        '<div class="circle-clipper left">' +
          '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
          '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
          '<div class="circle"></div>' +
        '</div>' +
      '</div>' +
    '</div>';

var wave_animation = '<div class="ocean">' +
  '<div class="wave1"></div>' +
  '<div class="wave1"></div>' +
'</div>';

var reload_page = function (url = null) {
    window.location = '/';
}

var inline_message_template = function(message, type = null) {
    var template_class = 'info-inline';
    if (type) {
        switch(type) {
            case 'success':
                template_class = 'success-inline';
                break;
            case 'error':
                template_class = 'error-inline';
                break;
            case 'warning':
                template_class = 'warning-inline';
                break;
            case 'info':
                template_class = 'info-inline';
                break;
            default:
                template_class = 'info-inline';
        };
    }
    
    var template = '<div class="wrapper-custom-inline-1 z-depth-1 ' + template_class + '">' + message + '</div>';

    return template;
}

var small_dialog_template = function(message, status = 1) {
    var dialog_icon;
    switch(status) {
        case 0:
            dialog_icon = '<i class="material-icons">error_outline</i>';
            break;
        case 1:
            dialog_icon = '<i class="material-icons">check</i>';
            break;
        case 2:
            dialog_icon = '<i class="material-icons">info_outline</i>';
            break;
        case 3:
            dialog_icon = '<i class="material-icons">warning</i>';
            break;
        default:
            dialog_icon = '<i class="material-icons">check</i>';
    };
    
    var template = '<div class="dialog-custom-1">' + dialog_icon + ' ' + message + '</div>';

    return template;
}

var show_alert = function(url, data) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){

          if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
          } else {
            swal({
                title: data.title,
                text: data.text,
                type: data.type,
                //html: data.html,
                //showCancelButton: data.cancel,
                confirmButtonColor: data.btn_color,
                confirmButtonText: data.btn_text,
                //cancelButtonText: "Cancel",
            });
          }
      }
  });
}

//PRODUCTS
var menu_items_change = function (_this, url) {
    $('.collection-item').not($(_this)).removeClass('active');
    $(_this).addClass('active');
    menu_item_id = $(_this).data('id');
    
    if($('.collection-item').hasClass('active')) {
        $('#menu_item_options').slideDown(100);
    } else {
        $('#menu_item_options').slideUp(100);
    }

    load_menu_item_settings(url, menu_item_id);
    //url = "{{route('menus_items_data-load')}}"
    $('.add_product_button-1').slideDown(100);
    
    window.LaravelDataTables["dataTableBuilder"].draw(false);
    //e.preventDefault();
    //load_menu_item_data(url, lang_ind, $('#menu_items_data_container'), menu_item_id);
};

var clear_menu_items_change = function (e) {
    $('.collection-item').removeClass('active');
    $('#menu_item_options').slideUp(100);
    $('.add_product_button-1').slideUp(100)
    menu_item_id = null;
    window.LaravelDataTables["dataTableBuilder"].draw(false);
    //e.preventDefault();
};

var product_changestatus = function (product, url, product_changestatus) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'op_ids':[$(product).data('id')]},
        error: function(a,b,c) {
            Materialize.toast(small_dialog_template(c, 0), 4000, 'dialog_class-0');
        },
        success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
            if (data.status == 1) {
                $(product).addClass('active');
                } else {
                    $(product).removeClass('active');
                }
            }
            msg_text = data.message;
            Materialize.toast(small_dialog_template(data.message), 4000, 'dialog_class-1');

            $.each(product_changestatus, function( index, value ) {
                value.draw(false);
            });
        }
    });
}

var manage_product_load_modal = function(url, container, product_id) {
    //$('#manage_product_modal').modal("show");
    var product_id = product_id || null;
    container.html(loading_overlay_block).css({'min-height':'100px'});
    $('#manage_product_modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        manage_product_load(url, container, product_id);
      },
      complete: function() { } // Callback for Modal close
    });
    $('#manage_product_modal').modal('open')
    //$('#modal5').openModal();
}

var manage_product_load = function(url, container, product_id) {
    //container.html(loading_overlay_block);
    var product_id = product_id || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'menu_item_id': menu_item_id, 'product_id': product_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });

                /*if (data.itemCount == 0) {
                    $('#menu_item_options').slideUp(100);
                } else {
                    $('#menu_item_options').slideDown(100);
                }*/
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}
var product_img_update = function (product_id, name, url) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'id': product_id, 'name': name},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
          } else {
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      }
  });
}


var save_product = function(url, form) {
    $(form).validate({
        errorElement: 'span',
        rules: {
            name: {
                required:true,
                minlength:1,
                maxlength:100,
            },
            price: {
                required:true,
                minlength:1,
                maxlength:100,
                
            }
        },
        messages: { 
          /*name: {
            required:'გთხოვთ შეავსოთ ველი name',
            maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
          },
          name_trans: {
            required:'გთხოვთ შეავსოთ ველი link',
            maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
          },
          description: {
            required:'გთხოვთ შეავსოთ ველი menuanchor',
            minlength:'დაშვებულია არანაკლებ 2 სიმბოლო',
            maxlength:'დაშვებულია მხოლოდ 100 სიმბოლო',
          }*/
        },

        submitHandler: function(form) {
            btn_loading($('#product_save-btn'));
            var formData = new FormData(form);
            var text = CKEDITOR.instances['p_n_text'].getData();
            formData.set('text', text);
            $.ajax({
                url: url,
                type: "post",
                //datatype: 'json',
                data: formData,
                contentType: false,
                processData: false,
                error: function(a,b,c) {
                    btn_reset($('#product_save-btn'));
                },
                success: function(data){
                    btn_reset($('#product_save-btn'));
                    if (!data.success) {
                        msg_text = data.message;
                        Materialize.toast( data.message, 4000);
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        
                        Materialize.toast( data.message, 4000);
                        $('#manage_product_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                        /*container.fadeOut(100, function (e) {
                            //$(this).css({'width':'auto', 'height':'auto'});
                            $(this).html(data.html);
                            $(this).fadeIn(100);
                        });

                        $('#add_menu_item_modal').closemodal('close');*/
                    }
                },
                beforeSend: function() {
                    /*haight = container.height();
                    width = container.width();

                    container.append(loading_overlay_block);*/
                },
                ajaxComplete: function () {
                    
                }
            });

            return false;
        }
    });
}

function load_menu_items(url, lang, container) {
	$.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'language_id': lang},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });

                /*if (data.itemCount == 0) {
                    $('#menu_item_options').slideUp(100);
                } else {
                    $('#menu_item_options').slideDown(100);
                }*/
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();

            container.append(loading_overlay_block);
        },
        ajaxComplete: function () {
           $(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

function load_menu_item_data(url, lang, container, menu_item_id) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'language_id': lang, 'menu_item_id': menu_item_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
                
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();

            container.append(loading_overlay_block);
        },
        ajaxComplete: function () {
            $(".collapsible").collapsible({
                accordion: false
            });
            $('.tooltipped').tooltip({delay: 50});
        }
    });
}

function load_menu_item_settings(url, menu_item_id) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'menu_item_id': menu_item_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                $('#m_i_c_name_trans').val(data.menu.name_trans).focus();
                $('#m_i_c_name').val(data.menu.name).focus();
                $('#m_i_c_menu_item_id').val(data.menu.id).focus();
                
                //$('#m_i_c_description').val(data.menu.name_trans);
                if (data.menu.watch) {
                    $('#m_i_c_watch').prop('checked', true);
                } else $('#m_i_c_watch').prop('checked', false);

                if (data.menu.is_group) {
                    $('#m_i_c_is_group').prop('checked', true);
                } else $('#m_i_c_is_group').prop('checked', false);
            }
        },
        beforeSend: function() {
            
        },
        ajaxComplete: function () {
           
        }
    });
}

function add_menu_item(url, form, container) {

    $(form).validate({
        errorElement: 'span',
        rules: {
            name: {
                required:true,
                minlength:1,
                maxlength:100,
            },
            name_trans: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            description: {
                //maxlength:100,
            },

        },
        messages: { 
          name: {
            required:'გთხოვთ შეავსოთ ველი name',
            maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
          },
          name_trans: {
            required:'გთხოვთ შეავსოთ ველი link',
            maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
          },
          description: {
            required:'გთხოვთ შეავსოთ ველი menuanchor',
            minlength:'დაშვებულია არანაკლებ 2 სიმბოლო',
            maxlength:'დაშვებულია მხოლოდ 100 სიმბოლო',
          }
        },

        submitHandler: function(form) {
            btn_loading($('#add_menu_item_save-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#add_menu_item_save-btn'));
                },
                success: function(data){
                    btn_reset($('#add_menu_item_save-btn'));
                    if (!data.success) {
                        msg_text = data.message;
                        Materialize.toast( data.message, 4000);
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        container.fadeOut(100, function (e) {
                            //$(this).css({'width':'auto', 'height':'auto'});
                            $(this).html(data.html);
                            $(this).fadeIn(100);
                        });

                        $('#add_menu_item_modal').modal('close');
                    }
                },
                beforeSend: function() {
                    haight = container.height();
                    width = container.width();

                    container.append(loading_overlay_block);
                },
                ajaxComplete: function () {
                    //$('#add_menu_item_modal').modal('close');
                }
            });

            return false;
        }
    });
}

var products_reorder = function(url, order_arr, menu_item_id) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'menu_item_id': menu_item_id, 'diff': order_arr},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                
            }
            msg_text = data.message;
            Materialize.toast( data.message, 4000);
            window.LaravelDataTables["dataTableBuilder"].draw(false);
        },
        beforeSend: function() {
            
        },
        ajaxComplete: function () {
           
        }
    });
}


var menu_items_update = function(url, form) {
    form.on('submit', function(e) {
        e.preventDefault();
        $(form).validate({
            errorElement: 'span',
            rules: {
                name: {
                    required:true,
                    minlength:1,
                    maxlength:100,
                },
                name_trans: {
                    required:true,
                    minlength:1,
                    maxlength:100,
                    
                },
                description: {
                    //maxlength:100,
                },

            },
            /*messages: { 
              name: {
                required:'გთხოვთ შეავსოთ ველი name',
                maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
              },
              name_trans: {
                required:'გთხოვთ შეავსოთ ველი link',
                maxlength:'დაშვებულია არაუმეტეს 100 სიმბოლო',
              },
              description: {
                required:'გთხოვთ შეავსოთ ველი menuanchor',
                minlength:'დაშვებულია არანაკლებ 2 სიმბოლო',
                maxlength:'დაშვებულია მხოლოდ 100 სიმბოლო',
              }
            },*/

            submitHandler: function(form) {
                btn_loading($('#edit_menu_item_settings-save-btn'));
                var formData = new FormData(form);
                var text = CKEDITOR.instances['m_i_c_description'].getData();
                formData.set('text', text);
                $.ajax({
                    url: url,
                    type: "post",
                    //datatype: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    error: function(a,b,c) {
                        btn_reset($('#edit_menu_item_settings-save-btn'));
                    },
                    success: function(data){
                        btn_reset($('#edit_menu_item_settings-save-btn'));
                        if (!data.success) {
                            if (data.reload) {
                                reload_page();
                            }
                        } else {

                            /*container.fadeOut(100, function (e) {
                                //$(this).css({'width':'auto', 'height':'auto'});
                                $(this).html(data.html);
                                $(this).fadeIn(100);
                            });

                            $('#add_menu_item_modal').closeModal();*/
                        }
                        msg_text = data.message;
                        Materialize.toast( data.message, 4000);
                    },
                    beforeSend: function() {
                        /*haight = container.height();
                        width = container.width();

                        container.append(loading_overlay_block);*/
                    },
                    ajaxComplete: function () {
                        //$('#add_menu_item_modal').modal('close');
                    }
                });

                return false;
            }
        });
    });
    form.submit();
}
/*function delete_menu_item(url, lang, container, menu_item_id) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'language_id': lang, 'menu_item_id': menu_item_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
                
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();

            container.append(loading_overlay_block);
        },
        ajaxComplete: function () {
            $(".collapsible").collapsible({
                accordion: false
            });
            $('.tooltipped').tooltip({delay: 50});
        }
    });
}*/



//IMAGE UPLOAD

var uploadImage = function(form, url, product_id) {
    var fd = new FormData(form);
    var $image = $(".image-crop > img");
    var image_name = null;
    pos_data = null;
    if ($('#crop-editor-status').val() == 0) {
        $image.cropper('destroy');
    } else if ($('#crop-editor-status').val() == 1) {
        pos_data = JSON.stringify($image.cropper('getData'));
    }

    fd.append('image_crop_coordinates', pos_data);
    fd.append('image', $('#upload')[0].files[0]);
    fd.append('action', 'upload');
    if (product_id) {
        fd.append('product_id', product_id);
    }

    $.ajax({
        url: url,
        type: "post",
        //datatype: 'json',
        data: fd,
        contentType: false,
        processData: false,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                $('.delete_product_image-btn').prop('disabled', true);
                $('#product_image_name').val('');
                if (data.reload) {
                    reload_page();
                }
            } else {
                $('#product_image_name').val(data.newfilename);
                //console.log(data.newfilename);
                $('.delete_product_image-btn').prop('disabled', false);
                //$('.upload_product_image-btn').button('reset');
            }
            msg_text = data.message;
            Materialize.toast( data.message, 4000);
        },
        beforeSend: function() {
            $('.card-preloader-full').fadeIn(100);
            //$('.upload_product_image-btn').button('loading');
        },
        complete: function(){
            $('.card-preloader-full').fadeOut(100);
            $('.upload_product_image-btn').prop('disabled', true);
        },
    });

    return image_name;
}

var deleteImage = function(name, url, product_id) {
    var product_id = product_id  || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'filename':name, 'action':'delete', 'product_id':product_id, 'directory': 'products'},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                $('#product_image_name').val('');
                var $image = $(".image-crop > img");

                $image.prop('src', '');
                $image.cropper('destroy');
                $('#upload').val('');
                $('#file_input').val('');
                $('#remove_img_ind').val(1);
                $('#image_upload_input-wrapper').fadeIn(0);
                Materialize.toast( data.message, 4000);
            }
        },
        beforeSend: function(e) {
        },
        complete: function (e) {
           $('.delete_product_image-btn').prop('disabled', true);
        }
    });
}

var loadImage = function(_this) {
    var reader = new FileReader();
    reader.onload = function (e) {

        var image = new Image();

        image.src = reader.result;
        $(".image-crop > img").prop('src', image.src);
        image.onload = function() {
            var $image = $(".image-crop > img");
            cropper1 = $image.cropper('destroy');

            cropper1.cropper({
                //aspectRatio: 16 / 9,
                preview: ".img-preview",
            });
            $('.cropper-controll-wrapper').slideUp(50);
            $('#crop-editor-status').val(1);
            $('#crop_status_switch-wrapper').slideDown(100);
            //cropper1.cropper('disable');
            cropper1.cropper('enable');
        };  
    }
    if (_this.files[0] && _this.files[0].type.match('image.*')) {
         reader.readAsDataURL(_this.files[0]);
         //console.log(_this.files[0].size);
         //$('.upload_product_image-btn').prop('disabled', false);
         if(_this.files[0].size > 5242880) {
            $('.filesize-warning-sm-1').addClass('red-text text-darken-1');
            $('.upload_product_image-btn').prop('disabled', true);
         } else {
            $('.filesize-warning-sm-1').removeClass('red-text text-darken-1');
            $('.upload_product_image-btn').prop('disabled', false);
         }
    }
}

//DROPZONE FILE UPLOAD
/*Dropzone.autoDiscover = false;

// imageUpload portion is the camelized version of our HTML elements ID. ie <div id="image-upload"> becomes imageUpload.
Dropzone.options.productImageUpload = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 5, // MB
    parallelUploads: 2, //limits number of files processed to reduce stress on server
    addRemoveLinks: true,
    autoProcessQueue: false,
    accept: function(file, done) {
        // TODO: Image upload validation
        done();
    },
    sending: function(file, xhr, formData) {
        // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
        formData.append("_token", $('meta[name="csrf-token"]').attr('content')); // Laravel expect the token post value to be named _token by default
    },
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });

        this.on("success", function(file, response) {
            // On successful upload do whatever :-)
        });
    },
    success: function (file, serverResponse) {
        var fileuploded = file.previewElement.querySelector("[data-dz-name]");
        fileuploded.innerHTML = serverResponse.newfilename;
    },


};

var productDropzone = new Dropzone("#product-image-upload", {
    url: "{{route('file-upload-new')}}",
});

productDropzone.on('removedfile', function (file) {
    confirm = false;
    if (confirm || 1) {
        //$('.dz-image-preview').fadeOut(0);
        //$('.dz-file-preview').fadeOut(0);
        var name = file.previewElement.querySelector('[data-dz-name]').innerHTML;
        var url = "{{route('file-delete')}}"
        $.ajax({
            url: url,
            type: "post",
            datatype: 'json',
            data: {'filename': name},
            error: function(a,b,c) {
                Materialize.toast( c, 4000);
            },
            success: function(data){
                if (!data.success) {
                } else {
                    Materialize.toast( data.message, 4000);
                    
                }
            },
            beforeSend: function() {
            },
            ajaxComplete: function () {
               
            }
        });
    }
});*/
//////////////////////


//ORDERS

var show_order = function (order, container, url) {
    window.history.pushState("", "Title", url.slice(0,-1) + $(order).data('id'));
    var container_inner = $('.card.invoices-card');
    $.ajax({
      url: url.slice(0,-1) + $(order).data('id'),
      type: "post",
      datatype: 'json',
      data: {'order_id':$(order).data('id')},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {
            if (data.status == 1) {
                $('.show-order-btn').bind('click');
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      },
      beforeSend: function() {
            haight = container_inner.height();
            width = container_inner.width();

            container_inner.append(loading_overlay_block);
      },
      ajaxComplete: function () {
            
      }
  });
}

var save_order = function (url, form) {
    $(form).validate({
        errorElement: 'span',
        rules: {
            code: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            phone: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            name: {
                required: true,
                minlength:1,
                maxlength:100,
            },
            bith_date: {
                minlength:2,
                maxlength:100,
            },
            flat: {
                minlength:2,
                maxlength:100,
            },
            region: {
                minlength:2,
                maxlength:100,
            },
            postcode: {
                minlength:2,
                maxlength:100,
            },
            email: {
                required: true,
                minlength:2,
                maxlength:100,
                email: true
            },
            phone: {
                minlength:2,
                maxlength:100,
            },
            mobile: {
                minlength:2,
                maxlength:100,
            },
        },

        messages: { 
        },

        submitHandler: function(form) {
            btn_loading($('#save-order-parameters-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#save-order-parameters-btn'));
                },
                success: function(data){
                    if (data.reload) {
                        reload_page();
                    }
                    btn_reset($('#save-order-parameters-btn'));
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                },
                beforeSend: function() {
                },
                ajaxComplete: function () {
                }
            });

            return false;
        }
    });
}

//USERS 

var delete_users = function(url, user_ids, rowCount = null, callback_function = null) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'user_ids':user_ids},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                  reload_page();
              }
          } else {
            if (data.status == 1) {
                swal("Deleted", data.message, "success");
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);

          if (callback_function) {
                callback_function(data);
          }
      }
    });
}

var show_user = function (user, container, url) {
    window.history.pushState("", "Title", url.slice(0,-1) + $(user).data('id'));
    var container_inner = $('.card.invoices-card');
    $.ajax({
      url: url.slice(0,-1) + $(user).data('id'),
      type: "post",
      datatype: 'json',
      data: {'user_id':$(user).data('id')},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                  reload_page();
              }
          } else {
            if (data.status == 1) {
                $('.show-user-btn').bind('click');
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      },
      beforeSend: function() {
            haight = container_inner.height();
            width = container_inner.width();

            container_inner.append(loading_overlay_block);
      },
      ajaxComplete: function () {
            
      }
  });
}

var save_user = function (url, form) {
    $(form).validate({
        errorElement: 'span',
        rules: {
            username: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            code: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            phone: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            name: {
                required: true,
                minlength:1,
                maxlength:100,
            },
            bith_date: {
                minlength:2,
                maxlength:100,
            },
            flat: {
                minlength:2,
                maxlength:100,
            },
            region: {
                minlength:2,
                maxlength:100,
            },
            postcode: {
                minlength:2,
                maxlength:100,
            },
            email: {
                required: true,
                minlength:2,
                maxlength:100,
                email: true
            },
            phone: {
                minlength:2,
                maxlength:100,
            },
            mobile: {
                minlength:2,
                maxlength:100,
            },
            password_confirmation: {
              equalTo: "#password"
            }
        },

        messages: { 
        },

        submitHandler: function(form) {
            btn_loading($('#save-user-parameters-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#save-user-parameters-btn'));
                },
                success: function(data){
                    if (data.reload) {
                        reload_page();
                    }
                    btn_reset($('#save-user-parameters-btn'));
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                },
                beforeSend: function() {
                },
                ajaxComplete: function () {
                }
            });

            return false;
        }
    });
}


var add_user = function (url, form, un_username_url, un_email_url) {
    $(form).validate({
        errorElement: 'span',
        rules: {
            username: {
                required:true,
                minlength:3,
                maxlength:30,
                remote: {
                    url: un_username_url,
                    type: "post"
                }
            },
            code: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            phone: {
                required:true,
                minlength:10,
                maxlength:30,
            },
            name: {
                required: true,
                minlength:1,
                maxlength:100,
            },
            bith_date: {
                minlength:2,
                maxlength:100,
            },
            flat: {
                minlength:2,
                maxlength:100,
            },
            region: {
                minlength:2,
                maxlength:100,
            },
            postcode: {
                minlength:2,
                maxlength:100,
            },
            email: {
                required: true,
                minlength:2,
                maxlength:100,
                email: true,
                remote: {
                    url: un_email_url,
                    type: "post"
                }
            },
            phone: {
                minlength:2,
                maxlength:100,
            },
            mobile: {
                minlength:2,
                maxlength:100,
            },
            password: {
                required: true,
                minlength:6,
                maxlength:100,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        },

        messages: { 
        },

        submitHandler: function(form) {
            btn_loading($('#save-user-parameters-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#save-user-parameters-btn'));
                },
                success: function(data){
                    btn_reset($('#save-user-parameters-btn'));
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        $('#add_user_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                    }
                    form_au.find('#username').val('')
                    form_au.find('#email').val('')
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                },
                beforeSend: function() {
                },
                ajaxComplete: function () {
                }
            });

            return false;
        }
    });
}


var save_comment = function(url, form, btn) {
    btn_loading($('#save_comment-btn'));
    var formData = $(form).serialize();
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: formData,
        error: function(a,b,c) {
            btn_reset($('#save_comment-btn'));
            Materialize.toast( c, 4000);
        },
        success: function(data){
          if (!data.success) {
            if (data.reload) {
                reload_page();
            }
          } else {
            $('.comment_modal-trigger').data('comment', $('#comment_text').val());
          }
          btn_reset($('#save_comment-btn'));
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
        }
    });
}

var add_cash_payer = function(url, data) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         //Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                  reload_page();
              }
          } else {

          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      }
  });
}

var edit_product_load_modal = function(url, container, data) {
    //$('#manage_product_modal').modal("show");
    var type = type || null;
   // container.html(loading_overlay_block).css({'min-height':'100px'});

    haight = container.height();
    width = container.width();

    container.append(loading_overlay_block);
    
    $('#add_product_modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.

        edit_product_load(url, container, data);
      },
      complete: function() { 

      } 
    });
    $('#add_product_modal').modal('open');
}

var edit_product_load = function(url, container, data) {
    //container.html(loading_overlay_block);
    var type = type || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            }
        },
        beforeSend: function() {
        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

var save_edit_op = function (url, form) {
    $(form).validate({
        rules: {

        },

        messages: { 
        },

        submitHandler: function(form) {
            btn_loading($('#edit_product_save-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#edit_product_save-btn'));
                },
                success: function(data){
                    if (data.reload) {
                        reload_page();
                    }
                    btn_reset($('#edit_product_save-btn'));
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                    $('#add_product_modal').modal('close');

                    //window.LaravelDataTables["dataTableBuilder"].draw();
                    //window.LaravelDataTables["dataTableBuilder"]['services'].draw();
                    //window.LaravelDataTables["dataTableBuilder"]['mobile_services'].draw();
                },
                beforeSend: function() {
                },
                ajaxComplete: function () {
                }
            });

            return false;
        }
    });
}

var add_product_load_modal = function(url, container, type) {
    //$('#manage_product_modal').modal("show");
    var type = type || null;
   // container.html(loading_overlay_block).css({'min-height':'100px'});

    haight = container.height();
    width = container.width();

    container.append(loading_overlay_block);
    
    $('#add_product_modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      /*opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute*/
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.

        add_product_load(url, container, type);
      },
      complete: function() { 

      } 
    });
    $('#add_product_modal').modal('open');
}

var add_product_load = function(url, container, type) {
    //container.html(loading_overlay_block);
    var type = type || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'type': type, 'user_id': $('#user_id').val()},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });

                /*if (data.itemCount == 0) {
                    $('#menu_item_options').slideUp(100);
                } else {
                    $('#menu_item_options').slideDown(100);
                }*/
            }
        },
        beforeSend: function() {
        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

var save_add_user_product = function (url, form) {
    $(form).validate({
        rules: {

        },

        messages: { 
        },

        submitHandler: function(form) {
            btn_loading($('#add_product_save-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#add_product_save-btn'));
                },
                success: function(data){
                    if (data.reload) {
                        reload_page();
                    }
                    btn_reset($('#add_product_save-btn'));
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                    $('#add_product_modal').modal('close');
                    //window.LaravelDataTables["dataTableBuilder"]['services'].draw();
                    //window.LaravelDataTables["dataTableBuilder"]['mobile_services'].draw();
                },
                beforeSend: function() {
                },
                ajaxComplete: function () {
                }
            });

            return false;
        }
    });
}

//ORDERS PRODUCTS

var delete_orders_products = function(url, op_ids, rowCount = null) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'op_ids':op_ids},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {
            if (data.status == 1) {
                swal("Deleted", data.message, "success");
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      }
    });
}

var ordersproducts_change_status = function(url, status, op_id, type, datatables_obj, switch_btn) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'id': op_id, 'status': status, 'type': type},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {

          }

          $.each(datatables_obj, function( index, value ) {
            value.draw(false);
          });

          msg_text = data.message;
          Materialize.toast( data.message, 4000);
        },
        beforeSend: function() {
            $(switch_btn).prop('disabled', true);
        },
        ajaxComplete: function () {
            //$(switch_btn).prop('disabled', false);
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

//ADMINISTRATORS   
var add_admin = function (url, form, un_email_url) {
    $(form).validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            firstname: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            lastname: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            email: {
                required: true,
                email: true,
                maxlength:100,
                //uniqueEmail: true,
                remote: {
                    url: un_email_url,
                    type: "post"
                 }
            },
            password: {
                required: true,
                minlength: 5,
                maxlength:42
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            },
            /*flat: {
                required:true
            },
            city: {
                required:true,
                minlength:3,
                maxlength:60
            },
            phone: {
                required:true,
                minlength:6,
                maxlength:15,
                regexPhone:true
            },
            country: {
                required:  function(element) {
                    if( $("#your_country").val() =='-1' || $("#your_country").val() =='select country' ){
                      return false;
                    } else {
                      return true;
                    }
                }
            },
            captcha_cnt: {
                required: true,
            },
            postcode: {
                minlength:3,
                maxlength:7
            }*/
        },
        messages: { 
          
        },
        submitHandler: function(form) {
            btn_loading($('#save-admin-parameters-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#save-admin-parameters-btn'));
                },
                success: function(data){
                    btn_reset($('#save-admin-parameters-btn'));
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        $('#add_admin_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                    }
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                }
            });
            return false;
        }
    });
}

var save_admin = function (url, form, un_email_url) {
    $(form).validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            firstname: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            lastname: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            email: {
                required: true,
                email: true,
                maxlength:100,
                //uniqueEmail: true,
               /* remote: {
                    url: un_email_url,
                    type: "post"
                 }*/
            },
            password: {
                //required: true,
                minlength: 6,
                maxlength:42
            },
            password_confirmation: {
                //required: true,
                //equalTo: '#password'
            },
            /*flat: {
                required:true
            },
            city: {
                required:true,
                minlength:3,
                maxlength:60
            },
            phone: {
                required:true,
                minlength:6,
                maxlength:15,
                regexPhone:true
            },
            country: {
                required:  function(element) {
                    if( $("#your_country").val() =='-1' || $("#your_country").val() =='select country' ){
                      return false;
                    } else {
                      return true;
                    }
                }
            },
            captcha_cnt: {
                required: true,
            },
            postcode: {
                minlength:3,
                maxlength:7
            }*/
        },
        messages: { 
          
        },
        submitHandler: function(form) {
            btn_loading($('#admin_save-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#admin_save-btn'));
                },
                success: function(data){
                    btn_reset($('#admin_save-btn'));
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        $('#manage_admin_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                    }
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                }
            });
            return false;
        }
    });
}


var manage_service_packages = function(url, data, redraw_tables) {
    
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         //Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {

          }

          $.each(redraw_tables, function( index, value ) {
                value.draw(false);
          });

          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      },
      ajaxComplete: function () {
           
      }
  });
}

var manage_admin_load_modal = function(url, container, admin_id) {
    //$('#manage_product_modal').modal("show");
    var admin_id = admin_id || null;
    container.html(loading_overlay_block).css({'min-height':'100px'});
    $('#manage_admin_modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        manage_admin_load(url, container, admin_id);
      },
      complete: function() { } // Callback for Modal close
    });
    $('#manage_admin_modal').modal('open')
}

var manage_admin_load = function(url, container, admin_id) {
    //container.html(loading_overlay_block);
    var admin_id = admin_id || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'admin_id': admin_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

//LANGUAGES

var add_language = function (url, form, un_email_url) {
    $(form).validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            language: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            language_code: {
                required:true,
                minlength:1,
                maxlength:5,
                
            },
        },
        messages: { 
          
        },
        submitHandler: function(form) {
            btn_loading($('#save-language-parameters-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#save-language-parameters-btn'));
                },
                success: function(data){
                    btn_reset($('#save-language-parameters-btn'));
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        $('#add_admin_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                    }
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                }
            });
            return false;
        }
    });
}

var save_language = function (url, form) {
    $(form).validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            language: {
                required:true,
                minlength:1,
                maxlength:100,
                
            },
            language_code: {
                required:true,
                minlength:1,
                maxlength:5,
                
            },
        },
        messages: { 
          
        },
        submitHandler: function(form) {
            btn_loading($('#language_save-btn'));
            var formData = $(form).serialize();
            $.ajax({
                url: url,
                type: "post",
                datatype: 'json',
                data: formData,
                error: function(a,b,c) {
                    btn_reset($('#language_save-btn'));
                },
                success: function(data){
                    btn_reset($('#language_save-btn'));
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {
                        $('#manage_language_modal').modal('close');
                        window.LaravelDataTables["dataTableBuilder"].draw(false);
                    }
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                }
            });
            return false;
        }
    });
}

var manage_language_load_modal = function(url, container, language_id) {
    //$('#manage_product_modal').modal("show");
    var language_id = language_id || null;
    container.html(loading_overlay_block).css({'min-height':'100px'});
    $('#manage_language_modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        manage_language_load(url, container, language_id);
      },
      complete: function() { } // Callback for Modal close
    });
    $('#manage_language_modal').modal('open')
}

var manage_language_load = function(url, container, language_id) {
    //container.html(loading_overlay_block);
    var language_id = language_id || null;
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'language_id': language_id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

/////////////////


var load_order_items = function (url, container, type) {
    window.history.pushState("", "Title", url);
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'type': type},
      error: function(a,b,c) {
         //Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {
            if (data.status == 1) {
                container.fadeOut(100, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(100);
                });
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      }
  });
}

var op_load_filter = function(url, type, container, dataTable = null, dataTableOptions = null) {
    if (dataTable != null) {
        dataTable.draw(false);
    }
    
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: {'type': type},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                
                container.fadeOut(200, function (e) {
                    $(this).html(data.html);
                    $(this).fadeIn(0).removeClass('svg-blur-11');
                });

                //container.html(data.html);
                //
                
                if (dataTable != null) {
                   // dataTable.destroy();
                   // $("#dataTableBuilder").removeClass('dtr-inline collapsed');
                    //dataTable = $("#dataTableBuilder").DataTable(dataTableOptions);

                    $.each(data.showcolumns, function( index, value ) {
                        column = dataTable.column(index);
                        //column.visible(value);

                        if (!value) {
                            $(column.header()).addClass( 'never' );
                        } else {
                            $(column.header()).removeClass( 'never' );
                        }
                        //window.dispatchEvent(new Event('resize'));
                    });

                    dataTable.columns.adjust();
                    dataTable.responsive.rebuild();
                    dataTable.responsive.recalc();

                }

                
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
            //container.addClass('svg-blur-11');
            container.append(loading_overlay_block);
            // if (container.find('.ocean').length == 0) {
            //     container.addClass('loading-filter').append(wave_animation);
            // }
            

        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

var op_load_table = function(url, data, container) {

    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                //msg_text = data.message;
                //Materialize.toast( data.message, 4000);
                
                container.fadeOut(200, function (e) {
                    $(this).html(data.html);
                    $(this).fadeIn(0);
                });
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
            //container.addClass('svg-blur-11');
            container.append(loading_overlay_block);
            // if (container.find('.ocean').length == 0) {
            //     container.addClass('loading-filter').append(wave_animation);
            // }
            

        },
        ajaxComplete: function () {
           //$(document.body).tooltip({ selector: ".tooltipped" });
        }
    });
}

var update_glob = function(url, data, callback_function = null) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         //Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {
            if (callback_function) {
                callback_function(data);
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      }
  });
}


// var manage_load = function(url, container, data = {}, fadespeed = 100, btn = null, type, callback_function = null) {
//     var response = true;
//     var empty = container.find('.loaded-content:first').length == 0;
//     var loadable = container;

//     //console.log(loadable); return

//     if (btn) {
//         btn_loading($(btn));
//     }

//     $.ajax({
//         url: url,
//         type: type,
//         datatype: 'json',
//         data: data,
//         error: function(a,b,c) {
//             Materialize.toast( c, 4000);
//         },
//         success: function(data){
//             if (!data.success) {
//                 msg_text = data.message;
//                 Materialize.toast( data.message, 4000);
//                 if (data.reload) {
//                     reload_page();
//                 }

//                 response = false;
//                 if (data.permission && !data.status) {
//                     container.fadeOut(fadespeed, function (e) {
//                         //$(this).css({'width':'auto', 'height':'auto'});
//                         $(this).html('<div class="modal-content row">' + inline_message_template(data.message, 'error') + '</div>' +
//                             '<div class="modal-footer">' +
//                                 '<button class="modal-action modal-close waves-effect waves-light btn-flat" id="denied_save-cancel-btn" type="button">Cancel</button>' + 
//                             '</div>');
//                         $(this).fadeIn(fadespeed);
//                     });
//                 }
//             } else {


//                 //container.fadeOut(fadespeed, function (e) {
//                     //$(this).css({'width':'auto', 'height':'auto'});
//                     //$(data.html).wrap( "<div class='loaded-content' style='display: none;'></div>" );
//                     // console.log($(container).children());
//                     // $(container).children().fadeOut(fadespeed, function(e) {
//                     //      $(container).html(data.html).hide().fadeIn(fadespeed);
//                     //    // $(this).fadeIn(fadespeed);
//                     // });

//                     var content = "<div class='loaded-content'>" + data.html + "</div>";

//                     if (empty) {
//                         //loadable.fadeOut(fadespeed, function() {
//                             loadable.html(content).hide().fadeIn(fadespeed);
//                         //});
//                     } else {
                        
//                         loadable.find('.loaded-content:first').fadeOut(fadespeed, function(e) {
//                             loadable.find('.preloader-overlay').fadeOut(fadespeed, function(e) {
//                                 $(this).remove();
//                             });
//                             $(content).hide().appendTo(loadable).fadeIn(fadespeed);
//                             $(this).remove();
//                             //loadable.html(content).fadeIn(fadespeed);
//                         });
//                     }
                    
    
//                     if (data.response) {
//                         $.each(data.response, function(index, value) {
//                             $('#' + index).val(value);
//                         });

//                         if (data.response.current_menu_item_id) {
//                             $('.side-menu-items-wrapper').find('.collection-item').removeClass('active');
//                             $('.side-menu-items-wrapper').find('.collection-item[data-id="' + data.response.current_menu_item_id + '"]').addClass('active');
//                             if (current_menu_item_id != data.response.current_menu_item_id) {
//                                 current_menu_item_id = data.response.current_menu_item_id;
//                                 window.LaravelDataTables["dataTableBuilder"].draw();
//                             }
//                         }
//                     }
//                // });
//             }

//             if (btn) {
//                 btn_reset($(btn));
//             }

//             if (callback_function != null) {
//                 callback_function();
//             }
//         },
//         beforeSend: function() {
//             haight = loadable.height();
//             width = loadable.width();
//             $(loading_overlay_block).hide().appendTo(loadable).fadeIn(fadespeed);
//             //loadable.append(loading_overlay_block);
//         },
//         ajaxComplete: function () {
//           /* $(document.body).tooltip({ selector: ".tooltipped" });
//            $('ul.tabs').tabs({'swipeable':false});*/
//         }
//     });


//DISCOUNTS




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//UNIVERSAL
var manage_load_modal = function(url, container, data, dismissible = true, type = 'post', callback_function = null) {
    //$('#manage_product_modal').modal("show");
    container.html(loading_overlay_block).css({'min-height':'100px'});
    $('#manage_modal').modal({
      dismissible: dismissible, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        manage_load(url, container, data, 100, null, type, callback_function);
      },
      complete: function() { } // Callback for Modal close
    });
    $('#manage_modal').modal('open')
}

var manage_load = function(url, container, data = {}, fadespeed = 100, btn = null, type, callback_function = null) {
    var response = true;
    if (btn) {
        btn_loading($(btn));
    }
    
    $.ajax({
        url: url,
        type: type,
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }

                response = false;
                if (data.permission && !data.status) {
                    container.fadeOut(fadespeed, function (e) {
                        //$(this).css({'width':'auto', 'height':'auto'});
                        $(this).html('<div class="modal-content row">' + inline_message_template(data.message, 'error') + '</div>' +
                            '<div class="modal-footer">' +
                                '<button class="modal-action modal-close waves-effect waves-light btn-flat" id="denied_save-cancel-btn" type="button">Cancel</button>' + 
                            '</div>');
                        $(this).fadeIn(fadespeed);
                    });
                }
            } else {
                container.fadeOut(fadespeed, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(fadespeed);

                    if (data.response) {
                        $.each(data.response, function(index, value) {
                            $('#' + index).val(value);
                        });

                        if (data.response.current_menu_item_id) {
                            $('.side-menu-items-wrapper').find('.collection-item').removeClass('active pulse-s add_item');
                            $('.side-menu-items-wrapper').find('.dd-item').removeClass('active');

                            var selected_menu_item =  $('.side-menu-items-wrapper').find('.collection-item[data-id="' + data.response.current_menu_item_id + '"]');

                            selected_menu_item.addClass('active');
                            selected_menu_item.closest('.dd-item').addClass('active');

                            if (data.response.add_item_to_parent) {
                                selected_menu_item.addClass('pulse-s add_item');
                            }

                            selected_menu_item.closest('.dd-item').parents('.dd-item').removeClass('dd-collapsed');

                            if (current_menu_item_id != data.response.current_menu_item_id) {
                                current_menu_item_id = data.response.current_menu_item_id;
                                if (window.LaravelDataTables !== undefined) {
                                    window.LaravelDataTables["dataTableBuilder"].draw(false);
                                }
                            }
                        }
                    }
                });
            }

            if (btn) {
                btn_reset($(btn));
            }

            if (callback_function != null) {
                callback_function();
            }
        },
        beforeSend: function() {
            haight = container.height();
            width = container.width();
            container.append(loading_overlay_block);
        },
        ajaxComplete: function () {
          /* $(document.body).tooltip({ selector: ".tooltipped" });
           $('ul.tabs').tabs({'swipeable':false});*/
        }
    });

    return response;
}



var save_parameters = function(url, form, form_validate = {}, form_text_names_arr = {}, datatables_arr = {}, type = 'post', callback_function = null) {
    jQuery.validator.addMethod("greaterThan", 
    function(value, element, params) {

        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }

        return isNaN(value) && isNaN($(params).val()) 
            || (Number(value) > Number($(params).val())); 
    },'Выберите значение больше, чем дата начала');

    $(form).validate({
        errorElement: 'span',
        rules: form_validate.rules,
        messages: form_validate.messages,
        submitHandler: function(form) {

            var button = $(form).find('.parameters-save-btn');
            var formData = new FormData(form);
            btn_loading(button);
            $.each(form_text_names_arr, function(index, name) {
                formData.set(index, CKEDITOR.instances[name].getData());
            })
            
            $.ajax({
                url: url,
                type: type,
                //datatype: 'json',
                data: formData,
                contentType: false,
                processData: false,
                error: function(a,b,c) {
                    btn_reset(button);
                },
                success: function(data){
                    btn_reset(button);
                    if (!data.success) {
                        if (data.reload) {
                            reload_page();
                        }
                    } else {

                        if (data.status) {
                            $('#manage_modal').modal('close');
                        }
                       
                        $.each(datatables_arr, function(index, datatable) {
                            datatable.draw(false);
                        });

                        if (data.result) {
                            $('#current_edited_item_id').val(data.result.id);
                        }

                        if (callback_function != null) {
                            callback_function(data);
                        }
                    }
                    msg_text = data.message;
                    Materialize.toast(small_dialog_template(data.message, data.status), 4000, 'dialog_class-' + data.status);
                },
                beforeSend: function() {

                },
                ajaxComplete: function () {
                    btn_reset(button);
                }
            });

            return false;
        }
    });
}

var changestatus = function (item, url, redraw_tables = {}, swal_response = false, callback_function = null) {
    var response = true;
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'ids':[$(item).data('id')]},
      error: function(a,b,c) {
         Materialize.toast(small_dialog_template(c, 0), 4000);
         response = false;
      },
      success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
                response = false;
            } else {
                if (data.status == 1) {
                    $(item).addClass('active');
                } else if(data.status == 0) {
                    $(item).removeClass('active');
                }
            }
          // msg_text = data.message;
          // Materialize.toast( data.message, 4000);

            msg_text = data.message;
            Materialize.toast(small_dialog_template(data.message), 4000, 'dialog_class-1');

            if (response) {

                if (swal_response) {
                    swal(swal_response[0], swal_response[1], swal_response[2]);
                }

                $.each(redraw_tables, function( index, value ) {
                    value.draw(false);
                });
            }

            if (callback_function) {
                callback_function();
            }

        }
  });
}

var reorder_items = function(url, data, datatables = {}) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                
            }
            msg_text = data.message;
            Materialize.toast( data.message, 4000);

            $.each(datatables, function(index, datatable) {
                datatable.draw(false);
            });
        },
        beforeSend: function() {
            
        },
        ajaxComplete: function () {
           
        }
    });
}

var delete_items = function(url, item_ids, rowCount = null, redraw_tables = {}, callback_function = null) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: {'ids':item_ids},
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                //console.log(data.messages.length,data.errors.length); return;
                var response_type = data.status ? 'success' : 'warning',
                    title = data.status ? "удален" : 'ошибка',
                    text = '',
                    messages_length = Object.keys(data.messages).length,
                    errors_length = Object.keys(data.errors).length,
                    swal_class = (messages_length > 0 || errors_length > 0) ? "swal-wide" : 'swal-regular';

                if (errors_length > 0) {  
                    text = '<ul class="collapsible expandable">';
                    $.each(data.errors, function (k, v) {
                        //console.log('<div class="collapsible-body">'+v+'</div>');
                        text += '<li class="active">' + 
                          '<div class="collapsible-header active"><i class="material-icons red-text">error_outline</i><b>'+k+'</b></div>' + 
                          '<div class="collapsible-body">'+v+'</div>' + 
                        '</li>';
                    });
                    text += '</ul>';
                } /*else if (errors_length == 1) {
                    text += data.errors[Object.keys(data.errors)[0]];
                }*/

                if(messages_length){

                    if (errors_length) {
                        text += '<div style="border-bottom: 2px dashed #9e9e9e52; margin: 3rem 0;"></div><div class="swal2-icon swal2-success" style="display: block;">' + 
                        '<div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>' + 
                        '<span class="swal2-success-line-tip"></span>' + 
                        '<span class="swal2-success-line-long"></span>' + 
                        '<div class="swal2-success-ring"></div>' + 
                        '<div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>' +
                        '<div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>' +
                        '</div><h2 class="swal2-title" id="swal2-title">удален</h2>';
                    }

                    if (messages_length > 0) {
                        text += '<ul class="collapsible expandable">';

                        $.each(data.messages, function (k, v) {
                            //console.log('<div class="collapsible-body">'+v+'</div>');
                            text += '<li class="active">' + 
                              '<div class="collapsible-header active"><i class="material-icons green-text">check</i><b>'+k+'</b></div>' + 
                              '<div class="collapsible-body">'+v+'</div>' + 
                            '</li>';
                        });
                        text += '</ul>';
                    } /*else if (messages_length == 1) {
                        text += data.messages[Object.keys(data.messages)[0]];
                    }*/
                } 


                if (data.status || messages_length) {
                    $.each(redraw_tables, function( index, value ) {
                        value.draw(false);
                    });
                }

                swal({
                    title: title, 
                    html: text, 
                    type: response_type, 
                    customClass: swal_class, 
                    buttonsStyling: false, 
                    confirmButtonClass: 'btn green',
                    //timer: 1000
                }).then(function() {
                   
                   
                });

                setTimeout(function() {
                        $('.swal2-container .collapsible').collapsible({
                          accordion: false
                        });
                }, 0);
                    
            }

            // if (data.messages) {
            //     $.each(data.messages, function (k, v) {
            //         Materialize.toast(small_dialog_template(v, 1), 4000, 'dialog_class-' + 1);
            //     })
            // }

            // if (data.errors) {
            //     $.each(data.errors, function (k, v) {
            //         Materialize.toast(small_dialog_template(v, 0), 4000, 'dialog_class-' + 0);
            //     })
            // }

            if (callback_function) {
                callback_function(data);
            }
        },
        ajaxComplete: function () {
        }
    });
}

var delete_main = function(url, data, redraw_tables = {}, callback = null) {
    $.ajax({
      url: url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         Materialize.toast(small_dialog_template(c, 0), 4000, 'dialog_class-0');
      },
      success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                if (data.status == 1) {
                    swal("удаленный", data.message, "success");
                    $.each(redraw_tables, function( index, value ) {
                        value.draw(false);
                    });
                }
            }

            msg_text = data.message;
            Materialize.toast(small_dialog_template(data.message, data.status), 4000, 'dialog_class-' + data.status);

            if (callback != null) callback();
            
        },
        ajaxComplete: function () {
            //callback;
        }
    });
}

var show_page = function (item, container, url, data, fadespeed = 100) {
    var _url = url.replace("id_plc", $(item).data('id'));
    window.history.pushState("", "Title", _url);
    var container_inner = container;
    $.ajax({
      url: _url,
      type: "post",
      datatype: 'json',
      data: data,
      error: function(a,b,c) {
         Materialize.toast( c, 4000);
      },
      success: function(data){
          if (!data.success) {
              if (data.reload) {
                    reload_page();
                }
          } else {
            if (data.status == 1) {
                //item.bind('click');
                container.fadeOut(fadespeed, function (e) {
                    //$(this).css({'width':'auto', 'height':'auto'});
                    $(this).html(data.html);
                    $(this).fadeIn(fadespeed);

                    $('body').addClass('loaded');
                    $('.loader').fadeOut(10);
                });
            } else {
            }
          }
          msg_text = data.message;
          Materialize.toast( data.message, 4000);
      },
      beforeSend: function() {
            haight = container_inner.height();
            width = container_inner.width();

            //container_inner.append(loading_overlay_block_2);
            //
            $('body').removeClass('loaded');
            $('.loader').fadeIn(10);

      },
      ajaxComplete: function () {
            
      }
  });
}

var delete_image = function(url, data, callback_function = null) {
    data['action'] = 'delete';
    btn_loading($('.delete_image-btn'));
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                if (data.reload) {
                    reload_page();
                }
            } else {
                $('#image_name').val('');
                var $image = $(".image-crop > img");

                $image.prop('src', '');
                $image.cropper('destroy');
                $('#upload').val('');
                $('#file_input').val('');
                $('#remove_img_ind').val(1);
                $('#image_upload_input-wrapper').slideDown(100);
                btn_reset($('.delete_image-btn'));
                Materialize.toast( data.message, 4000);

                if (callback_function) {
                    callback_function(data);
                }
            }
        },
        beforeSend: function(e) {
        },
        complete: function (e) {
           $('.delete_image-btn').prop('disabled', true);
        }
    });
}

var upload_image = function(form, url, data, callback_function = null) {
    btn_loading($('.upload_image-btn'));
    var fd = new FormData(form);
    var $image = $(".image-crop > img");
    var image_name = null;
    pos_data = null;
    if ($('#crop-editor-status').val() == 0) {
        $image.cropper('destroy');
    } else if ($('#crop-editor-status').val() == 1) {
        pos_data = JSON.stringify($image.cropper('getData'));
    }

    fd.append('image_crop_coordinates', pos_data);
    fd.append('image', $('#upload')[0].files[0]);
    fd.append('action', 'upload');

    $.each(data, function function_name(k, v) {
        fd.append(k, v);
    })

    $.ajax({
        url: url,
        type: "post",
        //datatype: 'json',
        data: fd,
        contentType: false,
        processData: false,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                $('.delete_image-btn').prop('disabled', true);
                $('#image_name').val('');
                if (data.reload) {
                    reload_page();
                }
            } else {
                btn_reset($('.upload_image-btn'));
                $('#image_name').val(data.newfilename);
                //console.log(data.newfilename);
                $('.delete_image-btn').prop('disabled', false);
                $('#image_upload_input-wrapper').slideUp(100);
                $('#crop_status_switch-wrapper').slideUp(100);
                $(".image-crop > img").cropper('destroy');

                if (callback_function) {
                    callback_function(data);
                }
            }

            msg_text = data.message;
            Materialize.toast( data.message, 4000);
        },
        beforeSend: function() {
            $('.card-preloader-full').fadeIn(100);
            //$('.upload_product_image-btn').button('loading');
        },
        complete: function(){
            $('.card-preloader-full').fadeOut(100);
            $('.upload_image-btn').prop('disabled', true);
        },
    });

    return image_name;
}

var load_image = function(item, max_size) {
    var reader = new FileReader();
    btn_loading($('.upload_image-btn'));
    reader.onload = function (e) {
        var image = new Image();
        image.src = reader.result;
        $(".image-crop > img").prop('src', image.src);

        image.onload = function() {
            var $image = $(".image-crop > img");
            cropper1 = $image.cropper('destroy');
            cropper1.cropper({
                //aspectRatio: 16 / 9,
                preview: ".img-preview",
            });
            $('.cropper-controll-wrapper').slideUp(50);
            $('#crop-editor-status').val(1);
            $('#crop_status_switch-wrapper').slideDown(100);
            //cropper1.cropper('disable');
            cropper1.cropper('enable');
        };
    }
    if (item.files[0] && item.files[0].type.match('image.*')) {
         reader.readAsDataURL(item.files[0]);
         btn_reset($('.upload_image-btn'));
         //console.log(_this.files[0].size);
         //$('.upload_product_image-btn').prop('disabled', false);
         if(item.files[0].size > max_size) {
            $('.filesize-warning-sm-1').addClass('red-text text-darken-1');
            $('.upload_image-btn').prop('disabled', true);
         } else {
            $('.filesize-warning-sm-1').removeClass('red-text text-darken-1');
            $('.upload_image-btn').prop('disabled', false);
         }
    }
}

var items_change = function (_this, url, data = {}) {
    data['id'] = item_id = $(_this).data('id');
    
    if($(_this).hasClass('active')) {
        $('#item_options').slideDown(100);
        load_item_settings(url, item_id, data);
    } else {
        $('#item_options').slideUp(100);
    }

    
    //url = "{{route('menus_items_data-load')}}"
    
    
    //window.LaravelDataTables["dataTableBuilder"].draw();
    //e.preventDefault();
    //load_menu_item_data(url, lang_ind, $('#menu_items_data_container'), menu_item_id);
};

var clear_items_change = function (e) {
    $('.collection-item').removeClass('active');
    $('#item_options').slideUp(100);
    $('.add_role_permission-container').slideUp(100);
    if (window.LaravelDataTables !== undefined) {
        window.LaravelDataTables["dataTableBuilder"].draw(false);
    }
    
    //e.preventDefault();
};

function load_item_settings(url, id, data) {
    $.ajax({
        url: url,
        type: "post",
        datatype: 'json',
        data: data,
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                if (data.item) {
                    $.each(data.item, function (k, v) {
                        item = $('#i_'+k);
                        if (item.is(':checkbox')) {
                            if (v) {
                                item.prop('checked', true);
                            } else {
                                item.prop('checked', false);
                            }
                        } else if (item.is(':text') || item.is(':hidden')) {
                            item.val(v).focus();
                        }
                    });
                }

                if (!data.item.id) {
                    $('#i_id').val(current_menu_item_id);
                } else $('#i_id').val(data.item.id);

                if (data.select_data) {
                    $('.select-custom').html('<option value="default"></option>');
                    $('.select-custom').val('default');
                    $.each(data.select_data, function (k, v) {
                        name = v.display_name;

                        if (data.items_type != undefined) {
                            if(data.items_type == 'products') {
                                name = v.translated[0].name + (v.translated[0].short_text ? ' <i>(' + v.translated[0].short_text + ')</i>' : null);
                            } else if (data.items_type == 'permissions') {
                                name = v.display_name;
                            }
                        }

                        $('.select-custom').append('<option value="'+v.id+'">'+name+'</option>');
                    });
                    
                }
                //$('#m_i_c_description').val(data.menu.name_trans);
                /*if (data.menu.watch) {
                    $('#m_i_c_watch').prop('checked', true);
                } else $('#m_i_c_watch').prop('checked', false);

                if (data.menu.is_group) {
                    $('#m_i_c_is_group').prop('checked', true);
                } else $('#m_i_c_is_group').prop('checked', false);*/
            }
        },
        beforeSend: function() {
            
        },
        ajaxComplete: function () {
           
        }
    });
}


function change_transaction(type, id, count, order_id, domain, url) {

    if(!type || !id || !count || !order_id) return false;
    swal({
        title: "Провести операцию?",
        //text: "You will not be able to recover these discounts!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "confirm",
        cancelButtonText: "cancel",
    }).then(function () {
        var data = {'type': type, 'id': id, 'count': count, 'order_id': order_id, 'domain': domain};
        $.ajax({
            url: url,
            type: "post",
            datatype: 'json',
            data: data,
            error: function(a,b,c) {
                Materialize.toast( c, 4000);
            },
            success: function(data){
                if (!data.success) {
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000);
                    if (data.reload) {
                        reload_page();
                    }
                } else {
                    if(type==1) {
                        var text = '<i class="material-icons">check_circle</i> Транзакция была подтверждена! - '+data.transop_date;
                    } else if(type==2) {
                        var text = '<i class="material-icons">check_circle</i> Транзакция была отменена! - '+data.transop_date;
                    } else if(type==3) {
                        var text = '<i class="material-icons">check_circle</i> Деньги были возвращены! - '+data.transop_date;
                    }

                    Materialize.toast( text, 4000);
                    $('#trans_op_btn_wrapper').slideUp(100, function(e) {
                        $('.wrapper-custom-1').html(text).slideDown(100);
                    });
                    
                    // $('.bank_communication input').remove();
                    // $('.bank_communication h2').after('<input style="border:0;background: none;" class="bank_communication_button" type="submit" onclick="return false;" value="'+text+'">');
                }
            },
            beforeSend: function() {
                
            },
            ajaxComplete: function () {
               
            }
        });
    });

    return false;
}

function sticker_query(id,el) {

    $.ajax({
        url: 'orders/sticker/query',
        type: "post",
        datatype: 'json',
        data: {'id': id},
        error: function(a,b,c) {
            Materialize.toast( c, 4000);
        },
        success: function(data){
            if (!data.success) {
                msg_text = data.message;
                Materialize.toast( data.message, 4000);
                if (data.reload) {
                    reload_page();
                }
            } else {
                if(data.surl)
                {
                    //var wnd = window.open("about:blank", "", "_blank,width=810,height=650");
                    //wnd.document.write(answer.html);
                    $(el).next("a").attr("href",data.surl);
                    $(el).next("a").show();
                    $(el).hide();
                }
            }
        },
        beforeSend: function() {
            
        },
        ajaxComplete: function () {
           
        }
    });
}