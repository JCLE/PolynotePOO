
$("#img_receiver").hide();
$('#input_name').hide();
$('#input_file').show();

/**
 * Change input display
 * @param {*} input 
 */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            // $(location).attr('href', 'addCategory');
            $('#img_receiver').attr('src', e.target.result).show();
            $('#input_file').hide();
            $('#input_name').show();
            $('#category_name').focus();
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img_file").change(function(){
    readURL(this);
});