/**
 * Contacts Form Api
 */

$(function () {
    $("#new_email").on("click", function (e) {
        var clone = $("#emails-wrapper .row:first-child").clone(true);

        clone.find("input[type='text']").val("");

        clone.append('<div class="col-md-2"> <a href="javascript:void(0)" onclick="$(this).parent().parent().remove();"><i class="fa fa-remove"></i></a></div>');

        $("#emails-wrapper").append(clone);
    });

    $("#new_phone").on("click", function (e) {
        var clone = $("#phones-wrapper .row:first-child").clone(true);

        clone.find("input[type='text']").val("");

        clone.append('<div class="col-md-2"> <a href="javascript:void(0)" onclick="$(this).parent().parent().remove();"><i class="fa fa-remove"></i></a></div>');

        $("#phones-wrapper").append(clone);
    });

    $("#documents").select2();
});
