$(function () {
  $(".hola").hide();
  $(".hola").attr("value", false);
  $(".detalles").live("click", function () {
    punto = ".hola#" + $(this).attr("id");
    if ($(punto).attr("value")) {
      $(punto).hide();
      $(punto).attr("value", false);
      $(punto).html("");
    } else {
      $(".hola").attr("value", false);
      $(".hola").hide();
      $(".hola").html("");
      $(punto).show();
      $(punto).attr("value", true);
      tabla = "verdetalles";
      inputString = $(".dato#" + $(this).attr("id")).attr("value");
      console.log(inputString);
      elegido = "id";
      $.post(
        "php/motorjs.php",
        { buscar: inputString, funcion: tabla, modo: elegido },
        function (data) {
          if (data.length > 0) {
            $(punto).html("<td>" + data + "</td>");
          } else {
            $(punto).html("vacio");
          }
        }
      );
    }
    return false;
  });
  $("#autorizar").live("click", function () {
    if ($("input#autorizar").attr("checked")) {
      $("input#enviarauto").attr("disabled", false);
    } else {
      $("input#enviarauto").attr("disabled", true);
    }
  });
  $("#enviarauto").live("click", function () {
    //aqui debemos mandar el formulario
    $.post(
      "php/autorizacion.php",
      { idatencion: $("input#sol").attr("value"), accion: "autorizar2" },
      (data) => alert(data)
    );

    $("#ab" + $("input#sol").attr("value"))
      .find("#banderaAutorizacion")
      .css("background-color", "green")
      .text("autorizado");
    $("#imprimir").prop("disabled", false);

    return false;
  });
  $("#corregir").live("click", function () {
    $.post("php/motorjs.php", $("#formdet").serialize(), function (data) {
      alert(data);
      $("#ab" + $("input#sol").attr("value"))
        .find("#banderaAutorizacion")
        .css("background-color", "red")
        .text("No autorizado");
      $("#imprimir").prop("disabled", true);
      $("input#autorizar").prop("checked", false);
      $("input#enviarauto").attr("disabled", true);
    });
    return false;
  });
  $("#imprimir").live("click", () => {
    window.open(
      "http://localhost/laboratorio1.0/reportes/resultados.php?idsol=1065116",
      "_blank"
    );
  });
});
