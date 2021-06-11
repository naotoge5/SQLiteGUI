const NEW = 1;
const DELETE = 0;

//初期ロード
databaseLoad();

function showDatabase(response) {
    let databases = JSON.parse(response);
    $('.database .show').empty();
    if (databases.length != 0) {
        for (let i = 0; i < databases.length; i++) {
            if (i == 0) {
                $('.database .show').append('<tr><td><input type="radio" name="select" value="' + databases[i] + '"checked></td><td><label for="' + databases[i] + '">' + databases[i] + '</label></td></tr>');
                $('.table h3').text('データベース（' + databases[i] + '）');
                //tableLoad(files[i]);
            } else {
                $('.database .show').append('<tr><td><input type="radio" name="select" value="' + databases[i] + '"></td><td><label for="' + databases[i] + '">' + databases[i] + '</label></td></tr>');
            }
        }
    } else {
        $('.database .show').append('<tr><th>データベースファイル（.db）が見つかりません</th></tr>');
    }
}

function showTable(response) {
    let tables = JSON.parse(response);
    $('.table .show').empty();
    if (tables.length != 0) {
        for (let i = 0; i < tables.length; i++) {
            $('.table .show').append('<tr><td><input type="button" name="select" value="削除"></td><td><label for="' + tables[i]['name'] + '">' + tables[i]['name'] + '</label></td></tr>');
        }
    } else {
        $('.table .show').append('<tr><th>テーブルは存在しません</th></tr>');
    }
}

/**
 * 
 * @param {String} name 
 * @param {Number} type delete -> 0, new -> 1
 */
function databaseLoad(database = '', type = -1) {
    $.ajax({
        type: "GET",
        url: "assets/database.php",
        data: { database: database, type: type }
    }).done(function (response) {
        showDatabase(response);
    }).fail(function () {
        console.log('miss');
    });
}

function tableLoad(database = '', type = -1) {
    $.ajax({
        type: "GET",
        url: "assets/table.php",
        data: { database: database, type: type }
    }).done(function (response) {
        showTable(response);
    }).fail(function () {
        console.log('miss');
    });
}


$(function () {
    //database
    $('.database .new').click(function () {
        let name = $('.database input[name="new"]').val();
        if (name != '') {
            databaseLoad(name + '.db', NEW);
            $('.database input[name="new"]').val('');
        }
    });

    $('.database .delete').click(function () {
        let database = $('.database .show input[name="select"]:checked').val();
        let flag = confirm('データベース"' + database + '"を削除しますか');
        if (flag) {
            databaseLoad(database, DELETE);
        }
    });

    //table
    /*
    $('.table .delete').click(function () {
        let table = $('.table .show input[name="select"]:checked').val();
        let flag = confirm('テーブル"' + table + '"を削除しますか');
        if (flag) {
            tableLoad(table, DELETE);
        }
    });*/

    $(document).on('change', 'input[name="select"]', function () {
        let database = $(this).val();
        $('.table h3').text('データベース（' + database + '）');
        tableLoad(database);
    });
});