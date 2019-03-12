var toggle_horizontal_animate = function (a_out, a_in, speedout = 1000, speedin = 1000, range = '300px', easing = 'swing', callback_function = null) {
    var animate_wrapper = a_out.closest('.animate-c-wrapper');
    animate_wrapper.css({'overflow': 'hidden'});

    a_out.removeClass('visible-c');
    a_in.addClass('visible-c');

  //  setTimeout(function() {

        if (a_out.hasClass('animate-left-out')) {
            a_out.animate({
                left: '-' + range,
                opacity: 0,
            }, { 
                easing: easing, 
                duration: speedout, 
                queue: false,
                complete: function() {
                    a_out.fadeOut(0).removeClass('animate-left-out').addClass('animate-right-in');
                }
            }); 
        } else if (a_out.hasClass('animate-right-out')) {
            a_out.animate({
                right: '-' + range,
                opacity: 0,
            }, { 
                easing: easing, 
                duration: speedout, 
                queue: false,
                complete: function() {
                    a_out.fadeOut(0).removeClass('animate-right-out').addClass('animate-left-in');
                }
            }); 
        }

        if (a_in.hasClass('animate-left-in')) {
            a_in.fadeIn(0).css({'right': '-' + range});
            a_in.animate({
                right: 0,
                opacity: 1,
            }, { 
                easing: easing, 
                duration: speedin, 
                queue: false,
                complete: function() {
                    a_in.removeClass('animate-left-in').addClass('animate-right-out');
                    setTimeout(function() {
                        animate_wrapper.height(a_in.height());
                    }, 200);
                    if (callback_function != null) {
                        callback_function();
                    }
                }
            });
        } else if (a_in.hasClass('animate-right-in')) {
            a_in.fadeIn(0).css({'left': '-' + range});;
            a_in.animate({
                left: 0,
                opacity: 1,
            }, { 
                easing: easing, 
                duration: speedin, 
                queue: false,
                complete: function() {
                    a_in.removeClass('animate-right-in').addClass('animate-left-out');
                    setTimeout(function() {
                        animate_wrapper.height(a_in.height());
                    }, 200);
                    if (callback_function != null) {
                        callback_function();
                    }
                }
            });
        }

   // }, 200);
}


var load_side_meun_items = function (url, callback_function = null) {
	$('#current_lang_id, #si_language_id').val(lang_ind);
    var btn = $('.side-menu-parameters-save-btn');
    var data = {'language_id': lang_ind, 'current_menu_item_id': current_menu_item_id};
    manage_load(url, $('.side-menu-items-wrapper'), data, 100, btn, 'post', callback_function);
}

var load_side_meun_nested_list = function (url, callback_function = null) {
    $('#current_lang_id, #si_language_id').val(lang_ind);
    var btn = $('.side-menu-parameters-save-btn');
    var data = {'language_id': lang_ind, 'list': nestable_list, 'current_menu_item_id': current_menu_item_id, 'nested_list_state': nested_list_state, 'add_item_to_parent_id': add_item_to_parent_id};
    manage_load(url, $('#nestable2'), data, 0, btn, 'post', callback_function);
}

var load_side_meun_data = function(menu_item, url, callback_function = null) {
    var other_items = $('.collection-item').not($(menu_item));
    other_items.removeClass('active').closest('.dd-item').removeClass('active');
    $(menu_item).toggleClass('active');

    if ($(menu_item).hasClass('active')) {
        $(menu_item).closest('.dd-item').addClass('active');
        show_item_arr_btn = true;
        current_menu_item_id = $(menu_item).data('id');
    } else {
        $(menu_item).closest('.dd-item').removeClass('active');
        show_item_arr_btn = false;
        current_menu_item_id = null;
    }

    $('#current_menu_item_id').val(current_menu_item_id);

    var data = {'language_id': $('#current_lang_id').val()};
    items_change(menu_item, url, data);

    if (callback_function) {
    	callback_function();
    }
}

var load_side_meun_data_nested = function(id, url, callback_function = null) {
    var menu_item = $('.side-menu-items-wrapper').find(".collection-item[data-id='" + id + "']");
    var other_items = $('.collection-item').not($(menu_item));
    other_items.removeClass('active').closest('.dd-item').removeClass('active');
    $(menu_item).toggleClass('active');

    if ($(menu_item).hasClass('active')) {
        $(menu_item).closest('.dd-item').addClass('active');
        show_item_arr_btn = true;
        current_menu_item_id = $(menu_item).closest('.dd-item').data('id');
    } else {
        $(menu_item).closest('.dd-item').removeClass('active');
        show_item_arr_btn = false;
        current_menu_item_id = null;
    }

    add_item_to_list = false;

    $('#current_menu_item_id').val(current_menu_item_id);
    $('#current_edited_item_id').val(current_menu_item_id);

    // var data = {'language_id': $('#current_lang_id').val()};
    // items_change(menu_item, url, data);

    if (callback_function) {
        callback_function();
    }
}

var delete_item = function(button, texts, callback_function = null) {
    var _this = $(button);
    swal.queue([{
        title: texts.title,
        text: texts.text,
        type: texts.type,
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: texts.confirm,
        cancelButtonText: texts.cancel,
        confirmButtonClass: 'btn red',
        cancelButtonClass: 'btn btn-flat',
        buttonsStyling: false,
        preConfirm: () => {
            swal.showLoading();
            var $datatable = window.LaravelDataTables !== undefined ? [window.LaravelDataTables["dataTableBuilder"]] : [];
            return new Promise(function(resolve, reject) {
                delete_items(item_delete_url, [_this.data('id')], null, $datatable, function () {
                    if (callback_function) {
                        callback_function();
                    }
                });

                // changestatus(_this, item_delete_url, $datatable, null, function () {
                //     swal({title: texts.deleted, text: texts.success_title, type: "success", buttonsStyling: false, confirmButtonClass: 'btn green'});
                //     if (callback_function) {
                //         callback_function();
                //     }
                // });
            })
        }
    }]).then(function() {
      //swal('Ajax request finished!');
    });
}

var delete_menu_item = function(button, texts, callback_function = null) {
    $(button).closest('.collection-item').addClass('ready_to-delete pulse-s');
    $(button).removeClass('hover-show');
    //$(button).closest('.dd-item').addClass('ready_to-delete pulse-s')
    var _this = $(button);
    swal.queue([{
        title: texts.title,
        text: texts.text,
        type: 'warning',
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: texts.confirm,
        cancelButtonText: texts.cancel,
        confirmButtonClass: 'btn red',
        cancelButtonClass: 'btn btn-flat',
        buttonsStyling: false,
        preConfirm: () => {
            swal.showLoading();
            m_i_id = _this.data('id');
            current_menu_item_id = null;
            show_item_arr_btn = false;
            contents_shown = 'show-list';
            var $datatable = window.LaravelDataTables !== undefined ? [window.LaravelDataTables["dataTableBuilder"]] : [];

            $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
            clear_items_change(_this);
            return new Promise(function(resolve, reject) {
                delete_main(side_menu_item_delete_url, {'id': m_i_id, 'language_id': lang_ind}, $datatable, function() {
                    manage_load(side_menu_load_url, $('#menu_items-container'), {'language_id': $('#current_lang_id').val()}, 100, null, 'post', function () {
                        if (callback_function) {
                            callback_function();
                        }
                    });

                    $('#item_edit-container').fadeOut(100, function(e) {
                        $(this).html('');
                        $('#menu_item_data-container').fadeIn(100);
                        $('#current_edited_item_id').val('');
                    });

                    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
                    contents_shown = 'show-list';

                    $('#item_options').slideUp(100);
                    swal({title: texts.deleted, text: texts.success_title, type: "success", buttonsStyling: false, confirmButtonClass: 'btn green'});

                    
                })
            });
        }
    }]).then(function() {
        $(button).addClass('hover-show');
        $(button).closest('.collection-item').removeClass('ready_to-delete pulse-s')
      //swal('Ajax request finished!');
    });
}

var delete_menu_item_nested = function(button, texts, callback_function = null) {
    $(button).removeClass('hover-show').addClass('pulse');
    $(button).closest('.dd-item').addClass('ready_to-delete pulse-s')
    var _this = $(button);

    swal.queue([{
        title: texts.title,
        text: texts.text,
        type: 'warning',
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: texts.confirm,
        cancelButtonText: texts.cancel,
        confirmButtonClass: 'btn red',
        cancelButtonClass: 'btn btn-flat',
        buttonsStyling: false,
        preConfirm: () => {
            m_i_id = _this.data('id');
            current_menu_item_id = null;
            show_item_arr_btn = false;
            contents_shown = 'show-list';

            $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
            clear_items_change(_this);

            return new Promise(function(resolve, reject) {
                delete_main(item_delete_url, {'ids': [m_i_id], 'language_id': lang_ind}, [], function() {
                    $('#nestable2').nestable('destroy');
                    load_pages_type_data_inline();

                    return_to_list_nested($(button), function() {

                    });

                    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
                    contents_shown = 'show-list';
                    
                    swal({title: texts.deleted, text: texts.success_title, type: "success", buttonsStyling: false, confirmButtonClass: 'btn green'});

                    

                })
            });
        }
    }]).then(function() {
        $(button).addClass('hover-show').removeClass('pulse');
        $(button).closest('.dd-item').removeClass('ready_to-delete pulse-s')
      //swal('Ajax request finished!');
    });
}

var language_switch = function(button, callback_function = null) {
    lang_ind = $(button).data('langid');
    $('#current_lang_id, #si_language_id').val(lang_ind);

    current_menu_item_id = $('#current_menu_item_id').val();
    show_item_arr_btn = false;

    if (window.LaravelDataTables !== undefined) {
        delay(function(){
          window.LaravelDataTables["dataTableBuilder"].draw();
        }, 200 );
    }

    if (current_menu_item_id) {
        show_item_arr_btn = true;
        menu = $('.collection-item[data-id="'+current_menu_item_id+'"]');
        data = {'language_id': $('#current_lang_id').val()}
        items_change(menu, side_menu_load_settings_url, data);

        if ($('.no-m-t.no-m-b').hasClass('show-edit')) {
            manage_item(item_edit_url, $('#current_edited_item_id').val(), $('#current_lang_id').val());
        }
    }

    if (callback_function != null) {
        callback_function();
    }
}

var language_switch_nested = function(button, callback_function = null) {
    lang_ind = $(button).data('langid');
    $('#current_lang_id, #si_language_id').val(lang_ind);

    current_menu_item_id = $('#current_edited_item_id').val();
    show_item_arr_btn = false;

    if (window.LaravelDataTables !== undefined) {
        delay(function(){
          window.LaravelDataTables["dataTableBuilder"].draw();
        }, 200 );
    }

    $('#nestable2').nestable('destroy');
    load_pages_type_data_inline()

    if (current_menu_item_id) {
        show_item_arr_btn = true;
        menu = $('.collection-item[data-id="'+current_menu_item_id+'"]');
        data = {'language_id': $('#current_lang_id').val()}
      //  items_change(menu, side_menu_load_settings_url, data);

        if ($('.no-m-t.no-m-b').hasClass('show-edit')) {
            manage_item(item_edit_url, $('#current_edited_item_id').val(), $('#current_lang_id').val());
        }
    }

    if (callback_function != null) {
        callback_function();
    }
}

var menu_item_switch = function(button, callback_function = null) {
    load_side_meun_data($(button), side_menu_load_settings_url, function() {
        if (window.LaravelDataTables !== undefined) {
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 100 );
        }

        if (callback_function) {
            callback_function();
        }
    });

    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
    contents_shown = 'show-list';

    var animate_in = $('#menu_item_data-container.animate-c');
    var animate_out = $('#item_data-container.animate-c');

   // console.log($('#current_edited_item_id').val());

    if ( $('#current_edited_item_id').val() || 1) {
        toggle_horizontal_animate(animate_out, animate_in, 300, 300, '10%', 'swing', function () {
            // $('#item_options').slideDown(100);
            // load_item_settings(side_menu_load_settings_url, current_menu_item_id, {'language_id': $('#current_lang_id').val(), 'id': current_menu_item_id});
        });
    }

    $('#current_edited_item_id').val('');

}

var menu_item_switch_nested = function(id, callback_function = null) {
    load_side_meun_data_nested(id, item_edit_url, function() {
        if (current_menu_item_id) {
            item_edit_nested(id, function() {

            });
        } else {
            return_to_list_nested(id, function() {

            });
        }
    });
}

var menu_item_add = function(button, callback_function = null) {
    var data = {'language_id': lang_ind};
    var container = $('#manage_modal > .container-2');
    manage_load_modal(side_menu_item_edit_url, container, data, false, 'post', function () {
        if (callback_function) {
            callback_function();
        }
    });
}

var menu_item_add_nested = function(button, callback_function = null) {
    var data = {'language_id': lang_ind, 'id': $(button).data('id')};
    var container = $('#manage_modal > .container-2');
    manage_load_modal(side_menu_item_edit_url, container, data, false, 'post', function () {
        if (callback_function) {
            callback_function();
        }
    });
}

var item_edit = function(button, callback_function = null) {
    var id = $(button).data('id');
    manage_item(item_edit_url, id, $('#current_lang_id').val());
    manage_load(load_gallery_data, $('#gallery_items-container'), {'menu_item_name': gallery_items_type_name}, 100, null, 'post', function () {
        if (callback_function) {
            callback_function();
        }
    });
}

var manage_item = function (url, id = null, language_id = null) {
    

    $('#current_edited_item_id').val(id);
    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-edit');
    contents_shown = 'show-edit';

    var container = $('#item_edit-container');
    var data = {'current_menu_item_id': current_menu_item_id, 'id': id, 'language_id': lang_ind};

    $(loading_overlay_block).hide().appendTo('.animate-c-wrapper').fadeIn(300);

    manage_load(url, container, data, 0, null, 'post', function() {

        var animate_wrapper = $('#menu_item_data-container').closest('.animate-c-wrapper');
        var animate_out = $('#menu_item_data-container.animate-c.animate-left-out');
        var animate_in = $('#item_data-container.animate-c.animate-left-in');

        animate_in.css({'opacity': 0, 'right': '-300px'});

        $('.animate-c-wrapper').find('.preloader-overlay').fadeOut(300, function (e) {
            $('.animate-c-wrapper').remove('.preloader-overlay');
        });

        toggle_horizontal_animate(animate_out, animate_in, 300, 750, '10%', 'swing', function () {
            //window.dispatchEvent(new Event('resize'));
            $('#item_options').slideDown(100).find('label.input-label-1').addClass('active');
            load_item_settings(side_menu_load_settings_url, current_menu_item_id, {'language_id': $('#current_lang_id').val(), 'id': current_menu_item_id});
        });
    });
};

var item_edit_nested = function(id, callback_function = null) {
    manage_item_nested(item_edit_url, id, $('#current_lang_id').val());
    manage_load(load_gallery_data, $('#gallery_items-container'), {'menu_item_name': gallery_items_type_name}, 100, null, 'post', function () {
        if (callback_function) {
            callback_function();
        }
    });
}

var manage_item_nested = function (url, id = null, language_id = null, parent_id = null, button = null) {
    
    $('#current_edited_item_id').val(id);
    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-edit');
    contents_shown = 'show-edit';

    if (parent_id) {
        add_item_to_parent_id = parent_id;
        current_menu_item_id = parent_id;
    } else {

        add_item_to_parent_id = false;

        if(!id) {
            $('#current_menu_item_id').val('');
            $('#current_edited_item_id').val('');
            current_menu_item_id = null;
            add_item_to_list = true;

            $('.side-menu-items-wrapper').find('.collection-item').removeClass('active pulse-s add_item');
            $('.side-menu-items-wrapper').find('.dd-item').removeClass('active');

        } 
    }

    trigger_manage_load(url, id, parent_id);
    
};

var trigger_manage_load = function (url, id = null, parent_id = null) {
    var container = $('#item_edit-container');
    var data = {'id': $('#current_edited_item_id').val(), 'language_id': lang_ind, 'parent_id': parent_id};

    $(loading_overlay_block).hide().appendTo('.animate-c-wrapper').fadeIn(300);

    manage_load(url, container, data, 0, null, 'post', function() {

        var animate_wrapper = $('#menu_item_data-container').closest('.animate-c-wrapper');
        var animate_out = $('#menu_item_data-container.animate-c.animate-left-out');
        var animate_in = $('#item_data-container.animate-c.animate-left-in');
        animate_in.css({'opacity': 0, 'right': '-300px'});

        $('.animate-c-wrapper').find('.preloader-overlay').fadeOut(300, function (e) {
            $('.animate-c-wrapper').remove('.preloader-overlay');
        });

        toggle_horizontal_animate(animate_out, animate_in, 300, 750, '10%', 'swing', function () {
            
            //window.dispatchEvent(new Event('resize'));
            //$('#item_options').slideDown(100);
            //load_item_settings(side_menu_load_settings_url, current_menu_item_id, {'language_id': $('#current_lang_id').val(), 'id': current_menu_item_id});
        });
    });
}

var return_to_list = function(button, callback_function = null) {
    var animate_in = $('#menu_item_data-container.animate-c');
    var animate_out = $('#item_data-container.animate-c');

    //alert('here')

    if ( $('#current_edited_item_id').val() || 1) {
        toggle_horizontal_animate(animate_out, animate_in, 300, 300, '10%', 'swing', function () {});
    }

    $('#current_edited_item_id').val('');

    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
    contents_shown = 'show-list';

    if (callback_function) {
        callback_function();
    }
}

var return_to_list_nested = function(id, callback_function = null) {
    var animate_in = $('#menu_item_data-container.animate-c');
    var animate_out = $('#item_data-container.animate-c');

   // alert(add_item_to_list);

    //if ( $('#current_menu_item_id').val() || add_item_to_list) {
        toggle_horizontal_animate(animate_out, animate_in, 300, 300, '10%', 'swing', function () {
            add_item_to_list = false;
        });

        $('.animate-c-wrapper').height('75vh');

   // }

    $('#current_menu_item_id').val('');
    $('#current_edited_item_id').val('');
    add_item_to_parent = false;

    $('.side-menu-items-wrapper').find('.collection-item').removeClass('active pulse-s add_item');
    $('.side-menu-items-wrapper').find('.dd-item').removeClass('active');
    //$('.side-menu-items-wrapper').find('.dd-item.nested-list').addClass('dd-collapsed');

    $('.no-m-t.no-m-b').removeClass(contents_shown).addClass('show-list');
    contents_shown = 'show-list';


    if (callback_function) {
        callback_function();
    }
}

var clear_menu_data = function(button, callback_function = null) {
    current_menu_item_id = null;
    $('#current_menu_item_id').val(current_menu_item_id);
    clear_items_change(button);
    load_side_meun_items(side_menu_load_url);
    show_item_arr_btn = false;

    if (callback_function) {
        callback_function();
    }
}

var datatables_init_simple = function () {
   // $('.dataTable').addClass('responsive-table');
    $('.dataTables_length select').addClass('browser-default');
    $('.dataTables_filter input[type=search]').addClass('expand-search');
    $('.card-options').fadeOut(0);

    window.LaravelDataTables["dataTableBuilder"].on( 'row-reorder', function ( e, diff, edit ) {
        var order_arr = {};
        var ien;
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            //$(diff[i].node).addClass("reordered");
            //console.log(diff[i]);
            //order_arr.push({'old_o': diff[i].oldData, 'new_o': diff[i].newData});
            order_arr[diff[i].oldData] = diff[i].newData;
        }
        if (ien > 1) {

            reorder_items(item_reorder_url, {'current_menu_item_id': current_menu_item_id, 'diff': order_arr}, [ window.LaravelDataTables["dataTableBuilder"] ]);
        }
        
        //window.LaravelDataTables["dataTableBuilder"].draw();
    } );

    window.LaravelDataTables["dataTableBuilder"].on( 'processing.dt', function ( e, settings, processing ) {
       // console.log(this)
        var wrapper = $(this).closest('.collapsible-body.table-holder-1');
        if (processing) {
            $(this).closest('.dataTables_wrapper').addClass('svg-blur-1');
            if (wrapper.find('.preloader-full').length == 0) {
                $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
            }
            
        } else {
            $(this).closest('.dataTables_wrapper').removeClass('svg-blur-1');
            wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
        }
    } );

    $(".dataTables_filter input").unbind() // Unbind previous default bindings
    .bind("input", function(e) { // Bind our desired behavior
        // If the length is 3 or more characters, or the user pressed ENTER, search
        if(this.value.length >= 1 || e.keyCode == 13) {
            // Call the API search function
            search_input = this.value;
            window.LaravelDataTables["dataTableBuilder"].search(search_input);
        }
        // Ensure we clear the search if they backspace far enough
        if(this.value == "") {
            search_input = "";
            window.LaravelDataTables["dataTableBuilder"].search('');
        }
        window.LaravelDataTables["dataTableBuilder"].draw();
        return;
    });
}

var masonry_update = function () {     
    var $grid = $('.grid');
    $grid.imagesLoaded().done(function(){
        $grid.masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-item',
            percentPosition: true,
            gutter: 0
        });
    });

} 

var delay = (function(){
    var timer = 50;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

var animation_resize = function () {
    //var elements = $('.animate-c');
    new ResizeSensor($('.animate-c'), function(){ 
        var active_child = $('.animate-c-wrapper').find('.animate-c.visible-c');
        var active_child_height = active_child.height();
        var active_child_width = active_child.width();
        $('.animate-c-wrapper').css({'height': active_child_height}); 
    });
};

var nested_list_state_change = function(button, callback_function = null) {

    var all_nested = $('.side-menu-items-wrapper').find('.dd-item.nested-list');
    if ($(button).hasClass('state-passive')) {
        nested_list_state = true;
        $(button).removeClass('state-passive').addClass('state-active');
        all_nested.removeClass('dd-collapsed');
    } else if ($(button).hasClass('state-active')) {
        nested_list_state = false;
        $(button).removeClass('state-active').addClass('state-passive');
        all_nested.addClass('dd-collapsed');
    }

    if (callback_function) {
        callback_function();
    }
};


(function(window,$){

    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    $(document).on('click', '.side-slidable-toggle-btn', function (e) {
        var wrapper = $(this).closest('.side-slidabl-wrapper');
        if (wrapper.hasClass('opened-1')) {
            wrapper.addClass('closed-1').removeClass('opened-1');
            wrapper.parent('.side-holder-next').find('.side-content-wrapper-1').removeClass('col l9 m8 s12').addClass('col l12 m12 s12');
            wrapper.find('button.btn').addClass('btn-floating').removeClass('btn-flat');
        } else {
            wrapper.addClass('opened-1').removeClass('closed-1');
            wrapper.parent('.side-holder-next').find('.side-content-wrapper-1').removeClass('col l12 m12 s12').addClass('col l9 m8 s12');
            wrapper.find('button.btn').removeClass('btn-floating tooltipped').addClass('btn-flat');
        }
    }); 


    $(document).ready(function (e) {

        $('#item_options .collapsible-header').on('click', function(e) {
            $('#item_options').find('label.input-label-1').addClass('active');
        });

        animation_resize();
        // var child_height = $('.animate-c-wrapper').find('.animate-c:visible').height();
        // $('.animate-c-wrapper').css("height", child_height); 
    }); 


   

})(window,jQuery);