document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SETUP MAP ---
    const mapCenter = [-3.9985, 122.5126]; // Kendari
    // matikan zoomControl default agar bisa dipindah posisinya (opsional)
    const map = L.map('map', {zoomControl: false}).setView(mapCenter, 13);
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    
    const loader = document.getElementById('map-loading');

    // --- 2. LAYER PETA DASAR ---
    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // --- 3. AMBIL DATA & FORMAT RUPIAH ---
    const dataGroups = window.crimeData || [];
    
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        }).format(angka);
    };

    // --- 4. SIAPKAN LAYER GROUP & DATA HEATMAP ---
    var markerLayerGroup = L.layerGroup(); // Wadah titik-titik cluster
    var heatPoints = [];                   // Wadah koordinat panas [[lat, lng, intensitas]]

    if (dataGroups.length > 0) {
        dataGroups.forEach(function(group) {
            
            // Warna & Intensitas (C3 Rawan = Panas, C1 Aman = Dingin)
            const color = group.backgroundColor;
            const label = group.label;
            const isWarning = (group.level === 1);
            
            // Logic intensitas: Level 2(Rawan)=1.0, Level 1(Sedang)=0.6, Level 0(Aman)=0.3
            const intensity = (group.level === 2) ? 1.0 : (group.level === 1 ? 0.6 : 0.3);

            if (group.data && group.data.length > 0) {
                group.data.forEach(function(point) {
                    if (point.lat && point.lng) {
                        
                        // A. SIAPKAN TITIK CLUSTER
                        const popupContent = `
                            <div class="text-center mb-1"><strong style="font-size:0.9rem">${point.kecamatan}</strong></div>
                            <div class="text-center mb-2">
                                <span class="badge" style="background-color: ${color}; color: ${isWarning ? '#000' : '#fff'}; border: 1px solid #fff;">
                                    ${label}
                                </span>
                            </div>
                            <table class="w-100 table-sm small">
                                <tr><td class="text-muted">Kejahatan</td><td class="fw-bold text-end">${point.jenis || '-'}</td></tr>
                                <tr><td class="text-muted">Rugi</td><td class="fw-bold text-end text-danger">${formatRupiah(point.rugi || 0)}</td></tr>
                            </table>
                        `;

                        const circle = L.circleMarker([point.lat, point.lng], {
                            radius: 7, fillColor: color, color: "#fff", weight: 1.5, opacity: 1, fillOpacity: 0.85
                        }).bindPopup(popupContent);

                        markerLayerGroup.addLayer(circle); // Masukkan ke group, jangan addTo map dulu

                        // B. SIAPKAN DATA HEATMAP
                        heatPoints.push([point.lat, point.lng, intensity]);
                    }
                });
            }
        });
    }

    // --- 5. INITIALIZE LAYERS ---
    
    // Default: Tampilkan Layer Cluster saat pertama buka
    markerLayerGroup.addTo(map);

    // Buat Layer Heatmap (Jika plugin tersedia dan data ada)
    var heatmapLayer = null;
    if (typeof L.heatLayer === 'function' && heatPoints.length > 0) {
        heatmapLayer = L.heatLayer(heatPoints, {
            radius: 35, // Radius sebaran api
            blur: 25,   // Efek kabut
            maxZoom: 15,
            gradient: {0.2: 'blue', 0.4: 'lime', 0.6: 'yellow', 0.9: 'red', 1.0: '#800000'}
        });
    } else {
        console.warn("Plugin Heatmap tidak terdeteksi atau data kosong.");
    }

    // --- 6. LOGIKA TOMBOL SWITCH HTML ---
    // Pastikan ID di HTML sama dengan ID di sini ('switchCluster', 'switchHeatmap')
    
    const btnCluster = document.getElementById('switchCluster');
    const btnHeatmap = document.getElementById('switchHeatmap');

    if(btnCluster) {
        btnCluster.addEventListener('change', function() {
            if (this.checked) map.addLayer(markerLayerGroup);
            else map.removeLayer(markerLayerGroup);
        });
    }

    if(btnHeatmap && heatmapLayer) {
        btnHeatmap.addEventListener('change', function() {
            if (this.checked) map.addLayer(heatmapLayer);
            else map.removeLayer(heatmapLayer);
        });
    }

    // --- 7. HANDLING LOADER ---
    const hideLoader = () => {
        if (loader && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden');
            setTimeout(() => { if(loader) loader.remove(); }, 500);
        }
    };
    
    // Beri delay 1.5 detik agar loading terasa
    tiles.on('load', () => setTimeout(hideLoader, 1500));
    setTimeout(hideLoader, 4000); // Fallback
    setTimeout(() => map.invalidateSize(), 500); // Fix render
});