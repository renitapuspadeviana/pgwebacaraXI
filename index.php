<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- leaflet css link  -->
    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />

    <title>Web-GIS with Geoserver and Leaflet</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #map {
            width: 100%;
            height: 100vh;
        }

        /* ============================
          JUDUL MAP (GRADIENT PREMIUM)
        ============================= */
        #map-title {
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 35px;
            font-size: 22px;
            font-weight: bold;
            color: white;
            border-radius: 12px;

            /* Gradasi mewah */
            background: linear-gradient(135deg, #4b79a1, #283e51);

            /* Shadow elegan */
            box-shadow: 0 4px 14px rgba(0,0,0,0.35),
                        0 0 8px rgba(255,255,255,0.15) inset;

            letter-spacing: 1px;
            z-index: 1200;

            /* Blur kaca halus */
            backdrop-filter: blur(3px);
        }

        /* ============================
           KOTAK INFO / CREDIT
        ============================= */
        #info-box {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(255,255,255,0.85);
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 13px;
            font-family: Arial;
            box-shadow: 0 0 10px rgba(0,0,0,0.25);
            z-index: 1000;
        }

        /* Legend */
        .legend {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
            line-height: 24px;
            color: #333;
        }
        .legend h4 {
            margin: 0 0 5px;
            color: #333;
        }
        .legend img {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <!-- JUDUL BARU -->
    <div id="map-title">WEBGIS • KABUPATEN SLEMAN</div>

    <!-- MAP -->
    <div id="map"></div>

    <!-- Info kecil -->
    <div id="info-box">WebGIS by Renita • Powered by Leaflet & GeoServer</div>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
      // ===============================
      // MAP DASAR
      // ===============================
        var map = L.map("map").setView([-7.732521, 110.402376], 11);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "© OpenStreetMap contributors",
        }).addTo(map);

      // ===============================
      // LAYER WMS DARI GEOSERVER
      // ===============================

        var desa = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgwebx/wms",
        {
            layers: "pgwebx:ADMINKEC_SLEMAN_FIXPOL",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var jalan_geoportal = L.tileLayer.wms(
        "https://geoportal.slemankab.go.id/geoserver/geonode/wms",
        {
            layers: "geonode:jalan_kabupaten_sleman_2023",
            format: "image/png",
            transparent: true,
        }).addTo(map);

        var jalan_local = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgwebx/wms",
        {
            layers: "pgwebx:JALAN_LN_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var kecamatan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgwebx/wms",
        {
            layers: "pgwebx:penduduksleman_view",
            format: "image/png",
            transparent: true
        }).addTo(map);

      // ===============================
      // LAYER CONTROL
      // ===============================
        var overlayLayers = {
            "Administrasi Kecamatan": desa,
            "Jalan Geoportal Sleman": jalan_geoportal,
            "Jalan 25K": jalan_local,
            "Data Kecamatan": kecamatan
        };

        L.control.layers(null, overlayLayers).addTo(map);

      // ===============================
      // LEGEND
      // ===============================
        var legend = L.control({position: 'bottomleft'});

        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.innerHTML =
                '<h4>Legenda</h4>' +
                '<div>Administrasi Kecamatan</div><img src="http://localhost:8080/geoserver/pgwebx/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=pgwebx:ADMINKEC_SLEMAN_FIXPOL"><br>' +
                '<div>Jalan</div><img src="http://localhost:8080/geoserver/pgwebx/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=pgwebx:JALAN_LN_25K"><br>' +
                '<div>Data Kecamatan</div><img src="http://localhost:8080/geoserver/pgwebx/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=pgwebx:penduduksleman_view">';

            return div;
        };

        legend.addTo(map);

    </script>
</body>
</html>
