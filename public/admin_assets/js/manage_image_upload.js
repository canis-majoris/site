$('#image-crop-on-btn').on('click', function() {
    $image.cropper({
        //aspectRatio: 16 / 9,
        preview: ".img-preview",
    });

    $('#crop-editor-status').val(1);
});

$('#image-crop-off-btn').on('click', function() {
    $(".image-crop > img").cropper('destroy');
    $('#crop-editor-status').val(0);

});

/*var $image = $(".image-crop > img");

$image.cropper({
    aspectRatio: "free",
    preview: ".img-preview"
});

$("#zoomIn").click(function() {
    $image.cropper('zoom', 0.1);
});

$("#zoomOut").click(function() {
    $image.cropper('zoom', -0.1);
});

$("#rotateLeft").click(function() {
    $image.cropper('rotate', 45);
});

$("#rotateRight").click(function() {
    $image.cropper('rotate', -45);
});

$("#clear").click(function() {
    $image.cropper('clear');
});

var $replaceWith = $('#replaceWith');
$('#replace').click(function () {
  $image.cropper('replace', $replaceWith.val());
});*/

var $image = $(".image-crop > img");

$(document).on('click change', '#upload', function () { 
    load_image(this, 5242880);
});

$(document).off('click change', '#crop_status_switch').on('click change', '#crop_status_switch', function(e) {
    var status = $(this).is(':checked') ? true : false;
    if (!status) {
        $(".image-crop > img").cropper('destroy');
        $('#crop-editor-status').val(0);
    } else {
        $(".image-crop > img").cropper({
            //aspectRatio: 16 / 9,
            preview: ".img-preview",
        });
        $(".image-crop > img").cropper('enable');
        $('#crop-editor-status').val(1);

    }
});
