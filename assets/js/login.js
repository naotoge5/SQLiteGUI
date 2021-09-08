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
    paths.forEach(tmp => {
        if (tmp == end) {
            $("#path").append("/" + tmp);
            $("#current").text(tmp);
        } else {
            $("#path").append("/<u class='__hover-pointer'>" + tmp + "</u>");
        }
    });
    data.list.forEach(tmp => {
        if (tmp.slice(-3) == '.db') {
            var name = tmp.replace('.db', '')
            $("#list").append('<li class="Box-row __hover-pointer __hover-bg DB" data-name="' + name + '">' + tmp + '</li>');
        } else if (tmp.indexOf('.') != -1) {
            $("#list").append('<li class="Box-row">' + tmp + '</li>');
        } else {
            $("#list").append('<li class="Box-row __hover-pointer __hover-bg">' + tmp + '</u></li>');
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
