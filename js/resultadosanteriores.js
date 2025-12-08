$("#dialog").dialog({
    bgiframe: true,
    autoOpen: false,
    resizable: false,
    height:140,
    modal: true,
    overlay: {
        backgroundColor: '#000',
        opacity: 0.5
    },
    buttons: {
        /* 'Si': function() {
            var url =$('.delete').attr("href");
            location.href=url;
            return true;
        }, */
        'Cerrar': function() {
            $(this).dialog('close');
            return false;
        }
    }
});                    
$('input#resultadosanteriores').click( function(e){
   $(this).click(function(){
       $('#dialog').dialog('open');
       return false;
    });                
   
});