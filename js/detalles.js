$(function() {
	
	$('.hola').hide();
	$('.hola').attr("value",false);
	

	$(".detalles").live("click", function() {
		
		punto='.hola#'+$(this).attr("id");	
		
		if ($(punto).attr("value")){		
			
			$(punto).hide();
			$(punto).attr("value",false);
			$(punto).html('');
			
		}
		else {
			
			$('.hola').attr("value",false);
			$('.hola').hide();
			$('.hola').html('');
			$(punto).show();
			$(punto).attr("value",true);
			tabla="verdetalles";
			inputString=$('.dato#'+$(this).attr("id")).attr("value");
			console.log(inputString);
			elegido="id";
			$.post("php/motorjs.php", {buscar: inputString ,funcion:tabla, modo: elegido}, function(data){
			console.log(data);
			if(data.length >0) {
				$(punto).html("<td>"+data+"</td>");
			} else {
				$(punto).html("vacio");
			}
			
			});
		}
		return false;
		});	
	$("#autorizar").live("click", function(){
		if($('input#autorizar').attr("checked")) {
    		$('input#enviarauto').attr("disabled",false);
  		} else {
    		$('input#enviarauto').attr("disabled",true);
  		} 
  	})
  	$("#enviarauto").live("click", function(){
  		//aqui debemos mandar el formulario
  		$.post("php/autorizacion.php", {idatencion:$("input#sol").attr("value"),accion:"autorizar2"}, function(data){
			alert(data);
		});
  		//aqui borramos la tupla autorizada
  		//$(this).parent().parent().parent().html('');
  		//$('td#borra'+$("input#sol").attr("value")).html("autorizado");
		colorFondo = $('#banderaAutorizacion').css('background-color');
		if(colorFondo=='green'){
			$('#banderaAutorizacion').css('background-color')='red';
		}
		else {
			$('#banderaAutorizacion').css('background-color')='green';
		}
  		return false;
  	})
  	$("#corregir").live("click", function(){
  		$.post("php/motorjs.php",$("#formdet").serialize(), function(data){
  			alert(data);
  		})
  		
  		return false;
  	})
});