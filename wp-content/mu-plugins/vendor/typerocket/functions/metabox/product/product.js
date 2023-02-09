function productMetabox() {

    let adminAmount = document.getElementsByName('product_shareholder_admin_user');
    adminAmount.addEventListener('change', 'test');

    function test() {
        console.log('change');
    }
    // let photographerAmount = ;
    // let graphicerAmount = ;

    jQuery.ajax({
        url: product_metabox_ajax_localize_obj.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'product_metabox_ajax_handle',
            submitted_nonce: product_metabox_ajax_localize_obj.the_nonce,
            toJobUserID: toJobUserID,
            toJobJobID: toJobJobID,
            toJobContent: toJobContent,
        },
        success: function (response) {
            // alert(response.data.success);
            console.log(response);
        },
        error: function (response) {
            alert('Error retrieving the information: ' + response.status + ' ' + response.statusText);
            console.log(response);
        }
    });

}