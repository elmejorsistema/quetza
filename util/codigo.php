




$(\"div[rel=#buscar]\").overlay({
  // disable this for modal dialog-type of overlays
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     onLoad: function() {
        $('#name_b').focus(); 
     },
     onBeforeClose: function() {
        $(\"#myform_b\").data(\"validator\").reset();
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 },
     $cargar_nuevo
     });




$(\"#nombre_b\").tooltip();
$(\"#rfc_b\").tooltip();
$(\"#calle_b\").tooltip();
$(\"#no_exterior_b\").tooltip();
$(\"#colonia_b\").tooltip();
$(\"#no_interior_b\").tooltip();
$(\"#localidad_b\").tooltip();
$(\"#municipio_b\").tooltip();
$(\"#codigo_postal_b\").tooltip();
$(\"#telefono_b\").tooltip();
$(\"#email_b\").tooltip();
$(\"#pagina_web_b\").tooltip();









  select: function(event, supplier) {
            var url = supplier.item.id;
            if(url != '#') {
                location.href = '/blog/' + url;


function(a, callback){ return callback([\"Action0\",\"Action1\",\"Action2\"])},

function (a,b)
                 {
                    id = $(\"#supplier\").val();
                    $.post(\"util/json_proveedor.php\",
                           { \"proveedor\" : id },
                           function(data){
                               if(data)
                                  {
                                    var suggestions = []; 
                                    $.each(data, function(i, val)
                                     {                                
                                       suggestions.push(val.label);  
                                       //alert(suggestions);
                                       //return suggestions;
                                     })
                                   }
                                },
                           \"json\");
                   return [\"Action0\",\"Action1\",\"Action2\"]
                 },



$(function() {
 var data1 = [
\"ActionScript\",
\"AppleScript\",
\"Asp\",
\"BASIC\",
\"C\",
\"C++\",
\"Clojure\",
\"COBOL\",
\"ColdFusion\",
\"Erlang\",
\"Fortran\",
\"Groovy\",
\"Haskell\",
\"Java\",
\"JavaScript\",
\"Lisp\",
\"Perl\",
\"PHP\",
\"Python\",
\"Ruby\",
\"Scala\",
\"Scheme\"
];

    $(\"#supplier\").autocomplete({
        source: data1,
        minLength: 2,
        select: function(event, ui) {
            var url = ui.item.id;
            if(url != '#') {
                location.href = '/blog/' + url;
            }
        },
 
        html: false, // optional (jquery.ui.autocomplete.html.js required)
 
      // optional (if other layers overlap autocomplete list)
        open: function(event, ui) {
            $(\".ui-autocomplete\").css(\"z-index\", 1000);
        }
    });
 
});




$(function() {
 var data1 = [
\"ActionScript\",
\"AppleScript\",
\"Asp\",
\"BASIC\",
\"C\",
\"C++\",
\"Clojure\",
\"COBOL\",
\"ColdFusion\",
\"Erlang\",
\"Fortran\",
\"Groovy\",
\"Haskell\",
\"Java\",
\"JavaScript\",
\"Lisp\",
\"Perl\",
\"PHP\",
\"Python\",
\"Ruby\",
\"Scala\",
\"Scheme\"
];
$('#supplier').autocomplete({
	 source: data1
});	
});


 //window.open('util/7.php?grupo=401&final=0','_blank', 'menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no, width=100, top=100, height=100');
        //$('a[rel]').overlay().close();
 //window.open('util/7.php?grupo='+grupo+'&final='+final,'_blank', 'menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no, width=100, top=100, height=100');
        

        //download('util/7.php','grupo=401&final=0', 'get');
        //$('a[rel]').overlay().close();
        //ert('hola');
        //$.download('util/7.php','grupo=401&final=0' );

        //jQuery('<form id=\"bajar\" action=\"util/7.php\" method=\"get\"><input type=\"text\" name=\"grupo\" value=\"401\"><input type=\"text\" name=\"final\" value=\"0\"></form>').appendTo('body').submit().remove();
        //$('a[rel]').overlay().close();

       /* $.post('util/7.php?grupo=41&final=0', function(data)
           {

            jQuery('<form id=\"bajar\" action=\"util/7.php?grupo=41&final=0\" method=\"post\"><input type=\"text\" name=\"grupo\" value=\"401\"><input type=\"text\" name=\"final\" value=\"0\"></form>').appendTo('body').submit().remove();
            $('a[rel]').overlay().close();
            //$('body').append(data);
            //data.appendTo('body');
            //var blob=new Blob([data]);
            //download.(blob);
            //var downloadIFrame = window.transData.downloadIFrame = window.transData.downloadIFrame || $('#downloadFileiFrame');
            //downloadIFrame.attr('src',blob );


            //var link=document.createElement('a');
            //link.href=window.URL.createObjectURL(blob);
            //link.download='Dossier_'+new Date()+'.pdf';
            //link.click();




                   //alert(data);
                   //$('a[rel]').overlay().close();
                   //window.open(data);
           
           });*/



download = function(url, data, method){
        //alert('download');
	//url and data options required
	if( url && data ){ 
		//data can be string of parameters or array/object
		data = typeof data == 'string' ? data : jQuery.param(data);
		//split params into form inputs
		var inputs = '';
		jQuery.each(data.split('&'), function(){ 
			var pair = this.split('=');
			inputs+='<input type=\"hidden\" name=\"'+ pair[0] +'\" value=\"'+ pair[1] +'\" />'; 
		});
		//send request
		jQuery('<form action=\"'+ url +'\" method=\"'+ (method||'post') +'\">'+inputs+'</form>')
		.appendTo('body').submit().remove();
	};
};



$(document).ready(function() {


$(\"#bajar-401\").submit(function(event) {
  alert('submit');
});


$(\"#bajar-401\").bind('ajax:complete', function() {

 alert(' de regreso');

   });





$(\"utton[rel]\").each(function(i, el) {
  el = $(el);
  $(el).overlay({
     closeOnClick: false,
     closeOnEsc:   false,
     top:   110,
     left:  215,
     fixed: false,
     api:   true,
     //effect: 'shake',
     onLoad: function() {
        $(el).closest(\"form\").submit();
        $(el).overlay().close();
       //var form = $(this).eq(i).closest('form');
       //console.log(el);
       //alert($(el));
       //form.submit();
       //$(\"#bajar\").submit();
       
     },
     mask: {
       color: '#ffffff',
       loadSpeed: 200,
       opacity: 0.6 }
     });
  });
});


