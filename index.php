<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>WebGIS SMA Negeri DIY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-mouse-position@1.2.0/src/L.Control.MousePosition.css" />

    <style>
        :root { --pink: #F6C1CC; --sage: #A8C3B2; --blue: #B7D7F0; --dark-sage: #5E7F73; }
        body { font-family: 'Poppins', sans-serif; background: #FAFAFA; }
        .navbar { background: linear-gradient(90deg, var(--pink), var(--blue)); }
        .navbar-brand { font-weight: 600; }
        .hero-card { background: white; border-radius: 18px; padding: 30px; border-left: 8px solid var(--sage); box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        #map { height: 520px; border-radius: 20px; box-shadow: 0 12px 30px rgba(0,0,0,.12); }
        .section-title { font-weight: 600; color: var(--dark-sage); }
        .tools-container { margin-top: 20px; padding-top: 20px; border-top: 2px solid #f8f8f8; }
        .search-box input { border-radius: 25px; padding: 10px 20px; border: 2px solid var(--sage); max-width: 400px; }
        .filter-container { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 10px; }
        .form-check-input:checked { background-color: var(--dark-sage); border-color: var(--dark-sage); }
        .legend-container { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px; }
        .legend-item { display: flex; align-items: center; font-size: 0.8rem; color: #666; background: #fff; padding: 6px 14px; border-radius: 20px; border: 1px solid #eee; }
        .legend-color { width: 12px; height: 12px; border-radius: 50%; margin-right: 8px; }
        .btn-custom-info { background-color: var(--sage) !important; color: white !important; font-weight: 600; border: none; }
        .btn-custom-web { background-color: var(--blue) !important; color: #555 !important; font-weight: 600; border: none; }
        .table-section { background: white; border-radius: 18px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,.05); margin-top: 30px; }
        .table { font-size: 0.85rem; min-width: 1200px; }
        .table thead { background-color: var(--sage); color: white; }
        .table-responsive { border-radius: 12px; overflow-x: auto; }
        .col-desc { min-width: 300px; white-space: normal; }

        /* Hanya ini yang diubah: badge Status "Negeri" pakai hijau section-title */
        .badge-negeri {
            background-color: #5E7F73;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container"><span class="navbar-brand">🌸 <strong>WebGIS SMA Negeri DIY</strong></span></div>
</nav>

<section class="container my-5">
    <div class="hero-card">
        <h4 class="section-title">Sebaran SMA Negeri di Daerah Istimewa Yogyakarta</h4>
        <p class="text-muted mb-0">WebGIS interaktif untuk menampilkan persebaran SMA Negeri di DIY sebagai pendukung analisis spasial bidang pendidikan.</p>
        <div class="tools-container">
            <div class="search-box mb-3"><input type="text" id="inputSearch" class="form-control shadow-sm" placeholder="🔍 Cari Nama SMA Negeri..."></div>

            <div class="filter-container">
                <div class="form-check"><input class="form-check-input filter-kab" type="checkbox" value="kota yogyakarta" id="c1" checked><label class="form-check-label small" for="c1">Kota Jogja</label></div>
                <div class="form-check"><input class="form-check-input filter-kab" type="checkbox" value="sleman" id="c2" checked><label class="form-check-label small" for="c2">Sleman</label></div>
                <div class="form-check"><input class="form-check-input filter-kab" type="checkbox" value="bantul" id="c3" checked><label class="form-check-label small" for="c3">Bantul</label></div>
                <div class="form-check"><input class="form-check-input filter-kab" type="checkbox" value="kulon progo" id="c4" checked><label class="form-check-label small" for="c4">Kulon Progo</label></div>
                <div class="form-check"><input class="form-check-input filter-kab" type="checkbox" value="gunungkidul" id="c5" checked><label class="form-check-label small" for="c5">Gunungkidul</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" value="wms" id="toggleWMS" checked><label class="form-check-label small" for="toggleWMS">Tampilkan Batas Administrasi (WMS)</label></div>
            </div>

            <div class="legend-container mt-2">
                <div class="legend-item"><span class="legend-color" style="background: #ff1493;"></span> Kota Yogyakarta</div>
                <div class="legend-item"><span class="legend-color" style="background: #4A90E2;"></span> Sleman / Kabupaten Sleman</div>
                <div class="legend-item"><span class="legend-color" style="background: #8a2be2;"></span> Bantul / Kabupaten Bantul</div>
                <div class="legend-item"><span class="legend-color" style="background: #FFF5BA;"></span> Kabupaten Kulonprogo</div>
                <div class="legend-item"><span class="legend-color" style="background: #CCE2CB;"></span> Gunung Kidul</div>
                <div class="legend-item"><span class="legend-color" style="background: #FFD8A8;"></span> Kabupaten Magelang</div>
                <div class="legend-item"><span class="legend-color" style="background: #A3E4D7;"></span> Kabupaten Purworejo</div>
                <div class="legend-item"><span class="legend-color" style="background: #FAD2E1;"></span> Klaten</div>
                <div class="legend-item"><span class="legend-color" style="background: #D0E1F9;"></span> Wonogiri</div>
            </div>
        </div>
    </div>
</section>

<section class="container mb-4">
    <div id="map"></div>
</section>

<section class="container mb-5">
    <div class="table-section">
        <h5 class="section-title mb-3">📋 Data Lengkap Sekolah (Dapat Digeser ke Samping)</h5>
        <div class="table-responsive">
            <table class="table table-hover table-bordered border-light align-middle">
                <thead>
                    <tr>
                        <th>Nama Sekolah</th>
                        <th>Jenjang</th>
                        <th>Status</th>
                        <th>Kabupaten</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th class="col-desc">Deskripsi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</section>

<footer class="text-center pb-3 text-muted small">Azzahra Reisa Putri | NIM 24/540468/SV/24849</footer>

<div class="modal fade" id="infoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: var(--sage); color:white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="modalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-mouse-position@1.2.0/src/L.Control.MousePosition.js"></script>

<script>
    var map = L.map('map').setView([-7.797068, 110.370529], 10);
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    var esri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
    osm.addTo(map);

    var layerWMS = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pendidikan_diy/wms", {
            layers: 'pendidikan_diy:merge',
            format: 'image/png',
            transparent: true,
            version: '1.1.1',
            attribution: "Batas Administrasi DIY"
    }).addTo(map);

    document.getElementById('toggleWMS').addEventListener('change', function() {
        if (this.checked) { map.addLayer(layerWMS); } 
        else { map.removeLayer(layerWMS); }
    });

    var baseMaps = { "Jalan (OSM)": osm, "Satelit (Esri)": esri };
    var overlayMaps = { "Batas Administrasi (WMS)": layerWMS };
    L.control.layers(baseMaps, overlayMaps).addTo(map);

    L.control.mousePosition({position: 'bottomleft', prefix: 'Koordinat: '}).addTo(map);

    var markersLayer = L.layerGroup().addTo(map);
    var allSchools = [];

    function warnaIcon(w) { return L.divIcon({ className: '', html: `<div style="background:${w};width:18px;height:18px;border-radius:50%;border:3px solid white;box-shadow:0 0 5px rgba(0,0,0,0.3);"></div>` }); }

    <?php
    $conn = new mysqli("localhost", "root", "", "pendidikan_diy");
    $sql = "SELECT * FROM sekolah WHERE jenjang='SMA' AND status='Negeri'";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()){
        $nama = addslashes($r['nama_sekolah']); 
        $web = $r['website']; 
        $info = addslashes($r['deskripsi']); 
        $kab = strtolower($r['kabupaten']);
        $color = (strpos($kab,'yogyakarta')!==false?'#ff1493':
                  (strpos($kab,'sleman')!==false?'#4A90E2':
                  (strpos($kab,'bantul')!==false?'#8a2be2':
                  (strpos($kab,'kulon')!==false?'#ffd700':'#32cd32'))));
        echo "allSchools.push({ 
            name:'$nama', 
            lat:'{$r['latitude']}', 
            lng:'{$r['longitude']}', 
            web:'$web', 
            info:'$info', 
            kab:'$kab', 
            color:'$color', 
            jenjang:'{$r['jenjang']}', 
            status:'{$r['status']}' 
        });";
    }
    ?>

    function render() {
        markersLayer.clearLayers();
        var tableContent = "";
        var searchVal = document.getElementById('inputSearch').value.toLowerCase();
        var checkedKabs = Array.from(document.querySelectorAll('.filter-kab:checked')).map(cb => cb.value);

        allSchools.forEach(function(s) {
            if (s.name.toLowerCase().includes(searchVal) && checkedKabs.some(k => s.kab.includes(k))) {
                L.marker([s.lat, s.lng], { icon: warnaIcon(s.color) })
                    .bindPopup("<div style='text-align:center;'><strong>"+s.name+"</strong><br><br><button class='btn btn-sm btn-custom-info w-100 mb-2' onclick=\"showModal('"+s.name+"','"+s.info+"')\">LIHAT INFO</button><a href='"+s.web+"' target='_blank' class='btn btn-sm btn-custom-web w-100'>WEB SEKOLAH</a></div>")
                    .addTo(markersLayer);
                
                tableContent += "<tr><td><strong>"+s.name+"</strong></td><td>"+s.jenjang+"</td><td><span class='badge badge-negeri'>"+s.status+"</span></td><td>"+s.kab.toUpperCase()+"</td><td>"+s.lat+"</td><td>"+s.lng+"</td><td class='col-desc'>"+s.info+"</td></tr>";
            }
        });
        document.getElementById('tableBody').innerHTML = tableContent;
    }

    function showModal(n, i) { document.getElementById('modalTitle').innerText = n; document.getElementById('modalBody').innerText = i; new bootstrap.Modal(document.getElementById('infoModal')).show(); }
    document.getElementById('inputSearch').addEventListener('input', render);
    document.querySelectorAll('.filter-kab').forEach(cb => cb.addEventListener('change', render));
    render();
</script>
</body>
</html>
