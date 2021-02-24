
const AJAX_URL = 'indexAjax.php';
const MIN_LENGTH_SEARCH = 1;

/**
 *Detect which device is in use
    * Need these HTML tags and BOOTSTRAP to be activated :
    *  <div id="users-device-size">
    *       <div id="xs" class="d-sm-none"></div>
    *       <div id="sm" class="d-md-none"></div>
    *       <div id="md" class="d-lg-none"></div>
    *       <div id="lg" class="d-xl-none"></div>
    *       <div id="xl" class=""></div>
    *   </div>
    *
    * @return {string} Return id value from divs
    */
function getBootstrapDeviceSize() 
{
    return $('#users-device-size').find('div:visible').first().attr('id');
}


$(document).ready(function() 
{

    $("#search").autocomplete({
        minLength: MIN_LENGTH_SEARCH ,
        source: function(req, response)
        {
            $.ajax({
                url: AJAX_URL,
                type : 'POST',
                timeout : 5000,
                data:{
                    search : $('#search').val()
                },
                dataType: "json",
                success: function(data, status, XHR){
                    response($.map(data, function (item) {
                        // return(item); // without rename
                        return {
                          id_user : item.id_user,
                          id_note : item.id,
                          link : item.url,
                          value : item.title,
                          alt : item.description,
                        //   log: console.log(item), 
                        }
                     }))
                },
                error: function(xhr, status, error){
                    console.log("xhr : "+xhr+" status : "+status+" error : "+error);
                },
                complete: function(xhr, status){
                    console.log("Status : "+status+"\nXHR : ");
                    console.dir(xhr);
                    // console.dir(xhr['responseText']);
                },
                statusCode : {
                    404 : function (){
                        console.log("Erreur 404");
                    }
                }
            });
        },focus: function( event, ui ) {}
    })
    
    // Auto select first element
    .autocomplete( "option", "autoFocus", true )
    .autocomplete({
        // Create input in hidden div
        select: function( event, ui ) {
            console.dir(ui.item);
            $("#hidden_idnote").html("<input name=\"id_note\" value=\""+ui.item.id_note+"\"/>");
        },
        // reset if search changing
        focus: function( event, ui ) {
            $("#hidden_idnote").html("");
        }
    })
    // Instance each elements
    .autocomplete( "instance" )._renderItem = function( ul, item) 
    {
        return $( "<li>" )
        .append( "<div><img alt=\""+item.alt+"\" src=\"public/sources/users/icons/user"+item.id_user+"/"+item.link+"\" /> " +  item.value + "</div>")
        .appendTo( ul );
    };

    
    var sizeScreen = getBootstrapDeviceSize();
    if(sizeScreen == "xs")
    {
        $("#search").autocomplete("disable");
    }


});

