$(function() {
  'use strict';

  //Tinymce editor
  if ($(".tinymceExample").length) {
    tinymce.init({
      selector: '.tinymceExample',
      //content_css : "<?php echo BASE_URL_SITE ?>/css/style-v5.css",
      language: 'pt_BR',
      language_url: window.location.origin+'/assets/js/langs/pt_BR.js',
        plugins: ["image", "autolink", "media", "imagetools"],
        toolbar: ['undo redo | forecolor backcolor | anchor | image | media | fontselect | fontsizeselect | forecolor | styleselect | bold italic underline | title | alignleft aligncenter alignright'],
        color_map: [
            "000000", "Black",
            "808080", "Gray",
            "FFFFFF", "White",
            "FF0000", "Red",
            "FFFF00", "Yellow",
            "008000", "Green",
            "0000FF", "Blue"
          ],
          visual: false,
        image_uploadtab: true,
        images_upload_base_path: './',
        images_upload_credentials: true,
        automatic_uploads: true,
        //image_list: "list_images.php",
        images_upload_handler: function (blobInfo, success, failure) {
          var xhr, formData;

          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', window.location.origin+'/admin/upload_img_conteudo_blog');
          
          xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
              failure('HTTP Error: ' + xhr.status);
              return;
            }

            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
              failure('Invalid JSON: ' + xhr.responseText);
              return;
            }
            console.log(json);
            success(json.location);
          };
          
    //alert(blobInfo.blob());
          formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          formData.append('_token', $('input[name="_token"]').val());

          xhr.send(formData);
        }
    });
  }
  
});