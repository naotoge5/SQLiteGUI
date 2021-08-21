import { Folder } from './module.js';

init();

function init() {
    var deferred = Folder.data();
    deferred.done(function (data) {
        console.log(data);
        set(data);
    });
}
var paths = [];
$(document).on("click", "#list u", function (e) {
    var path = $("#path").text() + '/' + $(e.target).text();
    console.log(path);
    var deferred = Folder.data(path);
    deferred.done(function (data) {
        set(data);
    });
}).on("click", "#path u", function (e) {
    var dir = $(e.target).text();
    var path = '';
    for (let index = 0; index < paths.length; index++) {
        path += '/' + paths[index];
        if (dir == paths[index]) break;
    }
    var deferred = Folder.data(path);
    deferred.done(function (data) {
        set(data);
    });
}).on("change", "input[type='radio']", function (e) {
    var val = $(e.target).val()
    $("#db").text(val);
    var path = $("#path").text() + '/' + val;
    $("input[name='name']").val(path);
});



function set(data) {
    $("#path, #list").empty();
    paths = data.paths;
    var end = paths[paths.length - 1];
    paths.forEach(emp => {
        if (emp == end) {
            $("#path").append("/" + emp);
        } else {
            $("#path").append("/<u>" + emp + "</u>");
        }
    });
    data.list.forEach(emp => {
        if (emp.slice(-3) == '.db') {
            $("#list").append('<li><input type="radio" name="database" id="' + emp + '" value="' + emp + '"> <label for="' + emp + '">' + emp + '</label></li >');
        } else if (emp.indexOf('.') != -1) {
            $("#list").append("<li>" + emp + "</li>");
        } else {
            $("#list").append("<li><u>" + emp + "</u></li>");
        }
    });
}
/*
$("button").click(function (e) {
    var name = $("input[name='name']").val();
    var path = $("#dir_path").text();
    var flag = confirm(path + 'に\nデータベース：' + name + 'を作成しますか');
    if (flag) {
        path += '/' + name + '.db';

        var deferred = getFolderPath();

        deferred.done(function (data) {
            dirSet(data);
        });
    }
});*/
