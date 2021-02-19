
/* ******************************************* */
//  THIS SCRIPT NEED tagManager.js to work
/* ******************************************* */

$(function () {
    // Display bootstrap tooltip
    $('[data-toggle="tooltip"]').tooltip();
  })

const CONTAINER = '#content';
const PREVIEW = '#preview';
const DEFAULT_VALUE_PREVIEW = '<span class="text-center">prévisualisation</span>';
const DIV_TAGS_CLASS = 'col-12 p-0 mb-1';
const BTN_CLASS = 'btn btn-light';

/**
 * Template for create html tag
 */
var templateHTML = {
    addLink : function(f_name,content, tooltip_txt = '')
    {
        var element = {
            f_name : f_name,
            content : content,
            tooltip_txt : tooltip_txt,

            displayTag : function()
            {
                var txt = '<button tabindex=-1 type="button" data-toggle="tooltip" data-delay=\'{"show":"1000", "hide":"100"}\'';
                txt += ' title="' + tooltip_txt +'"';
                txt += ' class="' + BTN_CLASS +'"';
                txt += ' onclick="' + f_name + '" >';
                txt += this.content;
                txt += '</button>';
                return txt;
            }
        }
        return element;
    },
    addCollapse : function(collapsed_name,content, tooltip_txt = '')
    {
        var element = {
            collapsed_name : collapsed_name,
            content : content,
            tooltip_txt : tooltip_txt,

            displayTag : function()
            {
                var txt = '<button tabindex=-1 data-toggle="collapse" aria-expanded="false"';
                txt += ' href="#' + collapsed_name + '" aria-controls="' + collapsed_name + '" type="button"';
                txt += ' data-toggle="tooltip" data-delay=\'{"show":"1000", "hide":"100"}\'';
                txt += ' title="' + tooltip_txt +'"';
                txt += ' class="' + BTN_CLASS +'"';
                txt += ' onclick="" >';
                txt += this.content;
                txt += '</button>';
                return txt;
            }
        }
        return element;
    },
    addModal : function(modal_name,content, tooltip_txt = '')
    {
        var element = {
            modal_name : modal_name,
            content : content,
            tooltip_txt : tooltip_txt,

            displayTag : function()
            {
                var txt = '<button tabindex=-1 data-toggle="modal"';
                txt += ' data-target=".' + modal_name + '" type="button"';
                txt += ' class="' + BTN_CLASS +'"';
                txt += ' onclick="" >';
                txt += this.content;
                txt += '</button>';
                return txt;
            }
            // displayTag : function()
            // {
            //     var txt = '<a class="d-inline-block" data-toggle="tooltip" data-delay=\'{"show":"1000", "hide":"100"}\'';
            //     txt += ' title="' + tooltip_txt +'">';
            //     txt += '<button data-toggle="modal"';
            //     txt += ' data-target=".' + modal_name + '" type="button"';
            //     txt += ' class="' + BTN_CLASS +'"';
            //     txt += ' onclick="" >';
            //     txt += this.content;
            //     txt += '</button></a>';
            //     return txt;
            // }
        }
        return element;
    },
    addSpace : function()
    {
        var element = 
        {
            displayTag : function()
            {
                return '<span class="col-1"></span>';
            }
        }
        return element;
    }
}


/**
 * Display all tags from templateHTML array
 * @param {Object[]} templateHTML - array of template html tags
 */
function displayHTMLTags(array_templateHTML)
{
    var txt = '';
    array_templateHTML.forEach(function(element){
        txt += element.displayTag() + ' ';
    });
    return txt;
}

/**
 * Call addTagToContainer from toolbox
 * @param {string} tag - tag will become like [tag][/tag]
 */
function addTagToolbox(tag,tag_value = '')
{
    addTag(tag, CONTAINER, tag_value);
}


// Get container to create HTML tags
var content = document.querySelector(CONTAINER);
var library = document.querySelector('images-library');

    // Create tags
    var btn_library = templateHTML.addCollapse('images-library',
    '<i class="fa fa-file-image-o"> Images</i>',
    'Bibliotheque d\'images');
    var btn_width = templateHTML.addLink('addTagToolbox(\'w\',\'=25\')',
    '<i class="fa fa-text-width"></i>',
    'width bootstrap');
    var btn_col = templateHTML.addLink('addTagToolbox(\'col\',\'=3\')',
    '<i class="fa fa-columns"></i>',
    'col bootstrap');
    var btn_strong = templateHTML.addLink('addTagToolbox(\'b\')',
    '<i class="fa fa-bold"></i>',
    'Gras');
    var btn_align_left = templateHTML.addLink('addTagToolbox(\'left\')',
    '<i class="fa fa-align-left"></i>',
    'Aligné à gauche');
    var btn_align_center = templateHTML.addLink('addTagToolbox(\'center\')',
    '<i class="fa fa-align-center"></i>',
    'Aligné au centre');
    var btn_align_right = templateHTML.addLink('addTagToolbox(\'right\')',
    '<i class="fa fa-align-right"></i>',
    'Aligné à droite');
    var btn_code = templateHTML.addLink('addTagToolbox(\'code\')',
    '<i class="fa fa-code"></i>',
    'Inserer code');
    var btn_img = templateHTML.addLink('addTagToolbox(\'img\')',
    '<i class="fa fa-picture-o"></i>', 
    'Inserer image externe');
    var btn_key = templateHTML.addLink('addTagToolbox(\'kbd\')',
    '<i class="fa fa-keyboard-o" ></i>', 
    'Inserer clef clavier');
    var btn_float_left = templateHTML.addLink('addTagToolbox(\'float\',\'=l\')',
    '<i class="fa fa-caret-left" ></i>', 
    'Element flottant à gauche');
    var btn_float_right = templateHTML.addLink('addTagToolbox(\'float\',\'=r\')',
    '<i class="fa fa-caret-right" ></i>', 
    'Element flottant à droite');
    var btn_strike = templateHTML.addLink('addTagToolbox(\'s\')',
    '<i class="fa fa-strikethrough"></i>',
    'Texte barré');
    var btn_sup = templateHTML.addLink('addTagToolbox(\'sup\')',
    '<i class="fa fa-superscript"></i>',
    'nombre exposant (puissance)');
    var btn_url = templateHTML.addLink('addTagToolbox(\'url\')',
    '<i class="fa fa-external-link"></i>',
    'Lien URL');
    
// var showPrev = templateHTML.addCollapse('preview',
// '<span role="button" class="ui-icon ui-icon-arrow-4-diag justify-content-center"></span>',
// 'Preview');
var space = templateHTML.addSpace();

var tags = [];
tags.push(btn_strong);
tags.push(btn_strike);
tags.push(btn_sup);
// tags.push(space);
tags.push(btn_align_left);
tags.push(btn_align_center);
tags.push(btn_align_right);
tags.push(btn_float_left);
tags.push(btn_float_right);
// tags.push(space);
tags.push(btn_code);
tags.push(btn_key);
tags.push(btn_url);
// tags.push(space);
tags.push(btn_library);
tags.push(btn_img);
// tags.push(space);
tags.push(btn_width);
tags.push(btn_col);
// tags.push(space);

// tags.push(showPrev);

content.insertAdjacentHTML('beforebegin','<div id="toolbar" class="' + DIV_TAGS_CLASS +'">' + displayHTMLTags(tags) +'</div>');
// applyPreview();



// function nl2br (str, is_xhtml) {
//     if (typeof str === 'undefined' || str === null) {
//         return '';
//     }
//     var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
//     return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
// }

// function BBCode2Html(txt)
// {
//     txt = txt.replace(/\[b\]/gi,'<b>');
//     txt = txt.replace(/\[\/b\]/gi,'</b>');
//     txt = txt.replace(/\[key\]/gi,'<kbd>');
//     txt = txt.replace(/\[\/key\]/gi,'</kbd>');
//     txt = txt.replace(/\[code\]/gi,'<code>');
//     txt = txt.replace(/\[\/code\]/gi,'</code>');
//     txt = txt.replace(/\[pre\]/gi,'<pre>');
//     txt = txt.replace(/\[\/pre\]/gi,'</pre>');
//     return txt;
// }

// function escapeHtml(text) 
// {
//     var map = {
//       '&': '&amp;',
//       '<': '&lt;',
//       '>': '&gt;',
//       '"': '&quot;',
//       "'": '&#039;'
//     };

//     return text.replace(/[&<>"']/g, function(m) { return map[m]; });
// }


// function applyPreview()
// {
//     var val = DEFAULT_VALUE_PREVIEW;
//     if(content.value.length > 0)
//     {
//         val = BBCode2Html(nl2br(content.value));
//         val = BBCode2Html(val);
//     }
//     document.querySelector(PREVIEW).innerHTML = val;
// }