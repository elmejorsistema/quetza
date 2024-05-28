
//////////////////////////
// Esto funciona
//////////////////////////

$('[id^=ahe]').keypress(function(element, code){
   var code = element.keyCode || element.which;
   if(code==13)
    validar(4, 1);
   });


function validar(element, n)
{
 var a = element.id;
 alert(a);
};





/////////////////////////
// Esto funciona
////////////////////////
$('[id^=ahe]').bind('keypress', event, function()
   {
     var val = event.keyCode || event.which;
     alert(val);
   });

$('[id^=ahe]').bind('blur', function()
   {
     val = $(this).val();
     //alert(val);
   });

$('[id^=ahe]').keypress(function(element, code){
   var code = element.keyCode || element.which;
   if(code==13)
    validar(4, 1);
   });



/////////////////////////



$('[id^=ahe]').each(function(i, el){
 el = $(el);
 el.keypress(function(element, code){
   var code = element.keyCode || element.which;
   if(code==13)
    validar(4, 1);
    };})

$('[id^=ahe]').each(function(i, el){
 //el = $(el);
 $this.focusout(function(element){
  validar(element, 0);
  });
});



$(document).ready(function() {
  $('[id^=ahe]').each(function(i, el)
  {
    el = $(el);
    el.focusout(
            function()
             { alert('a');});
  }
 )};


$('[id^=ahe]').focusout(function(element){
    var a = $this.val();
//    alert(a);
   //validar(element, 0);
//});




$('[id^=he]').on('keypress', function(event)
   {
     tecla = event.keyCode || event.which;
     valor = $(this).val();
     if(validar(valor))
       if(tecla == 13 || tecla == 9)
       {
         id    = $(this).attr('id');
         envia(id, valor);
       }
   });

$('[id^=1ahe]').on('change', function(event)
   {
     valor = $(this).val();
     if(validar(valor))
     {    
       id    = $(this).attr('id');
       envia(id, valor);
     }
   });



$('[id^=ahe]').on('focusout', function(event)
   {
     //event.preventDefault();
     //return;
     valor = $(this).val();
    
     if(!isNumeric(valor))
      {
       if(!valor)
        {
         alert('Valor erroneo = '+valor+'Por favor ingresa un número entre 0 y 10');
         $(this).attr('class', 'calificacion-error');
        }
      }
     else
     {
      if(valor >10 || valor <0)
       {
         alert('Valor erroneo = '+valor+'Por favor ingresa un número entre 0 y 10');
         $(this).attr('class', 'calificacion-error');
       }
     }
   });

////////////////////////////////////
// Esto es lo úktimo que funciona //
////////////////////////////////////

$('[id^=ahe]').on('focusout', function(event)
   {
     //event.preventDefault();
     //return;
     valor = $(this).val();
    
     //alert(valor);
     if(!$.isNumeric(valor))
      {
       //alert('valor a');
       if(valor != '')
        {
         alert('Valor erróneo = '+valor+'\\nPor favor ingresa un número entre 0 y 10');
         $(this).attr('class', 'calificacion-error');
        }
       else
        {
         $(this).attr('class', 'calificacion');
        }
      }
     else
     {
      //alert('valor b');
      if(valor >10 || valor <0)
       {
         alert('Valor erróneo = '+valor+'\\nPor favor ingresa un número entre 0 y 10');
         $(this).attr('class', 'calificacion-error');
       }
      else
       {
        $(this).attr('class', 'calificacion');
       }
     }
   });



$('[id^=ahe]').on('change', function(event)
   {
     $('#tdenvia1').attr('class', 'titulo-alumno-enviar');
     $('#benvia1').html('Es Necesario Guardar los Datos');
     $('#tdenvia2').attr('class', 'titulo-alumno-enviar');
     $('#benvia2').html('Es Necesario Guardar los Datos');

     //valor = $(this).val();

   });



$('#formaalumno').data('serialize',$('#formaalumno').serialize()); // On load save form current state

$(window).bind('beforeunload', function(e)
   {
    if($('#formaalumno').serialize()!=$('#formaalumno').data('serialize'))
      return true;
    else
      e = null;
});



$('#formaalumno').validator({
    lang       :'sp',
    singleError: false,
    position: \"bottom center\",
 
    //onfocusout: function(){
    // alert('hola');}

    });
