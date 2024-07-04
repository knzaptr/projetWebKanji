$(document).ready(function() {
    // Cacher la div au chargement de la page
    $("#carddiv").hide();

    // Attacher un gestionnaire d'événements au bouton de suggestion
    $("#suggbutton").click(function() {
        // Afficher ou cacher la div en fonction de son état actuel
        $("#carddiv").toggle();
    });
});
