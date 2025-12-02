document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Cek Apakah Container Peta Ada (Agar tidak error di halaman lain)
    var mapContainer = document.getElementById('map-container');
    if (!mapContainer) return;

    // 2. Init Map (Pusatkan di Kendari)
    const map = L.map('map-container', {
        zoomControl: false // Kita matikan zoom default, nanti pindah ke bawah (opsional)
    }).setView([-3.9985, 122.5126], 12);

    // Tambah Zoom Control di kanan bawah agar lebih manis
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    
    // 3. Tambahkan Tile Layer (Peta Dasar)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '' // Kita kosongkan attribution sesuai request sebelumnya
    }).addTo(map);

    const points = window.landingMapData || [];

    // 5. Render Titik
    if(points.length > 0) {
        points.forEach(p => {
            // Tentukan Warna berdasarkan Level (0, 1, 2)
            let color = 'gray'; // Default
            let radius = 6;

            if (p.level == 0) {
                color = '#1cc88a'; // Hijau (Aman)
            } else if (p.level == 1) {
                color = '#f6c23e'; // Kuning (Sedang)
            } else if (p.level >= 2) {
                color = '#e74a3b'; // Merah (Rawan)
            }

            // Gambar Lingkaran
            L.circleMarker([p.lat, p.lng], {
                radius: radius,
                fillColor: color,
                color: '#fff', // Garis pinggir putih
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map)
            .bindPopup(`
                <div style="text-align: center;">
                    <strong style="color:#333;">${p.kecamatan}</strong><br>
                    <span class="badge" style="background:${color}; color:#fff; padding:2px 5px; border-radius:4px; font-size:10px;">
                        ${p.status}
                    </span>
                </div>
            `);
        });
    }
});