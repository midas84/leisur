$(function () {
  $(".hola").hide();
  $(".hola").attr("value", false);
  $(document).ready(function () {
    // ... Tu función adjustTextareaHeight(element) debe estar aquí ...
    function adjustTextareaHeight(element) {
      var $element = $(element);
      $element.css("height", "auto");
      $element.css("height", element.scrollHeight + "px");
    }

    // ... Tu función initializeDynamicTextareas(containerSelector) debe estar aquí ...
    window.initializeDynamicTextareas = function (containerSelector) {
      var $newTextareas = $(containerSelector).find("textarea.dynamic-height");

      $newTextareas.each(function () {
        adjustTextareaHeight(this);
        $(this)
          .off("input keyup")
          .on("input keyup", function () {
            adjustTextareaHeight(this);
          });
      });
    };

    // ------------------------------------------------------------------
    // CLAVE: REEMPLAZO DE .live() POR .delegate()
    // ------------------------------------------------------------------

    // Usamos 'body' o el contenedor principal como el elemento padre
    // para delegar el evento 'click' a los elementos '.detalles'.
    // Esto es mucho más estable que .live() en versiones antiguas de jQuery.
    $("body").delegate(".detalles", "click", function () {
      // El resto de tu lógica de AJAX va aquí:
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
              // 1. INYECTAR EL CONTENIDO
              $(punto).html("<td>" + data + "</td>");

              // 2. LLAMADA CRUCIAL
              if (typeof initializeDynamicTextareas === "function") {
                initializeDynamicTextareas(punto);
              }
            } else {
              $(punto).html("vacio");
            }
          }
        );
      }
      // IMPORTANTE: Si usas delegate, el 'return false' es a menudo innecesario
      // si la función solo gestiona el comportamiento. Lo mantendremos por seguridad.
      return false;
    });
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
    $("#sobre").prop("disabled", false);

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
      $("#sobre").prop("disabled", true);
      $("input#autorizar").prop("checked", false);
      $("input#enviarauto").attr("disabled", true);
    });
    return false;
  });
  $("#imprimir").live("click", () => {
    
    window.open(
      "reportes/resultados.php?idsol=" + $("input#sol").attr("value")+"&firma="+document.getElementById("firma").value,
      "_blank"
    );
  });
  $("#sobre").live("click", () => {
    window.open(
      "reportes/sobre.php?idsol=" + $("input#sol").attr("value"),
      "_blank"
    );
  });
});
