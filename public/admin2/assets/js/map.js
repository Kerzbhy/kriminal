

document.addEventListener("DOMContentLoaded", function() {
    
    // 1. SETUP MAP
    const mapCenter = [-3.9985, 122.5126]; // Kendari
    const map = L.map('map').setView(mapCenter, 13);
    const loader = document.getElementById('map-loading');

    // 2. LAYER PETA (OpenStreetMap)
    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        minZoom: 3,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 3. FUNGSI LOADER (Spinner)
    const hideLoader = () => {
        if (loader && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden'); 
            // Hapus elemen dari HTML setelah animasi selesai (500ms)
            setTimeout(() => { 
                if(loader) loader.remove(); 
            }, 500);
        }
    };

    // Hilangkan loader saat tile siap, atau setelah 3 detik (fallback)
    tiles.on('load', hideLoader);
    setTimeout(hideLoader, 4000);

    // 4. AMBIL DATA DARI JEMBATAN BLADE
    // Kita baca variabel global yang nanti kita definisikan di Blade
    const dataGroups = window.crimeData || [];

    // Debugging
    console.log("--------------------------------");
    console.log("Crime Map JS Loaded");
    console.log("Data diterima:", dataGroups.length, "Group");

    // 5. HELPER FORMAT RUPIAH
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        }).format(angka);
    };

    // 6. LOGIKA RENDER TITIK
    let pointCount = 0;

    if (dataGroups.length > 0) {
        dataGroups.forEach(function(group) {
            
            // Ambil style dari Controller
            const color = group.backgroundColor;
            const label = group.label;
            const isWarning = (group.level === 1); // C2 (Kuning)

            if (group.data && group.data.length > 0) {
                group.data.forEach(function(point) {
                    
                    // Pastikan koordinat ada dan valid
                    if (point.lat && point.lng) {
                        pointCount++;
                        
                        // Isi Popup
                        const popupContent = `
                            <div class="text-center mb-2">
                                <h6 class="fw-bold mb-1" style="font-size:0.9rem">${point.kecamatan}</h6>
                                <span class="badge border" style="background-color: ${color}; color: ${isWarning ? '#000' : '#fff'};">
                                    ${label}
                                </span>
                            </div>
                            <table class="w-100 table-sm small">
                                <tr>
                                    <td class="text-muted">Kejahatan</td>
                                    <td class="fw-bold text-end text-dark">${point.jenis || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kerugian</td>
                                    <td class="fw-bold text-end text-danger">${formatRupiah(point.rugi || 0)}</td>
                                </tr>
                            </table>
                        `;

                        // Render Circle Marker
                        L.circleMarker([point.lat, point.lng], {
                            radius: 8,
                            fillColor: color,
                            color: "#fff",
                            weight: 1.5,
                            opacity: 1,
                            fillOpacity: 0.85
                        })
                        .addTo(map)
                        .bindPopup(popupContent);
                    }
                });
            }
        });
        
        console.log(`Total titik digambar: ${pointCount}`);
    } else {
        console.warn("Data kosong! Pastikan sudah melakukan Clustering di menu sebelumnya.");
    }

    // Fix bug tampilan peta separuh saat load
    setTimeout(() => map.invalidateSize(), 500);
});