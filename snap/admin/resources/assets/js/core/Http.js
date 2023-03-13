/**************************************************/
/* AJAX                                           */
/**************************************************/
var Http = {};
Http.ajax = jQuery.ajax;
Http.post = jQuery.post;
Http.get = jQuery.get;
Http.json = jQuery.getJSON;
Http.setup = jQuery.ajaxSetup;
Http.tokenName = 'csrf_test_name';
Http.token = jQuery('meta[name="csrf-token"]').attr('content');
Http.setupData = {};
Http.setupData[Http.tokenName] = Http.token;
Http.setup({
    data: Http.setupData,
    headers: {
        'X-CSRF-TOKEN': Http.token
    }
});

module.exports = Http;