<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Pengelompokkan Kriminalitas</title>
  <meta name="description" content="Sistem Informasi Geografis Kriminalitas Kota Kendari">

  <!-- Favicons -->
  <link href="{{ asset('enno/assets/img/polisi.png') }}" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Raleway:wght@500;700&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('enno/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('enno/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('enno/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('enno/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('enno/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- LEAFLET CSS (PENTING UTK PETA) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- Main CSS File -->
  <link href="{{ asset('enno/assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('enno/assets/css/map.css') }}" rel="stylesheet">

</head>

<body class="index-page">

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container custom-navbar-container">
      <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="#" class="logo d-flex align-items-center me-auto">
          <img src="{{ asset('enno/assets/img/pol.png') }}" alt="pol">
          <h1 class="sitename">SISKRIM</h1>
        </a>
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#map">Peta & Data</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>

  <main class="main">

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero section d-flex align-items-center justify-content-center">
      <div class="container text-center text-white" data-aos="zoom-out">
        <h1>Sistem Pengelompokan Kriminalitas</h1>
        <p>Analisis persebaran titik rawan kejahatan berbasis wilayah di Kota Kendari</p>
        <a href="#map" class="btn-get-started">Lihat Peta Persebaran</a>
      </div>
    </section>

    <!-- ======= About Section ======= -->
    <section id="about" class="about section">
      <div class="container section-title" data-aos="fade-up">
        <h2>TENTANG SISKRIM</h2>
        {{-- Kalimat ini lebih profesional --}}
        <p class="mx-auto" style="max-width: 800px;">
          Sistem Informasi Geografis dan Pendukung Keputusan untuk menganalisis tingkat kerawanan kriminalitas
          di Kota Kendari menggunakan pendekatan sains data.
        </p>
      </div>

      <div class="container">
        <div class="row gy-4 justify-content-center mt-2">

          {{-- BOX 1: CLUSTERING --}}
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item item-cyan position-relative text-center p-4 shadow-sm h-100 rounded">
              <i class="bi bi-geo-alt icon fs-1 text-primary"></i>
              <h3 class="mt-3">Clustering Spasial (DBSCAN)</h3>
              {{-- Penjelasan teknis sederhana --}}
              <p>
                Mengelompokkan titik lokasi kejadian secara otomatis berdasarkan kepadatan area
                untuk menemukan zona kriminalitas yang akurat tanpa bias wilayah manual.
              </p>
            </div>
          </div>

          {{-- BOX 2: TOPSIS --}}
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item item-orange position-relative text-center p-4 shadow-sm h-100 rounded">
              <i class="bi bi-bar-chart-steps icon fs-1 text-warning"></i>
              <h3 class="mt-3">Prioritas Penanganan (TOPSIS)</h3>
              {{-- Menekankan pada Multikriteria --}}
              <p>
                Menentukan peringkat kecamatan paling rawan dengan perhitungan matematis yang objektif
                berdasarkan kriteria: Jumlah Kasus, Jenis Kejahatan, Kerugian, dan Kepadatan Penduduk.
              </p>
            </div>
          </div>

          {{-- BOX 3: VISUALISASI --}}
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item item-teal position-relative text-center p-4 shadow-sm h-100 rounded">
              <i class="bi bi-map icon fs-1 text-success"></i>
              <h3 class="mt-3">Visualisasi Geografis (WebGIS)</h3>
              {{-- Menekankan kemudahan user --}}
              <p>
                Menyajikan data dalam bentuk <strong>Peta Sebaran Titik</strong> dan <strong>Heatmap</strong>
                interaktif agar masyarakat dan aparat dapat memantau kondisi keamanan secara real-time.
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ======= PETA & DATA SECTION (BAGIAN PENTING) ======= -->
    <section id="map" class="contact section bg-light py-5">

      <div class="container section-title" data-aos="fade-up">
        <h2>Peta Persebaran & Peringkat Wilayah</h2>
        <p>Berikut adalah hasil analisis terbaru titik rawan kriminalitas.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">

          <!-- KOLOM KIRI: PETA -->
          <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold"><i class="bi bi-map-fill text-primary me-2"></i>Peta Persebaran Titik Lokasi
                </h6>
              </div>
              <div class="card-body p-0">
                <div id="map-container"></div> <!-- Container Peta Leaflet -->
              </div>
              <div class="card-footer bg-white text-muted small">
                <span class="badge bg-danger me-1">Merah = Rawan</span>
                <span class="badge bg-warning text-dark me-1">Kuning = Sedang</span>
                <span class="badge bg-success me-1">Hijau = Aman</span>
                <span class="badge bg-secondary ">Noise = Anomali</span>
              </div>
            </div>
          </div>

          <!-- KOLOM KANAN: TABEL TOP 5 -->
          <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i>Top 5 Wilayah Rawan</h6>
              </div>
              <div class="card-body p-0">
                @if(!empty($rankingData))
                  <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                      <thead class="table-light small">
                        <tr>
                          <th class="ps-3">#</th>
                          <th>Kecamatan</th>
                          <th class="text-end pe-3">Skor (V)</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rankingData as $idx => $row)
                          <tr>
                            <td class="ps-3 fw-bold">{{ $idx + 1 }}</td>
                            <td>
                              <span class="fw-bold">{{ $row['kecamatan'] }}</span><br>
                              <small class="text-{{ $idx < 3 ? 'danger' : 'warning' }}">
                                {{ $idx < 3 ? 'Prioritas Tinggi' : 'Waspada' }}
                              </small>
                            </td>
                            <td class="text-end pe-3">
                              <span
                                class="badge bg-{{ $idx == 0 ? 'danger' : ($idx == 1 ? 'danger' : 'secondary') }} badge-rank">
                                {{ number_format($row['skor'], 4) }}
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="text-center p-5 text-muted">
                    <i class="bi bi-info-circle fs-3"></i><br>
                    <p class="small mt-2">Belum ada data perhitungan saat ini.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>

  </main>

  <footer id="footer" class="footer py-4 border-top">
    <div class="container text-center">
      <p class="mb-0">&copy; {{ date('Y') }} <strong>SISKRIM</strong>. Sistem Pendukung Keputusan Kriminalitas Kota
        Kendari.</p>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('enno/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('enno/assets/js/main.js') }}"></script>
  <script src="{{ asset('enno/assets/js/map.js') }}"></script>

   <!-- LEAFLET JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- JEMBATAN DATA & PEMANGGIL FILE JS -->
  <script>
      window.landingMapData = @json($mapData ?? []);
  </script>

</body>

</html>