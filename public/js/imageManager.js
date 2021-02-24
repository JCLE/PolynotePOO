
const AJAX_URL = 'imgAjax.php';

$(document).ready(function() 
{

    $('#img_file').change(function(e){
        
        files = e.target.files[0];
        // files = $('#img_file').prop('files')[0];

        // console.dir(files);
        var formData = new FormData();
        formData.append("file", files);
        // console.dir(formData);

        $.ajax({
            url: AJAX_URL,
            type : 'post',
            cache: false,
            contentType: false,
            processData: false,
            data : formData,
            dataType: "json",

            success: function(data, status, XHR){
                console.log('data : '+ data['id_user']);
                $("#img-library").append('<div class="border col-12 '
                +' col-sm-5 col-md-3 col-lg-2 align-middle">'
                +' <button type="button" class="col-12 p-0 mb-1"'
                +' onclick="addTagImg('+ data['id_image'] +', \'#content\')">'
                +' <img class="img-thumbnail align-self-center" '
                +' src="public/sources/users/images/user'+ data['id_user'] +'/'+ data['url'] +'"'
                +' alt="'+ data['desc'] +'"/></button></div>');
            }
          });

    });

});

