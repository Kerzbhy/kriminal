document.addEventListener("DOMContentLoaded", function() {
    const kendariCenter = [-3.998, 122.512];

    // Inisialisasi peta tanpa bounds
    const map = L.map('map', {
        zoomControl: true
    }).setView(kendariCenter, 12);

    // Tambahkan layer OSM tanpa attribution
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        minZoom: 4,
        attribution: '' // kosong agar tidak ada tulisan
    }).addTo(map);

    const loader = document.getElementById('map-loading');

    // Hilangkan loading ketika semua tile sudah dimuat
    map.on('load', () => {
        if (loader) {
            loader.classList.add('hidden');
            setTimeout(() => loader.remove(), 500);
        }
    });

    // Cadangan: pastikan spinner hilang setelah 4 detik walau load gagal
    setTimeout(() => {
        if (loader && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden');
            setTimeout(() => loader.remove(), 500);
        }
    }, 4000);

    // Marker dihapus agar titik koordinat tidak muncul
    L.marker(kendariCenter)
        .addTo(map)
        .bindPopup("<b>Kota Kendari</b><br>Pusat Wilayah Kriminalitas")
        .openPopup();

    // Paksa refresh ukuran peta setelah render
    setTimeout(() => map.invalidateSize(), 500);
});
