/**
 * MINIMAP.JS - FINAL FIXED
 */

var miniMapInstance = null;

// 1. SCATTER PLOT
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartClustering');
    const chartData = window.clusterChartData || null;

    if (ctx && chartData) {
        new Chart(ctx, {
            type: 'scatter',
            data: { datasets: chartData },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(c) { return c.dataset.label; }
                        }
                    }
                },
                scales: {
                    x: { title: { display: true, text: 'Longitude' } },
                    y: { title: { display: true, text: 'Latitude' } }
                }
            }
        });
    }
});

// 2. FUNGSI SHOW DETAIL (MODAL & MAP)
function showDetail(kecamatan, label, dataList) {
    
    // A. Update Teks Judul
    const titleEl = document.getElementById('detailTitle');
    if (titleEl) titleEl.innerText = kecamatan;

    // B. Update Badge (INI PERBAIKANNYA)
    const badgeEl = document.getElementById('detailBadge'); // Kita namakan badgeEl
    
    if (badgeEl) {
        badgeEl.innerText = label; // Gunakan badgeEl, BUKAN badge
        badgeEl.className = 'badge ms-2'; // Reset class

        if(label.includes('NOISE')) badgeEl.classList.add('bg-secondary');
        else if(label.includes('Aman') || label.includes('C1')) badgeEl.classList.add('bg-success');
        else if(label.includes('Sedang') || label.includes('C2')) badgeEl.classList.add('bg-warning', 'text-dark');
        else badgeEl.classList.add('bg-danger');
    }

    // C. Isi Tabel Detail
    var tbody = document.getElementById('detailTableBody');
    if(tbody) {
        tbody.innerHTML = ''; 
        const formatRupiah = (num) => new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(num);

        if(dataList && dataList.length > 0) {
            dataList.forEach(item => {
                let row = `<tr>
                    <td>${item.jenis}</td>
                    <td class="text-end fw-bold">${formatRupiah(item.rugi)}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data detail</td></tr>';
        }
    }

    // D. Tampilkan Modal (jQuery / Bootstrap)
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalDetail').modal('show'); // Cara Paling Aman di Template Dashboard
    } else {
        var modalEl = document.getElementById('modalDetail');
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    // E. Render Peta Mini (Delay)
    setTimeout(() => {
        initMiniMap(dataList);
    }, 500);
}

// 3. FUNGSI RENDER MAP KECIL
function initMiniMap(points) {
    // Cek elemen
    if(!document.getElementById('miniMap')) return;

    // Reset Map Lama
    if (miniMapInstance) {
        miniMapInstance.off();
        miniMapInstance.remove();
        miniMapInstance = null;
    }

    // Init Map Baru
    miniMapInstance = L.map('miniMap').setView([-3.9985, 122.5126], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(miniMapInstance);

    // Tambah Marker
    if(points && points.length > 0) {
        var bounds = [];
        points.forEach(p => {
            if(p.lat && p.lng) {
                L.circleMarker([p.lat, p.lng], {
                    radius: 6,
                    fillColor: '#4e73df',
                    color: '#fff',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(miniMapInstance).bindPopup(`<b>${p.jenis}</b>`);
                
                bounds.push([p.lat, p.lng]);
            }
        });

        // Zoom ke lokasi
        if(bounds.length > 0) {
            miniMapInstance.fitBounds(bounds, {padding: [50, 50]});
        }
    }
    
    setTimeout(() => {
        miniMapInstance.invalidateSize();
    }, 100);
}