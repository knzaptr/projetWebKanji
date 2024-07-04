$(document).ready(function () {
  // Vérifier si l'utilisateur a déjà défini un mode
  var isNightMode = localStorage.getItem("isNightMode");
  var body = document.getElementsByTagName("body")[0];

  // Vérifier le mode initial
  if (isNightMode === "true") {
    body.classList.add("modenuit");
    $("#moon_light").html('<img src="../images/2.png" width="20" height="20">');
  } else {
    body.classList.add("modejour");
    $("#moon_light").html('<img src="../images/1.png" width="20" height="20">');
  }

  // Attacher un gestionnaire d'événements au bouton de mode nuit
  $("#night").click(function () {
    if (isNightMode === "true") {
      // passer en mode jour
      body.classList.remove("modenuit");
      body.classList.add("modejour");

      $("#card-body").css("background-color", "#F8F8FFFF");
      $(".accordion-item").css("background-color", "#F8F8FFFF");
      $("th, tr, td").css("color", "black");
      $(
        ".col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12"
      ).css("background-color", "rgba(255, 255, 255, 0.39)");
      $(".modejour").css("color", "#0e0e4e");
      $("a").css("color", "#0e0e4e");

      $("#moon_light").html(
        '<img src=\'../images/1.png\' width="20" height="20">'
      );
      $("#suggbutton").html('<img src="../images/bullejour.png" width="50">');

      localStorage.setItem("isNightMode", "false");
      isNightMode = "false";
    } else {
      // passer en mode nuit
      body.classList.remove("modejour");
      body.classList.add("modenuit");

      $("#card-body").css("background-color", "rgba(39,54,87,0.5)");
      $(".accordion-item").css("background-color", "rgba(39,54,87,0.5)");
      $("th, tr, td").css("color", "#ffffff");
      $(
        ".col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12"
      ).css("background-color", "rgba(255, 255, 255, 0.06)");
      $(".modenuit").css("color", "#ffffff");
      $("a").css("color", "#cccccc");

      $("#moon_light").html(
        '<img src="../images/2.png" width="20" height="20">'
      );
      $("#suggbutton").html('<img src="../images/bullenuit.png" width="50">');

      localStorage.setItem("isNightMode", "true");
      isNightMode = "true";
    }
  });
});
