$(function () {
  $("#seleccionmodo").live("set", function () {
    alert("hola");
  });
});
function lookup(inputString) {
  $("#suggestions").hide();
  if (inputString.length == 0) {
    // Hide the suggestion box.
    $("#suggestions").hide();
  } else {
    nombrecategoria = "categoriaanalisis";
    $.post(
      "php/motorjsresultados.php",
      { buscar: inputString, funcion: nombrecategoria },
      function (data) {
        if (data.length > 0) {
          $("#suggestions").show();
          $("#autoSuggestionsList").html(data);
        } else {
          $("#suggestions").hide();
        }
      }
    );
  }
} // lookup

function fill(thisValue, idcategoria) {
  $("#nombre").val(thisValue);
  listaderesultados="";
  setTimeout("$('#suggestions').hide();", 10);
  nombreanalisis = "analisisespecifico";
  $.post(
    "php/motorjsresultados.php",
    { buscar: idcategoria, funcion: nombreanalisis },
    function (data) {
      $("#selectresultado").html(data);
	  $("#listaresultados").html(listaderesultados);
    }
  );
}
function mostrarresultados(thisValue) {
  tabla = "gettiporesultado";
  $.post(
    "php/motorjsresultados.php",
    { buscar: thisValue.value, funcion: tabla },
     (data) => {
      $("#listaresultados").html(data);
    }
  );
}
