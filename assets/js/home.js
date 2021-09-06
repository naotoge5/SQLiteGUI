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
    columns: "#Columns tbody tr"
}

const Column = {
    name: {
        get: function () {
            return $("input[name='column-name']").val();
        },
        set: function (value) {
            $("input[name='column-name']").val(value);
        }
    },
    type: {
        get: "select[name='column-type']",
        set: function (value = null) {
            switch (value) {
                case 'TEXT':
                case 'text':
                    value = 'text';
                    break;
                case 'INTEGER':
                case 'integer':
                    value = 'integer';
                    break;
                case 'FLOAT':
                case 'float':
                    value = 'float';
                    break;
                case 'REAL':
                case 'real':
                    value = 'real';
                    break;
                case 'BLOB':
                case 'blob':
                    value = 'blob';
                    break;
                case 'NUMERIC':
                case 'numeric':
                    value = 'numeric';
                    break;
                case 'DATE':
                case 'date':
                    value = 'date';
                    break;
                case 'TIME':
                case 'time':
                    value = 'time';
                    break;
                case 'DATETIME':
                case 'datetime':
                    value = 'datetime';
                    break;
                case 'BOOLEAN':
                case 'boolean':
                    value = 'boolean';
                    break;
                case null:
                    $("select[name='column-type'] option[value='none']").prop('selected', true);
                    return;
                default:
                    break;
            }
            $("select[name='column-type'] option[value='" + value + "']").prop('selected', true);
        }
    },
    primary_key: {
        get: "input[name='column-primary_key']:checked",
        set: function (flag) {
            $("input[name='column-primary_key']").prop('checked', flag);
        }
    },
    unique: {
        get: "input[name='column-unique']:checked",
        set: function (flag) {
            $("input[name='column-unique']").prop('checked', flag);
        }
    },
    not_null: {
        get: "input[name='column-not_null']:checked",
        set: function (flag) {
            $("input[name='column-not_null']").prop('checked', flag);
        }
    },
    default: {
        get: function () {
            var result = false;
        },
        set: function (value) {
            if (value !== '') {
                $("input[name='column-default']").prop('checked', true);
                $("input[name='default-value']").val(value);
            } else {
                $("input[name='column-default']").prop('checked', false);
                $("input[name='default-value']").val('');
            }
        }
    },
    check: {
        get: function () {
            var result = false;
        },
        set: function (value) {
            if (value !== '') {
                $("input[name='column-check']").prop('checked', true);
                $("input[name='check-value']").val(value);
            } else {
                $("input[name='column-check']").prop('checked', false);
                $("input[name='check-value']").val('');
            }
        }
    },
    foreign_key: {
        get: function () {
            var result = false;
        },
        set: function (table, column = null) {
            if (table !== '') {
                $("input[name='column-foreign_key']").prop('checked', true);
                $("select[name='foreign_key-table'] option[value='" + table + "']").prop('selected', true);
                setForeign_keyColumn(table, column);
            } else {
                $("input[name='column-foreign_key']").prop('checked', false);
                $("select[name='foreign_key-table'] option[value='table']").prop('selected', true);
                $("select[name='foreign_key-column']").empty();
                $("select[name='foreign_key-column']").append("<option selected disabled>Column</option>");
            }
        }
    },
    All: {
        clear: function () {
            Column.name.set('');
            Column.type.set();
            Column.primary_key.set(false);
            Column.unique.set(false);
            Column.not_null.set(false);
            Column.default.set('');
            Column.check.set('');
            Column.foreign_key.set('');
        }
    }
}

function setForeign_keyColumn(table, column = '') {
    var deferred = DB.columns(table);
    deferred.done(function (data) {
        $("select[name='foreign_key-column']").empty();
        $("select[name='foreign_key-column']").append("<option selected disabled>Column</option>");
        data.forEach(tmp => {
            if (tmp === column) {
                $("select[name='foreign_key-column']").append("<option value='" + tmp + "' selected>" + tmp + "</option>");
            } else {
                $("select[name='foreign_key-column']").append("<option value='" + tmp + "'>" + tmp + "</option>");
            }
        });
    });
}

import { DB } from './module.js';
$(function () {
    $('#Columns .j__modal-close').on('click', function () {
        $('#Columns .__modal').fadeOut();
        return false;
    });
    $("#Columns .j__create").on('click', function () {
        //var nameTable = $("input[name='table-name']").val();
        var nameColumn = $(Column.name).val();
        var typeColumn = $(Column.type).val();
        var uniqueColumn = ' ' + $(Column.unique).val();
        var not_nullColumn = ' ' + $(Column.not_null).val();
        var rowQuery = nameColumn + ' ' + typeColumn + uniqueColumn + not_nullColumn;
        console.log(rowQuery);
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

    $("#Columns .j__add").click(function () {
        $("#Columns .__modal .Box-title").text('New Column');
        $("#Columns .__modal .j__create").text('Create Column');
        Column.All.clear();
        $('#Columns .__modal').fadeIn();
        return false;
    });

    $(Table.columns).click(function () {
        Column.name.set($(this).children().eq(0).text());
        Column.type.set($(this).children().eq(1).text());
        Column.primary_key.set($(this).children().eq(2).text() !== '');
        Column.unique.set($(this).children().eq(3).text() !== '');
        Column.not_null.set($(this).children().eq(4).text() !== '');
        Column.default.set($(this).children().eq(5).text());
        Column.check.set($(this).children().eq(6).text());
        Column.foreign_key.set($(this).children().eq(7).text(), $(this).children().eq(8).text());
        $("#Columns .__modal .Box-title").text('Edit Column');
        $("#Columns .__modal .j__create").text('Update Column');
        $('#Columns .__modal').fadeIn();
    });
});