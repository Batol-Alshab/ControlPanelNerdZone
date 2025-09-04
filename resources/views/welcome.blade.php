<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>نيرد زون - المنصة التعليمية</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { font-family: 'Cairo', sans-serif; background-color: #f9f9fb; line-height: 1.6; }
    .navbar { background-color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .navbar-brand img { height: 45px; }
    .hero { padding: 100px 0; text-align: center; background: linear-gradient(120deg, #d3cbd4, #d9b6df); color: #fff; }
    .hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 20px; }
    .hero p { font-size: 1.2rem; max-width: 700px; margin: auto; opacity: 0.9; }
    .hero .btn { margin-top: 25px; background:#BA68C8; color:#fff; border:none; }
    .hero .btn:hover { background:#c785d2; }
    .about { padding: 80px 0; background-color: #fff; }
    .about-video { border-radius: 12px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
    .about-text h2 { font-weight: 700; color: #333; }
    .info-cards { padding: 80px 0; }
    .info-cards .card { border: none; border-radius: 15px; padding: 35px 20px; transition: 0.3s; }
    .info-cards .card:hover { transform: translateY(-10px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    .info-cards i { color:#BA68C8; }
    .sections { background-color: #fff; padding: 80px 0; }
    .sections h2 { margin-bottom: 40px; font-weight: 700; }
    .sections .card { border: none; border-radius: 15px; padding: 25px; text-align: center; background: #f7f9fc; transition: 0.3s; }
    .sections .card:hover { background: #BA68C8; color: #fff; }
    .footer { background: #222; color: #ddd; padding: 50px 0; margin-top: 80px; }
    .footer a { text-decoration: none; color: #bbb; transition: 0.3s; }
    .footer a:hover { color: #fff; }
  </style>
</head>
<body>

  <!-- Nav -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="">
        <img src="{{ asset('images/nz.png') }}" alt="NerdZone">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="">من نحن</a></li>
          <li class="nav-item"><a class="nav-link" href="">الخدمات</a></li>
          <li class="nav-item"><a class="nav-link" href="">الأقسام</a></li>
          <li class="nav-item"><a class="nav-link" href="">تواصل</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <h1>مرحباً بك في نيرد زون</h1>
      <p>منصتك التعليمية المتكاملة حيث تجد الدروس المرئية، الملخصات، الاختبارات المؤتمتة، وكل ما تحتاجه للتفوق.</p>
      <a href="" class="btn btn-lg">ابدأ الآن</a>
    </div>
  </section>

  <!-- About Video -->
  <section class="about">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <div class="about-video">
            <video  id="aboutVideo" class="w-100" height="320" autoplay muted loop playsinline>
              <source src="{{ asset('images/veed.mp4') }}" type="video/mp4">
              متصفحك لا يدعم تشغيل الفيديو
            </video>
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const video = document.getElementById("aboutVideo");
                video.playbackRate = 0.6; // 0.5 = نصف السرعة، 1 = طبيعي
            });
          </script>
          </div>
          
        </div>
        <div class="col-md-6 about-text">
          <h2>لماذا نيرد زون؟</h2>
          <p>نيرد زون ليس مجرد منصة تعليمية، بل هو مجتمع يهدف إلى رفع مستوى التعليم عبر محتوى تفاعلي وأدوات مبتكرة تساعدك على فهم المواد الدراسية بأبسط الطرق.</p>
          <p>ابدأ الآن رحلتك التعليمية بطريقة حديثة واحترافية.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Services -->
  <section class="info-cards">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <i class="bi bi-camera-video fs-1"></i>
            <h5 class="mt-3">دروس مرئية</h5>
            <p>شروحات تفاعلية تغنيك عن الدروس الخصوصية.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <i class="bi bi-clipboard-check fs-1"></i>
            <h5 class="mt-3">اختبارات مؤتمتة</h5>
            <p>اختبر معلوماتك فوراً وحسّن مستواك.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <i class="bi bi-journal-text fs-1"></i>
            <h5 class="mt-3">ملخصات جاهزة</h5>
            <p>مواد مختصرة ومنظمة تساعدك على الفهم بسرعة.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sections -->
  <section class="sections">
    <div class="container">
      <h2 class="text-center">الأقسام المتاحة</h2>
      <div class="row g-4">
        <div class="col-md-3"><div class="card shadow-sm">الثالث الثانوي – علمي</div></div>
        <div class="col-md-3"><div class="card shadow-sm">الثالث الثانوي – أدبي</div></div>
        <div class="col-md-3"><div class="card shadow-sm">اللغة الإنجليزية</div></div>
        <div class="col-md-3"><div class="card shadow-sm">الدورات الصيفية</div></div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>روابط مهمة</h5>
          <ul class="list-unstyled">
            <li><a href="">الرئيسية</a></li>
            <li><a href="">من نحن</a></li>
            <li><a href="">الخدمات</a></li>
            <li><a href="">سياسة الخصوصية</a></li>
          </ul>
        </div>
        <div class="col-md-6 text-end">
          <p>تابعنا على:</p>
          <a href="" class="me-2"><i class="bi bi-facebook fs-4"></i></a>
          <a href="" class="me-2"><i class="bi bi-instagram fs-4"></i></a>
          <a href=""><i class="bi bi-telegram fs-4"></i></a>
        </div>
      </div>
      <div class="text-center mt-4">
        <small>© 2025 نيرد زون. جميع الحقوق محفوظة.</small>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
