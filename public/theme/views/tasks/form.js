/**
 * Contacts Form Api
 */

$(function () {

    $("#documents").select2();

    $("#start_date, #end_date, #complete_date").datepicker();
    
    $("#contact_type").on("change", function (e) {

        $("#contact_id").html("");

        if($(this).val() == "")
            return;

        var selected_contact_id = $("#contact_id").attr("data-selected-value")!=""?$("#contact_id").attr("data-selected-value"):"";

        getContacts($(this).val(), selected_contact_id);
    });

    if($("#contact_type").val() != "") {

        $("#contact_type").trigger("change");
    }
});


/**
 * get Contacts
 *
 *
 * @param status
 * @param selected_contact_id
 */
var getContacts = function (status, selected_contact_id)
{
    $.ajax({
        url: $("#getContactsAjaxUrl").val(),
        data: {status: status},
        method: "GET",
        dataType: "json",
        success: function (response) {
            if(response.length > 0) {
                for(var i=0; i<response.length; i++) {
                    $("#contact_id").append('<option value="'+ response[i].id +'" ' + (selected_contact_id == response[i].id?'selected':'') + '>'+ response[i].first_name + (response[i].middle_name != null?' ' + response[i].middle_name + ' ':' ') + (response[i].last_name!=null? response[i].last_name:'') + '</option>');
                }
            }
        }
    });
};