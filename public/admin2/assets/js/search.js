document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('kriminalTableBody');
    
    // Jika salah satu elemen penting tidak ada di halaman, hentikan script
    if (!searchInput || !tableBody) {
        return;
    }
    
    // Ambil semua baris data asli dan 'pesan kosong' dari tabel
    const allRows = tableBody.querySelectorAll('tr');
    const noDataRow = document.getElementById('noDataFoundRow'); // Baris yang akan kita tambahkan

    // Fungsi untuk memfilter tabel
    const filterTable = () => {
        const query = searchInput.value.toLowerCase(); // Ambil teks pencarian, ubah ke huruf kecil
        let visibleRows = 0;

        // Loop melalui setiap baris di dalam tbody
        allRows.forEach(row => {
            // Abaikan baris "tidak ditemukan" saat melakukan looping
            if(row.id === 'noDataFoundRow') return;

            // Ambil teks dari setiap sel di baris, ubah ke huruf kecil
            const rowText = row.textContent.toLowerCase();
            
            // Periksa apakah teks baris mengandung query pencarian
            if (rowText.includes(query)) {
                row.style.display = ''; // Tampilkan baris jika cocok
                visibleRows++;
            } else {
                row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
            }
        });

        // Tampilkan atau sembunyikan pesan "tidak ditemukan"
        if (noDataRow) {
            if (visibleRows === 0) {
                noDataRow.style.display = ''; // Tampilkan jika tidak ada baris yang cocok
            } else {
                noDataRow.style.display = 'none'; // Sembunyikan jika ada baris yang cocok
            }
        }
    };

    // Tambahkan event listener 'keyup' pada kotak pencarian
    searchInput.addEventListener('keyup', filterTable);
});