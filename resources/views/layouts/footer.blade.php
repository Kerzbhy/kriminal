<!--   Core JS Files   -->
<script src="{{ asset('admin2/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin2/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin2/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin2/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script>

    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('admin2/assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>


<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('sweet/dist/sweetalert2.all.min.js') }}"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
    setInterval(() => {
        fetch('/check-sync-token', { credentials: 'same-origin' })
            .then(res => res.json())
            .then(data => {
                if (!data.valid) {
                    // redirect ke logout otomatis jika token hilang
                    window.location.href = '/logout';
                }
            });
    }, 5000); // cek tiap 5 detik
</script>


@session('success')
    <script>Swal.fire({
            title: "Sukses!",
            text: "{{ session('success') }}",
            icon: "success"
        });</script>
@endsession

@session('error')
    <script>Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error"
        });</script>
@endsession

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll('.nav-link[data-menu]');

        // Hapus semua class active (biar bisa dikelola JS)
        navLinks.forEach(link => link.classList.remove('active', 'bg-gradient-dark', 'text-white'));

        // Ambil menu aktif dari localStorage
        const activeMenu = localStorage.getItem('activeMenu');
        if (activeMenu) {
            const activeLink = document.querySelector(`.nav-link[data-menu="${activeMenu}"]`);
            if (activeLink) {
                activeLink.classList.add('active', 'bg-gradient-dark', 'text-white');
            }
        }

        // Simpan pilihan menu ke localStorage saat diklik
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                const menu = this.getAttribute('data-menu');
                localStorage.setItem('activeMenu', menu);
            });
        });
    });
</script>


</body>
