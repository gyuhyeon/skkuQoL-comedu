/**
 * @file   modules/contact/tpl/js/contact_admin.js
 * @author NHN (developers@xpressengine.com)
 * @brief  contact module template javascript
 **/

function doDeleteExtraKey(module_srl, var_idx) {
	var params = {
		module_srl : module_srl,
		var_idx    : var_idx
	}
	exec_xml('contact', 'procContactAdminDeleteExtraVar', params, function() { location.reload() });
}

function moveVar(type, module_srl, var_idx) {
    var params = {
		type       : type,
		module_srl : module_srl,
		var_idx    : var_idx
	};
    var response_tags = ['error','message'];
    exec_xml('document','procDocumentAdminMoveExtraVar', params, function() { location.reload() });
}

/* mass configuration*/
function doCartSetup(url) {
    var module_srl = new Array();
    jQuery('#fo_list input[name=cart]:checked').each(function() {
        module_srl[module_srl.length] = jQuery(this).val();
    });

    if(module_srl.length<1) return;

    url += "&module_srls="+module_srl.join(',');
    popopen(url,'modulesSetup');
}


