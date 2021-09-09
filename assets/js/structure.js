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
        get: function () {
            return $("select[name='column-type']").val();
        },
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
        get: function () {
            var flag = $("input[name='column-primary_key']").prop('checked');
            return flag;
        },
        set: function (flag) {
            $("input[name='column-primary_key']").prop('checked', flag);
        }
    },
    autoincrement: {
        get: function () {
        },
        set: function (flag) {
            $("input[name='column-autoincrement']").prop('checked', flag);
        },
        disable: function (flag) {
            $("input[name='column-autoincrement']").prop('disabled', flag);
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
            Column.autoincrement.set(false);
            Column.autoincrement.disable(true)
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

function createSchema() {

}

import { DB } from './module.js';
$(function () {

    $("#Drop").submit(function (e) {
        var val = $("input[name='drop-table-name']").val();
        var flag = confirm('DROP TABLE ' + val + '?');
        if (!flag) return false;
    });

    $("select[name='foreign_key-table']").change(function (e) {
        var table = $(this).val();
        setForeign_keyColumn(table);
    });

    //Columns
    $("#Columns .j__add").click(function () {
        $("#Columns .__modal .Box-title").text('New Column');
        $("#Columns .__modal .j__create").text('Create Column');
        $("#Columns .__modal .j__delete").addClass('hide');
        Column.All.clear();
        $('#Columns .__modal').fadeIn();
        return false;
    });

    $('#Columns .j__modal-close').on('click', function () {
        $('#Columns .__modal').fadeOut();
        return false;
    });

    $("#Columns .j__create").on('click', function () {
        if ($("#Columns .j__create").text() === 'Create Column') {//new
            alert('new');
        } else {//edit
            var index = $(this).data('index');
            alert('edit');
        }

    });

    $("#Columns .j__delete").on('click', function () {
        var index = $(this).data('index');
        for (let count = 0; count < $(Table.columns).length; count++) {
            if ($(Table.columns).eq(count).data('index') === index) {
                $("#Columns tbody tr[data-index='" + index + "']").remove();
                console.log('ds')
                break;
            }
        }
        //createSchema();
        $('#Columns .__modal').fadeOut();
    });

    $("#Columns input[name='column-name'], #Columns select[name='column-type']").change(function () {
        $("#Columns .j__create").prop('disabled', !(Column.type.get() !== null && Column.name.get() !== ''));
    });

    $("#Columns select[name='column-type'], #Columns input[name='column-primary_key']").change(function () {
        if (Column.type.get() === 'integer' && Column.primary_key.get()) {
            Column.autoincrement.disable(false);
        } else {
            Column.autoincrement.set(false);
            Column.autoincrement.disable(true);
        }
    });

    $(Table.columns).click(function () {
        Column.name.set($(this).children().eq(0).text());
        Column.type.set($(this).children().eq(1).text());
        $("#Columns .j__create").prop('disabled', false);
        Column.primary_key.set($(this).children().eq(2).text() !== '');
        $("#Columns select[name='column-type']").change();
        Column.autoincrement.set($(this).children().eq(2).text() === 'autoincrement');
        Column.unique.set($(this).children().eq(3).text() !== '');
        Column.not_null.set($(this).children().eq(4).text() !== '');
        Column.default.set($(this).children().eq(5).text());
        Column.check.set($(this).children().eq(6).text());
        Column.foreign_key.set($(this).children().eq(7).text(), $(this).children().eq(8).text());
        $("#Columns .__modal .Box-title").text('Edit Column');
        $("#Columns .__modal .j__create").text('Update Column');
        $("#Columns .__modal .j__delete").removeClass('hide');
        $("#Columns .__modal .j__create, #Columns .__modal .j__delete").data('index', $(this).data('index'));
        $('#Columns .__modal').fadeIn();
    });

    //Constraints
    $("#Constraints .j__add").click(function () {
        $("#Constraints .__modal .Box-title").text('New Constraint');
        $("#Constraints .__modal .j__create").text('Create Constraint');
        //Column.All.clear();
        $('#Constraints .__modal').fadeIn();
        return false;
    });

    $('#Constraints .j__modal-close').on('click', function () {
        $('#Constraints .__modal').fadeOut();
        return false;
    });
});