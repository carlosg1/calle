/*
 * Extiendo la clase L.WMS.source
 */

var personalSource = L.WMS.Source.extend({

  'showFeatureInfo': function(latlng, info) {
      if (!this._map){
      	return;
      }

      //info = '<div>Plan Hidrico - Restitucion de pluviales</div>' + info;

      var datos = JSON.parse(info);

      var datos1 = '<div>MANTENIMIENTO DESAGUES PLUVIALES</div>';
      datos1 += 'ID: ' + datos.features[0].properties['gid'] + '<br>';
      datos1 += 'Tipo desague: ' + datos.features[0].properties['tipo_desague'] + '<br>';
      datos1 += 'Subtipo: ' + datos.features[0].properties['subtipo'] + '<br>';
      datos1 += 'Cuenca: ' + datos.features[0].properties['cuenca'] + '<br>';
      datos1 += 'Tipo de Segmento: ' + datos.features[0].properties['tipo_segmento'] + '<br>';
      datos1 += 'Limpio:' + datos.features[0].properties['limpio'] + '<br>';
      datos1 += 'Fecha mantenimiento: ' + datos.features[0].properties['fecha_mantenim'] + '<br>';

      /*
            var div1 = document.getElementById('ventanaEdicion');
            div1.innerHTML=datos1;
            div1.style.display='';
      */
      this._map.openPopup(datos1, latlng);
  },

  'ajax': function(url, callback) {
      ajax.call(this, 'curl.php?url='+url, callback);
  }


})

function leerAjax(url, callback) {
  var context = this,
      request = new XMLHttpRequest();
  request.onreadystatechange = change;
  request.open('GET', 'curl.php?url=' + url);
  request.send();

  function change() {
    if (request.readyState === 4) {
      if (request.status === 200) {
        callback.call(context, request.responseText);
      } else {
        callback.call(context, "error");
      }
    }
  }
};
