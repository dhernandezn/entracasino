$(function(){
	$('#fs').submit(function(){
		var comprobar = $('#arch_carga').val().length;
		console.log(comprobar);
		if (comprobar>0) {
			var formulario = $('#fs');
			console.log(formulario);
			var archivo = new FormData();
			var url = 'importar_csv.php';
			for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
				archivo.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
			console.log("dentro del for");
			}

			$.ajax({
				url: url,
				type: 'POST',
				contentType: false,
				data: archivo,
				processData: false,
				beforeSend: function(){
					$('#respuesta').html('<center><img src="img/loading.gif" width="220" heigh="50"></center>');
					console.log("ejecuto el envio");
				},
				success:function(data){
					if (data=='OK') {
						$('#respuesta').html('<label style="padding-top:10px; color:green;">Importación Realizada</label>');
					return false;
					}else{
					$('#respuesta').html('<label style="padding-top:10px; color:red;">Error en la Importación</label>');
					return false;
				}
			}
		});
			return false;
		}else{
			alert('Selecciona un archivo CSV');
			return false;
		}
	});
});