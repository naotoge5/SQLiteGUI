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
        //var nameTable = $("input[name='table-name']").val();
        var nameColumn = $("input[name='column-name']").val();
        var typeColumn = $("select[name='column-type']").val();
        var uniqueColumn = ' ' + $("input[name='column-unique']:checked").val();
        var not_nullColumn = ' ' + $("input[name='column-not_null']:checked").val();
        var rowQuery = nameColumn + ' ' + typeColumn + uniqueColumn + not_nullColumn;
        console.log(rowQuery);
    });
    var checked = false;
    $("input[name='column-unique']").click(function () {
        if ($(this).val() == checked) {
            $(this).prop('checked', false);
            checked = false;
        } else {
            checked = $(this).val();
        }
    });
    $("#Delete").submit(function (e) {

        var val = $("input[name='table-name']").val();

        var flag = confirm('DROP TABLE ' + val + '?');

        if (!flag) return false;

    });

    $("select[name='foreign_key-table']").change(function (e) {
        var table = $(this).val();
        setForeign_keyColumn(table);
    });

    function setForeign_keyColumn(table) {
        var deferred = DB.columns(table);
        deferred.done(function (data) {
            $("select[name='foreign_key-column']").empty();
            $("select[name='foreign_key-column']").append("<option selected disabled>Column</option>");
            data.forEach(tmp => {
                $("select[name='foreign_key-column']").append("<option>" + tmp + "</option>");
            });
        });
    }
});