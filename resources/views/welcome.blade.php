<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>المدرسة الربانية - منصة التعليم عن بعد</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('landing/assets/img/logo.png')}}" rel="icon">
    <link href="{{asset('landing/assets/img/logo.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('landing/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('landing/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('landing/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('landing/assets/css/style.css')}}" rel="stylesheet">

</head>

<body dir="rtl">

<!-- ======= Header ======= -->
<header id="header" class="fixed-top  header-transparent ">
    <div class="container d-flex align-items-center justify-content-between">

        <div class="logo">
            <h4><a href="">المدرسة الربانية</a></h4>
            <h6><a href="">النسخة التجريبية . إصدار {{config('app.version')}}</a></h6>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">الرئيسية</a></li>
                @if(Route::has('login'))
                @auth
                    <li><a class="getstarted scrollto" href="{{route('home')}}">صفحتي الشخصية</a></li>
                @else
                    <li><a class="nav-link scrollto" href="{{route('register')}}">تسجيل عضو جديد</a></li>
                    <li><a class="getstarted scrollto" href="{{route('login')}}">دخول مستخدم</a></li>
                @endauth
                @endif
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->


<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-center align-content-center pt-5 pt-lg-0 order-2 order-lg-1" data-aos="fade-up">
                <div>
                    <div class="logo pb-4" style="text-align: center">
                        <a href=""><img src="{{asset('landing/assets/img/logo.png')}}" alt="" class="img-fluid" style="height: 120px"></a>
                    </div>

                    <h1>منصة التعليم عن بُعد</h1>
                    <h2>هدفنا صناعة الشخصية الربانية النَّسَّاكة المُتَألِّهة بعلمٍ</h2>

                    @if(Route::has('login'))
                        @auth
                            <a href="{{route('home')}}" class="download-btn"><i class='bx bx-user-pin'></i>صفحتي الشخصية</a>
                        @else
                            <a href="{{route('register')}}" class="download-btn"><i class='bx bx-user-pin'></i>حساب جديد</a>
                            <a href="{{route('login')}}" class="download-btn"><i class='bx bxs-user'></i></i>دخول مستخدم</a>

                        @endauth
                    @endif

                </div>
            </div>
            <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img" data-aos="fade-up">
                <img src="{{asset('landing/assets/img/hero-img.png')}}" class="img-fluid" alt="">
            </div>
        </div>
    </div>

</section><!-- End Hero -->

<!-- ======= Footer ======= -->
<footer id="footer">

    <div class="footer-newsletter">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h4>اشترك معنا</h4>
                    <p>ادخل بريدك الاليكتروني ليصلك كل جديد</p>
                    <form action="" method="post">
                        <input type="email" name="email"><input type="submit" value="اشتراك">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-top">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="copyright">
            &copy; <strong><span>الربانية</span></strong>. جميع الحقوق محفوظة
        </div>
        <div class="credits">
            تصميم <a href="https://www.yaqob.com/">المدرسة الربانية</a>
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{asset('landing/assets/vendor/aos/aos.js')}}"></script>
<script src="{{asset('landing/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('landing/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('landing/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('landing/assets/vendor/php-email-form/validate.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{asset('landing/assets/js/main.js')}}"></script>

</body>

</html>
