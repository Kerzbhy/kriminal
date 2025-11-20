<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Pengelompokkan Kriminalitas</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

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

  <!-- Main CSS File -->
  <link href="{{ asset('enno/assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    {{-- Kita akan membungkus 'container-fluid' di dalam 'div' baru --}}
    <div class="container custom-navbar-container">
      <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="#" class="logo d-flex align-items-center me-auto">
          <img src="{{ asset('enno/assets/img/pol.png') }}" alt="pol">
          <h1 class="sitename">Si-Krim</h1>
        </a>
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#data">Data</a></li>
            <li><a href="#map">Peta</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>
    </div>
  </header>
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero section d-flex align-items-center justify-content-center">
    <div class="container text-center text-white">
      <h1>Sistem Pengelompokan Kriminalitas</h1>
      <p>Aplikasi pengelompokkan kriminalitas berbasis wilayah berdasarkan kepadatan penduduk</p>
      <a href="{{ route('login') }}" class="btn-get-started">Get Started</a>
    </div>
  </section>
  <!-- End Hero Section -->

  <!-- ======= About Section ======= -->
  <section id="about" class="about section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Tentang Si-Krim</h2>
      <p>Sebuah Sistem Pendukung Keputusan untuk Analisis Spasial Kriminalitas di Kota Kendari.</p>
    </div><!-- End Section Title -->

    <div class="container">

      <div class="row gy-4 justify-content-center">
        <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
          <img src="{{ asset('enno/assets/img/about.jpg') }}" class="img-fluid" alt="">
        </div>
        <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up">
          <h3>Latar Belakang & Tujuan Aplikasi</h3>
          <p class="fst">
            Aplikasi "Si-Krim" dibangun sebagai bagian dari tugas akhir untuk menjawab tantangan dalam mengidentifikasi
            pola dan wilayah rawan kejahatan di Kota Kendari. Tujuan utamanya adalah untuk membantu pihak berwenang
            dalam membuat keputusan strategis terkait alokasi sumber daya keamanan.
          </p>
          <ul>
            <li><i class="bi bi-check-circle"></i> <span>Mengidentifikasi cluster atau pengelompokan kejadian
                kriminal</span></li>
            <li><i class="bi bi-check-circle"></i> <span>Menentukan peringkat wilayah berdasarkan tingkat kerawanan yang
                dihasilkan dari berbagai faktor.</span></li>
            <li><i class="bi bi-check-circle"></i> <span>Menyediakan visualisasi data yang intuitif melalui peta
                interaktif untuk memudahkan analisis.</span></li>
          </ul>
          <p>
            Dengan memanfaatkan teknologi modern, kami berharap aplikasi ini dapat memberikan kontribusi nyata dalam
            upaya pencegahan dan penanggulangan kriminalitas.
          </p>
        </div>
      </div>


      <div class="row gy-4 justify-content-center mt-5">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="service-item item-cyan position-relative">
            <i class="bi bi-geo-alt icon"></i>
            <h3>Clustering Spasial (DBSCAN)</h3>
            <p>Saya menerapkan algoritma <strong>Density-Based Spatial Clustering of Applications with Noise
                (DBSCAN)</strong> untuk menemukan area-area padat kejadian kriminal secara otomatis, tanpa perlu
              menentukan jumlah cluster di awal.</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="service-item item-orange position-relative">
            <i class="bi bi-bar-chart-steps icon"></i>
            <h3>Penentuan Prioritas (TOPSIS)</h3>
            <p>Untuk merangking wilayah berdasarkan tingkat kerawanan, kami menggunakan metode <strong>Technique for
                Order of Preference by Similarity to Ideal Solution (TOPSIS)</strong>. Metode ini mengevaluasi berbagai
              kriteria secara simultan.</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="service-item item-teal position-relative">
            <i class="bi bi-map icon"></i>
            <h3>Visualisasi Interaktif</h3>
            <p>Semua hasil analisis, baik dari clustering maupun penentuan peringkat, disajikan dalam bentuk peta
              interaktif. Hal ini memungkinkan pengguna untuk memahami distribusi spasial kriminalitas dengan lebih
              mudah.
            </p>
          </div>
        </div>
      </div>

    </div>

  </section>
  <!-- End About Section -->

  <!-- ======= Contact Section ======= -->
  <section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Peta Kriminalitas</h2>
      <p>Visualisasi hasil pengelompokan kriminalitas berdasarkan wilayah.</p>
    </div>
  </section>
  <!-- End Contact Section -->

  <footer id="footer" class="footer">
    <div class="container text-center mt-4">
      <p>&copy; {{ date('Y') }} Si-Krim. All Rights Reserved.</p>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('enno/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('enno/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('enno/assets/js/main.js') }}"></script>

</body>

</html>