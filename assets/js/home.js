import { DB } from './module.js';
$(function () {
    $("#Add").click(function () {
        $('.modal').fadeIn();
        return false;
    });
    $('.modal-close').on('click', function () {
        $('.modal').fadeOut();
        return false;
    });
    $("#Create").on('click', function () {

    });
    var checked = false;
    $("input[name='uniques']").click(function () {
        if ($(this).val() == checked) {
            $(this).prop('checked', false);
            checked = false;
        } else {
            checked = $(this).val();
        }
    });

    $("select[name='foreign-table']").change(function (e) {
        var table = $(this).val();
        var deferred = DB.columns(table);
        deferred.done(function (data) {
            $("select[name='foreign-column']").empty();
            $("select[name='foreign-column']").append("<option selected disabled>Column</option>");
            data.forEach(tmp => {
                $("select[name='foreign-column']").append("<option>" + tmp + "</option>");
            });
        });
    });
});