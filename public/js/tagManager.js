

/**
 * Add image from library by id
 * @param {*} id_img - id image in bdd
 * @param {*} container - id or class name which contain image
 */
function addTagImg(id_img, container)
{
    var obj = document.querySelector(container);
    var tag = "library";

    if( typeof obj.selectionStart != "undefined")
    {
        var start_position = obj.selectionStart;
        var end_position = obj.selectionEnd;
        var value = obj.value;
        var content_before = value.substring( 0 , start_position);
        var content_after = value.substring( end_position, obj.textLength );

        // Without highlight text
        if(start_position === end_position)
        {
            content_before = content_before + '[' + tag + ']' + id_img + '[/' + tag + ']';
            value = content_before + content_after;
            obj.value = value;
            // Select position after value_without_end
            obj.setSelectionRange( content_before.length, content_before.length );
            obj.focus();
        }
        // With highlight text
        else
        {
            content_before = content_before + '[' + tag + ']' + id_img + '[/' + tag + ']';
            value = content_before + content_after;
            obj.value = value;
            // Select position after value_without_end
            obj.setSelectionRange( content_before.length, content_before.length );
            obj.focus();
        }
    }
}


/**
 * Add text to focus position of value element
 * @param {string} element - id of the element
 * @param {string} txt - text to add in focus position of the element
 */
function addTag(tag, container, tag_value = '')
{
    var obj = document.querySelector(container);
    if( typeof obj.selectionStart != "undefined")
    {
        var start_position = obj.selectionStart;
        var end_position = obj.selectionEnd;
        var value = obj.value;
        var content_before = value.substring( 0 , start_position);
        var content_after = value.substring( end_position, obj.textLength );

        // Without highlight text
        if(start_position === end_position)
        {
            var value_without_end = content_before + '[' + tag + tag_value + ']';
            value = value_without_end + '[/' + tag + ']' + content_after;
            obj.value = value;
            // Select position after value_without_end
            obj.setSelectionRange( value_without_end.length, value_without_end.length );
            obj.focus();
        }
        // With highlight text
        else
        {
            var highlighted  = value.substring( start_position, end_position );
            var value_without_end = content_before + '[' + tag + tag_value + ']' + highlighted + '[/' + tag + ']';
            value = value_without_end + content_after;
            obj.value = value;
            // Select position after value_without_end
            obj.setSelectionRange( value_without_end.length, value_without_end.length );
            obj.focus();
        }
    }
}