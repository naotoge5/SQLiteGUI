/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
const Table = {
    name: "input[name='table-name']",
    columns: "#Columns li"
}

const Column = {
    name: "input[name='column-name']",
    type: {
        get: "select[name='column-type']",
        set: function (value) {
            return "select[name='column-type'] option[value='" + value + "']"
        }
    },
    unique: "input[name='column-unique']:checked",
    not_null: "input[name='column-not_null']:checked",
    default: {
        flag: "input[name='column-default']:checked",
        value: "input[name='default-value']"
    },
    check: {
        flag: "input[name='column-check']:checked",
        value: "input[name='check-value']"
    },
    foreign_key: {
        flag: "input[name='column-foreign_key']:checked",
        table: "select[name='foreign_key-table']",
        column: "select[name='foreign_key-column']"
    }
}








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
        var nameColumn = $(Column.name).val();
        var typeColumn = $(Column.type).val();
        var uniqueColumn = ' ' + $(Column.unique).val();
        var not_nullColumn = ' ' + $(Column.not_null).val();
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

    $(Table.columns).click(function (e) {
        $(Column.name).val($(e.target).text());
        $(Column.type.set($(e.target).data("type"))).prop('selected', true);
    });
});