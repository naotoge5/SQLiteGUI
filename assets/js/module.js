export class Folder {
    /**
     * @param {string} path - path
     * @return {any} Deferredオブジェクト in data - list and paths
     */
    static data(path = '') {
        console.log(path);
        var deferred = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "controller/async.php",
            data: { function: 'Folder_data', value: path },
            dataType: 'json'
        }).done(function (data) {
            deferred.resolve(data);
        }).fail(function (data) {
            deferred.resolve(-1);
        });
        return deferred;
    }
}

export class DB {
    static columns(name = '') {
        var deferred = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "controller/async.php",
            data: { function: 'Column_list_name', value: name },
            dataType: 'json'
        }).done(function (data) {
            deferred.resolve(data);
        }).fail(function (data) {
            deferred.resolve(-1);
        });
        return deferred;
    }
}