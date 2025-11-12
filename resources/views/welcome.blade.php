<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Pengelompokkan Kriminalitas</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('enno/assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Raleway:wght@500;700&display=swap" rel="stylesheet">

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
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Si-Krim</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#services">Data</a></li>
          <li><a href="#contact">Peta</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
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
    <div class="container section-title" data-aos="fade-up">
      <span>Tentang<br></span>
      <h2>Tentang Kami</h2>
      <p>Aplikasi ini membantu menganalisis dan mengelompokkan tingkat kriminalitas berdasarkan kepadatan penduduk di wilayah Kota Kendari.</p>
    </div>
  </section>
  <!-- End About Section -->

  <!-- ======= Contact Section ======= -->
  <section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
      <span>Peta</span>
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
