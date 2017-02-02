/**
 * Created by kimtajo on 2015-06-23.
 */

function bpopup_load_text(){
    jQuery('#layer_popup_text').bPopup({
        modalClose: false
    });
}
function bpopup_load_image(load_url, move_url, move_target){
    jQuery('#layer_popup_image').bPopup({
        modalClose: false,
        content:'image',
        contentContainer:'.layer_popup_image_content',
        loadUrl:load_url,
        moveUrl:move_url,
        moveTarget: move_target
    });
}

function bpopup_load_iframe(load_url){
    jQuery('#layer_popup_iframe').bPopup({
        modalClose: false,
        content:'iframe',
        contentContainer:'.layer_popup_iframe_content',
        loadUrl:load_url
    });
}

