<?php ?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <link rel="stylesheet" href="css/leaflet.css">
        <link rel="stylesheet" href="css/Control.OSMGeocoder.css">
        <link rel="stylesheet" href="css/leaflet-measure.css">

        <style>
        html, body, #map {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            z-index: 100;
        }
        #map { cursor: default; }
        
        #logo-mcc > img { width: 170px; }
        .leaflet-control-layers{
            opacity:0.85!important;
            filter: alpha(opacity=30); /* para IE8 y posterior */
        }
        #logo-mcc {
            position: absolute;
            top: 10px;
            left: 73px;
            width: 200px; 
            z-index: 1100;
        }
        #busca-calle {
            position: absolute;
            left: 12px;
            top: 85px;
            z-index: 1101;
        }
        #mensajes { z-index: 1500; }
        </style>

        <!-- JQuery -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/humanity/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>  

        <title>Calles - Municipalidad de Corrientes</title>
    </head>
    <body>

        <div id="map"></div>

        <div id="logo-mcc"><img src="../images/escudo-municipalidad.png" alt=""></div>

        <!-- Buscador de calles --> 
        <div id="busca-calle">
            <div class="input-group shadow mb-3">
                <input type="text" class="form-control" placeholder="Nombre de calle" aria-label="Recipient's username" aria-describedby="btnBuscar" id="nombre_calle">
                <div class="input-group-append">
                    <button class="btn btn-outline-info" type="button" id="btnBuscar">Buscar</button>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-outline-danger" type="button" id="btnLimpiar">Limpiar</button>
                </div>
            </div>
        </div>

        <!-- mensaje que pide ingresar una calle --> 
        <div id="mensajes"><p>Tenes que ingresar un nombre de calle válido.</p></div>

        <!-- mensaje que no se encontro ningun elemento -->
        <div id="msg-no-encontre"><p>La busqueda no arrojó ningun resultado. <br /> Por favor, intente de nuevo con otra busqueda.</p></div>

        <script src="js/leaflet.js"></script>
        <script src="js/leaflet.rotatedMarker.js"></script>
        <script src="js/leaflet.pattern.js"></script>
        <script src="js/leaflet-hash.js"></script>
        <script src="js/Autolinker.min.js"></script>
        <script src="js/rbush.min.js"></script>
        <script src="js/labelgun.min.js"></script>
        <script src="js/labels.js"></script>
        <script src="js/leaflet.wms.js"></script>
        <script src="js/Control.OSMGeocoder.js"></script>
        <script src="js/leaflet-measure.js"></script>
        <script src="js/leaflet-omnivore.min.js"></script>
        <script src="js/mostrar-infowindow.js"></script>

        <script>
        var capas = Array();

        // detecta si se accede desde un mobil
        function isMobile(){
            return (
                (navigator.userAgent.match(/Android/i)) ||
                (navigator.userAgent.match(/webOS/i)) ||
                (navigator.userAgent.match(/iPhone/i)) ||
                (navigator.userAgent.match(/iPod/i)) ||
                (navigator.userAgent.match(/iPad/i)) ||
                (navigator.userAgent.match(/BlackBerry/i))
            );
        };

        var map = L.map('map', {
            zoomControl:true,
            inertia: true,
            maxZoom:17,
            minZoom:1,
            crs: L.CRS.EPSG900913,
            center: [-27.48483,-58.81393],
            zoom: 18
        });

        map.fitBounds([[-27.45664518742547, -58.763208389282234],[-27.504312737195168, -58.87899398803712]]);

        var hash = new L.Hash(map);
  
        // crea paneles para controlar el orden de superposicion de las capas
        map.createPane('pane-ide-calles').style.zIndex = 515;
        map.createPane('pane-calle-por-tipo-calzada').style.zIndex = 510;

        map.attributionControl.addAttribution(false);
        map.attributionControl.getContainer().innerHTML='Busqueda de calles - <a href="http://gis.ciudaddecorrientes.gov.ar" target="_blank">Direccion Gral de SIG</a>';

        var measureControl = new L.Control.Measure({
            primaryLengthUnit: 'meters',
            secondaryLengthUnit: 'kilometers',
            primaryAreaUnit: 'sqmeters',
            secondaryAreaUnit: 'hectares'
        });

        measureControl.addTo(map);

        var bounds_group = new L.featureGroup([]);

        function setBounds() {
        }

        var overlay_GooglecnSatellite_0 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
            opacity: 1.0
        });

        var overlay_CapabaseGIS_0 = L.WMS.layer("http://190.7.30.142:8282/geoserver/wms?version=1.3.0&", "capa_base_mcc:capa_base", {
            format: 'image/png',
            uppercase: true,
            transparent: true,
            continuousWorld : true,
            tiled: true,
            info_format: 'text/html',
            opacity: 1,
            identify: false,
        });

        map.addLayer(overlay_CapabaseGIS_0);

        // capas geoserver

        // vw_ide_calles
        var lyr_calle = new wms_GIS("http://190.7.30.142:8282/geoserver/wms?", {
            format: 'image/png',
            uppercase: true,
            transparent: true,
            version: '1.3.0',
            continuousWorld : true,
            tiled: true,
            attribution: "Direccion Gral de GIS",
            info_format: 'application/json',
            opacity: 1,
            pane: 'pane-ide-calles'
        }).getLayer("red_vial:vw_ide_calle", {pane: 'pane-ide-calles'});

        // vw_ide_calle_por_tipo_calzada
        var vw_ide_calle_por_tipo_calzada = new wms_GIS("http://190.7.30.142:8282/geoserver/wms?", {
            format: 'image/png',
            uppercase: true,
            transparent: true,
            version: '1.3.0',
            continuousWorld : true,
            tiled: true,
            attribution: "Direccion Gral de GIS",
            info_format: 'application/json',
            opacity: 1,
            pane: 'pane-calle-por-tipo-calzada'
        }).getLayer("red_vial:vw_ide_calle_por_tipo_calzada", {pane: 'pane-calle-por-tipo-calzada'});

       var osmGeocoder = new L.Control.OSMGeocoder({
            collapsed: false,
            position: 'topleft',
            text: 'Search'
        });

        var baseMaps = {
            "Google Satelite": overlay_GooglecnSatellite_0,
            "Capa base GIS": overlay_CapabaseGIS_0
        };

        L.control.layers(baseMaps,{
            '<b>Calles</b><span><div style="margin-left: 17px;"><img src="images/vw_ide_calles.png" /></div><span>': lyr_calle,
            '<b>Calles por tipo de calzada</b><span><div style="margin-left: 17px;"><img src="images/calle-por-tipo-calzada.png" /></div></span>': vw_ide_calle_por_tipo_calzada
        },{
            collapsed: isMobile()
        }).addTo(map);

        setBounds();

        L.control.scale().addTo(map);    
        </script>

        <!-- bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script>

            $(document).ready(function(){

                $('#mensajes,#msg-no-encontre').dialog({autoOpen:false});

                //$('#map').css('z-index', 9);

                $('#btnBuscar').click(function(){

                    if (document.getElementById('nombre_calle').value === "" ) {

                        $("#mensajes").dialog({
                            modal: true,
                            escapeClose: false,
                            showClose: false,
                            title: "Aviso!!",
                            width: 450,
                            buttons: {
                                Ok: function() { $(this).dialog("close"); }
                            }
                        }).css('zIndex', 1050);
                        
                        return false;
                    }

                    $.ajax( "busca_calle.php", {
                        data: 'nombre_calle=' + document.getElementById('nombre_calle').value,
                        method: 'POST',
                        success: function(response){

                            if (response == '-1') {
                                
                                $("#msg-no-encontre").dialog({
                                    modal: true,
                                    escapeClose: false,
                                    showClose: false,
                                    title: "Aviso!!",
                                    width: 450,
                                    buttons: {
                                        Ok: function() { $(this).dialog("close"); }
                                    }
                                }).css('zIndex', 1050);

                                return false;
                            }

                            var myStyle = {
                                "color": "#ff7800",
                                "weight": 5,
                                "opacity": 0.65
                            };
                           
                            capas.push(L.geoJSON(JSON.parse(response), {style: myStyle}).addTo(map));
                            // L.geoJSON(response, {style: myStyle}).addTo(map);
                        }
                    });
                });
                
                $('#btnLimpiar').click(function(e) {
                    capas.forEach(function(item, index){
                        item.remove();
                    });
                    capas = [];
                });
            })
        </script>
    </body>
</html>
