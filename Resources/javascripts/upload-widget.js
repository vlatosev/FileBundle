$(document).ready(function(){
  $(".js-upload-widget").each(function(ind, widget){
    initUploadWidget($(widget));
  });
});

function initUploadWidget(widget)
{
  var button       = widget.find("input[type='file']").first(),
      namespace    = widget.find("input[name*='file_namespace']").first().val(),
      id_input     = widget.find("input[name*='file_id']").first(),
      image        = widget.find("img").first(),
      img_classes  = image.attr('class'),
      preview_type = widget.find("img").first().data('preview-type'),
      fileclass    = widget.find("input[name*='file_id']").first().data('file-class'),
      showUploadedImage = function(id, type, classes)
      {
        $.post(showPreviewImageUrl,{
          id: id,
          type: type,
          img_class: classes
        }, function(data){
          var newdat = $(data);
          image.replaceWith(newdat);
          image = widget.find("img").first();
        });
      };


  button.fileupload({
    url: ajaxUploadingURL,
    paramName: 'upload_widget[upload_file]',
    singleFileUploads: true,
    formData: {
      'upload_widget[file_namespace]': namespace,
      'upload_widget[_token]':         csrfTokenUpload,
      file_class:                      fileclass
    }
  }).on('fileuploaddone', function(ev, data){
    id_input.val(data.result.file_id);
    if(image.length == 1){
      showUploadedImage(data.result.file_id, preview_type, img_classes);
    }
  }).on('fileuploadfail', function(ev, data){
    alert('Upload failed!');
  }).on('fileuploadstart', function(ev, data){
    var loader = $('<div class="image-loading"><div class="wait-loading"></div></div>');
    image.replaceWith(loader);
    image = loader;
  });
}

