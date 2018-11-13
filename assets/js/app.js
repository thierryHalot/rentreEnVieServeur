require('../css/app.scss');

const $ = require('jquery');
require('bootstrap');


$("#testJs").append("<h1>Si ce titre est afficher, alors jquery est fonctionnel</h1>");

$('#buttonMessageUpdateUser').click(



    //fonction qui permet de cacher le nombre de point dans la page de profils
    function messageHidden() {

        var div = $('.conteneurMessageUpdateUser');

        div.slideUp(800);


    }


);


$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});