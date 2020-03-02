/*
 * Extiendo la clase L.WMS.source
 */

var wms_GIS = L.WMS.Source.extend({

  'showFeatureInfo': function(latlng, info) {
    if (!this._map){
      return;
    }

    //info = '<div>Plan Hidrico - Restitucion de pluviales</div>' + info;
    
    var datos = JSON.parse(info);

    if (datos.features.length === 0) { return false; }

    var datos1 = undefined;

    /* saco el nombre de la capa */
    var queLayer = datos.features[0]["id"].split('.');

    // infowindow restitucion pluviales
    if(queLayer[0] == "vw_mantenimiento_pluviales") {
      datos1 = "<div style=\"width: 450px;\"><b><span style=\"color: #5ea8b5; font-size: 14pt;\">Restituci&oacute;n Desagues Pluviales</span></b></div>";
      datos1 += "<b>Cuenca:</b> " + datos.features[0].properties["cuenca"] + "<br />";
      datos1 += "<b>Tipo desague:</b> " + datos.features[0].properties["tipo_desague"] + "<br />";
      datos1 += "<b>Sub-tipo:</b> " + datos.features[0].properties["subtipo"] + "<br />";
      datos1 += "<b>Tipo segmento:</b> " + datos.features[0].properties["tipo_segmento"] + "<br />";
      datos1 += "<b>Restituido:</b> " + datos.features[0].properties["limpio"] + "<br />";

      if( !(datos.features[0].properties["fecha_mantenim"])== null ) {

        var f = datos.features[0].properties["fecha_mantenim"];
        var a = f.substr(8,2) + "/" + f.substr(5,2) + "/" + f.substr(0,4);

      } else {
        a = '';
      }

      datos1 += "<b>Fecha mant.:</b> " + a + "<br />";

    }

    // infowindow para restitucion sumidero
    if(queLayer[0] == "vw_mantenimiento_sumideros") {
      datos1 = "<div style=\"width: 450px;\"><b><span style=\"color: #b79547; font-size: 14pt;\">Restituci&oacute;n Sumideros</span></b></div>";
      datos1 += "<b>Clase de sumidero:</b> " + datos.features[0].properties["clase_de_sumideros"] + "<br />";
      datos1 += "<b>Tipo sumidero:</b> " + datos.features[0].properties["tipo_sumidero"] + "<br />";
      datos1 += "<b>Restituido:</b> " + (datos.features[0].properties["limpio"] == null ? '' : datos.features[0].properties["limpio"]) + "<br />";

      if( !(datos.features[0].properties["fecha_mantenim"])== null ) {

        var f = datos.features[0].properties["fecha_mantenim"];
        var a = f.substr(8,2) + "/" + f.substr(5,2) + "/" + f.substr(0,4);

      } else {
        a = '';
      }

      datos1 += "<b>Fecha mant.:</b> " + a + "<br />";

    }

    if (datos1 != undefined) {
      datos1 += '<div style="border-top: 1px solid #7f7f7f; padding-top: 7px; margin-top: 7px; font-family: Roboto; font-size: 11px; color: #7f7f7f">Fuente: Dir. Redes Viales</div>';

      this._map.openPopup(datos1, latlng);
    }
  }
});
  
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
