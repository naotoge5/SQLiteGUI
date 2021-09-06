import { Folder } from './module.js';

init();

function init() {
    var deferred = Folder.data();
    deferred.done(function (data) {
        set(data);
    });
}
var paths = [];
$(document).on("click", "#list li.__hover-pointer", function (e) {
    if ($(e.target).hasClass("DB")) {
        var name = $(e.target).data("name");
        var flag = confirm('Connect to "' + name + '"?');
        if (flag) {
            var path = $("#path").text() + '/' + $(e.target).text();
            location.href = "controller/_login.php?path=" + encodeURIComponent(path);
        }
    } else {
        var path = $("#path").text() + '/' + $(e.target).text();
        var deferred = Folder.data(path);
        deferred.done(function (data) {
            set(data);
        });
    }
}).on("click", "#path u.__hover-pointer", function (e) {
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
});



function set(data) {
    $("#path, #list").empty();
    paths = data.paths;
    var end = paths[paths.length - 1];
    paths.forEach(emp => {
        if (emp == end) {
            $("#path").append("/" + emp);
            $("#current").text(emp);
        } else {
            $("#path").append("/<u class='__hover-pointer'>" + emp + "</u>");
        }
    });
    data.list.forEach(emp => {
        if (emp.slice(-3) == '.db') {
            var name = emp.replace('.db', '')
            $("#list").append('<li class="Box-row __hover-pointer DB" data-name="' + name + '">' + emp + '</li>');
        } else if (emp.indexOf('.') != -1) {
            $("#list").append('<li class="Box-row">' + emp + '</li>');
        } else {
            $("#list").append('<li class="Box-row __hover-pointer">' + emp + '</u></li>');
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
