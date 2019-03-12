<div class="" >
    <ul class="collapsible" data-collapsible="expandable" style="margin: 0;">
        <li class="active" id="item_options" style="display: none;">
            <div class="collapsible-header active"><i class="material-icons">settings_applications</i>{{ trans('main.gallery.items.collection_headers.settings') }}</div>
            <div class="collapsible-body">
                <form id="edit_menu_item_settings" class="edit_menu_item-form">
                    <input type="hidden" name="id" id="i_id" value="">
                    <div class="col l12 m12 s12">
                        <div class="col l4 m6 s12">
                            <div class="input-field">
                                <input id="i_title" type="text" class="validate" name="title" value="" >
                                <label for="i_title">{{ trans('main.gallery.items.settings.title') }}</label>
                            </div>
                        </div>
                        <div class="col l4 m6 s12">
                            <div class="input-field">
                                <span class="input-wrapper-span-1">
                                    <input type="checkbox" id="i_status" name="status">
                                    <label for="i_status">{{ trans('main.gallery.items.settings.status') }}</label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="row">
                        <div class="col l12"> 
                            <div class="input-field col s12">
                                <button class="waves-effect waves-light btn pull-left btn-custom-width parameters-save-btn" id="edit_menu_item_settings-save-btn" type="submit" data-reset="<i class='material-icons'>check</i> {{trans('main.buttons.save')}}"><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                            </div>
                        </div>      
                    </div>
                    <div class="clear"></div>
                </form>
                <!-- Modal Structure -->
                <div id="modal1" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="input-field col s12">
                            <span>{{ trans('main.gallery.products.settings.text') }}</span>
                            <div id="i_description_wrapper">
                                <textarea id="i_description" class="materialize-textarea" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">{{trans('main.buttons.save')}}</a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header active grid-data"><i class="material-icons">view_list</i>{{ trans('main.gallery.items.collection_headers.items') }}</div>
            <div class="collapsible-body table-holder-1" style="padding:20px;">
                <div class="row">

                    <div class="col s12">
                        <div class="page-title">{{ trans('main.image_upload.title') }}</div>
                    </div>
                    <div class="col s12">
                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn gallery_item-add-btn custom-btn-5 large custom-color-1 squere waves-effect waves-cc-1 btn btn-custom-width shadow-1-1-h custom-toolbar-btn-1" style="display: none;"><i class="material-icons">library_add</i> {{trans('main.misc.attach_image')}}</button>
                                <button type="button" class="btn-flat custom-btn-toolbar-1 waves-effect waves-cc-1 tooltipped right" id="gallery-items-refresh" data-position="top" data-delay="50" data-tooltip="{{trans('main.misc.refresh')}}"><i class="material-icons">refresh</i></button>
                                <button type="button" class="btn-flat custom-btn-toolbar-1 waves-effect waves-cc-1 tooltipped right" id="gallery-items-select" data-position="top" data-delay="50" data-tooltip="{{trans('main.misc.select')}}"><i class="material-icons">select_all</i></button>

                            </div>
                            <div class="col s12">
                                <div class="row wrapper-custom-3 images-wrapper-2" style="margin: 1rem 0;">
                                    <div class="loading-block" id="items-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </li>
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('.custom-btn-toolbar-1.tooltipped').tooltip({delay: 50});
        var current_menu_item_id = $('#i_id').val();
        $(document).off('click', '#attach_images-btn').on('click', '#add_images-btn', function (e) {
            e.preventDefault();
            var container = $('#manage_modal > .container-2');
            manage_load_modal("{{route('gallery-attach_image_form')}}", container, {'current_menu_item_id': current_menu_item_id}, true, 'post', function() {
                //$('#load_images_from_gallery-btn').click();
            });
        });
    })
    
</script>

