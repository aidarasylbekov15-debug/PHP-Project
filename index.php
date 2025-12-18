

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Course Management System</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
  >

  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet"
  >

  <link
    href="https://unpkg.com/aos@2.3.4/dist/aos.css"
    rel="stylesheet"
  >
<nav class="navbar navbar-light navbar-expand-lg bg-dark fixed-top">
        <div class="container-fluid">
          <div class="col-4">
            <a href="#" class="navbar-brand text-white"><img src="system.png" class="icon2" style="width: 35px; height: 35px;" />Course
              Management System</a>
          </div>

          <?php session_start();
          if (!isset($_SESSION["u"])) {
          ?>
            <div class="btn-group col-lg-3 col-3 text-center">
              <div class="row">
                <div class="col-6">
                  <form action="SignIn.php">
                    <button class="fs-5 text-black btn btn-primary col-12">Sign In</button>
                  </form>
                </div>
                <div class="col-6">
                  <form action="Register.php">
                    <button class="fs-5 text-black btn btn-danger col-12">Register</button>
                  </form>
                </div>
              </div>
            </div>
          <?php
          } else {
          ?>
            <div class="btn-group col-lg-4 col-3">
              <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <label class="fs-6"><?php echo ($_SESSION["u"]) ?></label>
              </button>
              <div class="dropdown-menu">
                <?php 
                  $connection = new mysqli("localhost", "root", "", "online_lms");
                  $table = $connection->query("SELECT * FROM `user` WHERE `username`='".$_SESSION["u"]."'");

                  if($table->num_rows){
                    $row = $table->fetch_assoc();
                    $userType = $row["user_type_id"];

                    if($userType=="1"){
                      ?>
                        <a class="dropdown-item" href="ProfileAdmin.php">Profile</a>
                        <a class="dropdown-item" href="AdminDash.php">Dashboard</a>
                      <?php
                    }else if($userType=="2"){
                      ?>
                      <a class="dropdown-item" href="ProfileTeacher.php">Profile</a>
                      <a class="dropdown-item" href="TeacherDash.php">Dashboard</a>
                    <?php
                    }else if($userType=="3"){
                      ?>
                        <a class="dropdown-item" href="ProfileStu.php">Profile</a>
                        <a class="dropdown-item" href="StudentDash.php">Dashboard</a>
                      <?php
                    }else if($userType=="4"){
                      ?>
                        <a class="dropdown-item" href="ProfileAccademic.php">Profile</a>
                        <a class="dropdown-item" href="AccademicDash.php">Dashboard</a>
                      <?php
                    }
                  }
                ?>
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="SignOut.php">Sign Out</a>
              </div>
            </div>
          <?php
          }
          ?>

        </div>
      </nav>

  <link rel="stylesheet" href="/eduLearn/css/style.css">
</head>
<section id="hero" class="d-flex align-items-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1" data-aos="fade-up">
        <div>
          <h1>Course Management System</h1>
          <h2>
            Advanced Learning Management System for Information Technology & Computer Science Students
          </h2>
          <a href="#" class="download-btn"><i class="bx bx-laptop"></i> Get Started</a>
        </div>
      </div>
      <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img" data-aos="fade-up">
        <img src="hero-img.png" class="img-fluid" alt="" />
      </div>
    </div>
  </div>
</section>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
<main id="main">
  <section id="program" class="features">
    <div class="container">
      <div class="section-title">
        <h2>Programs</h2>
        <p>
          Our comprehensive course programs are designed to equip students with essential skills in modern technology. 
          From frontend to backend development, mobile applications to advanced algorithms, we provide industry-leading education 
          that prepares you for a successful career in Information Technology.
        </p>
      </div>

      <div class="row no-gutters">
        <div class="col-xl-7 d-flex align-items-stretch order-2 order-lg-1">
          <div class="content d-flex flex-column justify-content-center">
            <div class="row">
              <div class="col-md-6 icon-box" data-aos="fade-up">
                <i class="bx bxl-html5"></i>
                <h4>Frontend Development</h4>
                <p>
                  Master modern web design and development with HTML5, CSS3, and JavaScript. 
                  Learn to create responsive, user-friendly interfaces that engage and delight users.
                </p>
              </div>
              <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                <i class="bx bx-data"></i>
                <h4>Backend Development</h4>
                <p>
                  Build robust server-side applications with PHP, Python, and Node.js. 
                  Learn database management and API development to power modern applications.
                </p>
              </div>
              <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                <i class="bx bxl-android"></i>
                <h4>Mobile Development</h4>
                <p>
                  Develop cross-platform mobile applications for iOS and Android using Flutter and React Native. 
                  Create engaging mobile experiences for millions of users worldwide.
                </p>
              </div>
              <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                <i class="bx bx-sort-up"></i>
                <h4>Data Structures & Algorithms</h4>
                <p>
                  Master the fundamentals of computer science with in-depth study of data structures and algorithms. 
                  Essential knowledge for technical interviews and professional development.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="image col-xl-5 d-flex align-items-stretch justify-content-center order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
          <img src="features.svg" class="img-fluid" alt="" />
        </div>
      </div>
    </div>
  </section>

  <section id="details" class="details">
    <div class="container">
      <div class="row content">
        <div class="col-md-4" data-aos="fade-right">
          <img src="details-1.png" class="img-fluid" alt="" />
        </div>
        <div class="col-md-8 pt-4" data-aos="fade-up">
          <h3>
            Comprehensive Learning Experience for IT Professionals
          </h3>
          <p class="fst-italic">
            Our platform provides a structured path to mastering modern software development and IT concepts. 
            Learn from experienced teachers with real-world industry experience.
          </p>
          <ul>
            <li>
              <i class="bi bi-check"></i> Interactive hands-on projects and assignments
            </li>
            <li>
              <i class="bi bi-check"></i> Live coding sessions and expert guidance
            </li>
            <li>
              <i class="bi bi-check"></i> Comprehensive course materials and video tutorials
            </li>
            <li>
              <i class="bi bi-check"></i> Industry-recognized certificates upon completion
            </li>
          </ul>
          <p>
            Our teachers are dedicated professionals committed to your success. 
            Track your progress, collaborate with peers, and build a portfolio of projects 
            that demonstrates your expertise to potential employers.
          </p>
        </div>
      </div>

      <div class="row content">
        <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
          <img src="details-2.png" class="img-fluid" alt="" />
        </div>
        <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
          <h3>Advance Your Career in Technology</h3>
          <p class="fst-italic">
            Join thousands of students who have transformed their careers through our comprehensive learning platform. 
            Build real skills that matter in the job market.
          </p>
          <p>
            Whether you're beginning your coding journey or looking to advance your skills, our flexible learning paths 
            adapt to your pace and schedule. Our courses are designed by industry experts to match current market demands, 
            ensuring you learn technologies that companies are actively hiring for.
          </p>
          <p>
            Gain hands-on experience with real-world projects, receive personalized feedback from teachers, 
            and connect with a vibrant community of learners and professionals ready to support your growth.
          </p>
        </div>
      </div>

      

      
    </div>
  </section>



  <section id="faq" class="faq section-bg">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Frequently Asked Questions</h2>
        <p>
          Get answers to common questions about our courses, learning experience, and how we can help you achieve your goals. 
          If you have additional questions, our support team is always ready to help.
        </p>
      </div>

      <div class="accordion-list">
        <ul>
          <li data-aos="fade-up">
            <i class="bx bx-help-circle icon-help"></i>
            <a data-bs-toggle="collapse" class="collapse" data-bs-target="#accordion-list-1">How do I get started with the courses?
              <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
              <p>
                Simply create an account and explore our course catalog. Each course has a detailed description, 
                learning outcomes, and prerequisites. Once you enroll, you can access all course materials, videos, 
                and assignments immediately. Start learning at your own pace!
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="100">
            <i class="bx bx-help-circle icon-help"></i>
            <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed">Are there certificates upon completion?
              <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
              <p>
                Yes! Upon successful completion of any course, you'll receive a certificate of achievement. 
                These certificates are recognized by industry professionals and can be added to your resume and LinkedIn profile. 
                They demonstrate your commitment to continuous learning and mastery of specific technologies.
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="200">
            <i class="bx bx-help-circle icon-help"></i>
            <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed">How long do the courses take to complete?
              <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
              <p>
                Course duration varies depending on complexity and your learning pace. 
                Most courses range from 4 to 12 weeks when studied part-time (10-15 hours per week). 
                However, you have full flexibility to complete courses at your own speed with no time restrictions.
              </p>
            </div>
          </li>

          

         
        </ul>
      </div>
    </div>
  </section>

  <section id="contact" class="contact">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Contact</h2>
        <p>
          Have questions or need assistance? Reach out to our support team for help with course selection, technical issues, 
          or any other inquiries. We're here to support your learning journey every step of the way.
        </p>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-12 info">
              <i class="bx bx-phone"></i>
              <h4>Call Us</h4>
              <p>+996550035495</p>
            </div>
            <div class="col-lg-12 info">
              <i class="bx bx-envelope"></i>
              <h4>Email Us</h4>
              <p>cms@manas.edu.kg</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form" data-aos="fade-up">
            <div class="form-group">
              <input placeholder="Your Name" type="text" name="name" class="form-control" id="name" required />
            </div>
            <div class="form-group mt-3">
              <input placeholder="Your Email" type="email" class="form-control" name="email" id="email" required />
            </div>
            <div class="form-group mt-3">
              <input placeholder="Subject" type="text" class="form-control" name="subject" id="subject" required />
            </div>
            <div class="form-group mt-3">
              <textarea placeholder="Message" class="form-control" name="message" rows="5" required></textarea>
            </div>
            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">
                Your message has been sent. Thank you!
              </div>
            </div>
            <div class="text-center">
              <button type="submit">Send Message</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

