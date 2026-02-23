<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach($system as $k => $v){
    $_SESSION['system'][$k] = $v;
}
ob_end_flush();
?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Student Portal | <?php echo $_SESSION['system']['name'] ?? 'University of Loralai'; ?></title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <?php include('./header.php'); ?>

    <?php if(isset($_SESSION['login_id'])) header("location:index.php?page=home"); ?>

    <style>
        /* ----- Reset & Full Page ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* ----- Background Image (University of Loralai) ----- */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://pbs.twimg.com/ext_tw_video_thumb/1834548524328853504/pu/img/X-VGbiKj3yDfLSGe.jpg') no-repeat center center/cover;
            filter: brightness(0.99) saturate(2.9);
            z-index: -2;
        }

        /* Dark overlay for better text contrast */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 20, 40, 0.6);
            z-index: -1;
        }

        /* ----- Main Container ----- */
        .login-wrapper {
            width: 100%;
            max-width: 1300px;
            padding: 20px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        /* ----- UOLi Logo at Top ----- */
        .logo-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: inline-block;
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #f9e6b3, #f9b81b, #e0a800);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(249, 184, 27, 0.6);
            letter-spacing: 3px;
        }

        .logo span {
            font-size: 20px;
            font-weight: 400;
            display: block;
            color: rgba(255,255,255,0.8);
            letter-spacing: 2px;
            margin-top: -5px;
        }

        /* ----- Advanced Centered Card (Glassmorphism) ----- */
        .card-advanced {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(10, 25, 40, 0.5);
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            border: 1px solid rgba(249, 184, 27, 0.35);
            border-radius: 40px;
            padding: 40px 35px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255,215,0,0.2) inset, 0 0 50px rgba(249,184,27,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-advanced:hover {
            transform: translateY(-5px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8), 0 0 0 2px rgba(255,215,0,0.3) inset, 0 0 70px #f9b81b55;
        }

        /* ----- Form Header ----- */
        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-header h2 {
            font-size: 34px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #fff;
            margin-bottom: 8px;
            text-shadow: 0 4px 15px #000;
        }

        .form-header p {
            color: rgba(255,255,255,0.7);
            font-size: 16px;
            border-bottom: 1px dashed #f9b81b;
            padding-bottom: 12px;
            display: inline-block;
        }

        /* ----- Floating Label Inputs ----- */
        .input-group {
            position: relative;
            margin-bottom: 35px;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #f9b81b;
            font-size: 18px;
            transition: 0.2s;
            z-index: 2;
            text-shadow: 0 0 10px #f9b81b;
        }

        .floating-input {
            width: 100%;
            padding: 18px 20px 18px 55px;
            font-size: 16px;
            background: rgba(255,255,255,0.03);
            border: 2px solid rgba(249, 184, 27, 0.25);
            border-radius: 50px;
            color: white;
            outline: none;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .floating-input:focus {
            border-color: #f9b81b;
            background: rgba(0,0,0,0.3);
            box-shadow: 0 0 0 5px rgba(249,184,27,0.2);
        }

        .floating-input:focus + i {
            color: #ffd966;
            transform: translateY(-50%) scale(1.1);
        }

        .floating-label {
            position: absolute;
            left: 55px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: rgba(255,255,255,0.55);
            pointer-events: none;
            transition: 0.2s ease all;
            background: transparent;
            padding: 0 5px;
        }

        .floating-input:focus ~ .floating-label,
        .floating-input:not(:placeholder-shown) ~ .floating-label {
            top: 0;
            left: 45px;
            font-size: 13px;
            color: #f9b81b;
            background: #0a1e2f;
            padding: 2px 12px;
            border-radius: 30px;
            font-weight: 600;
            border: 1px solid #f9b81b55;
            backdrop-filter: blur(4px);
        }

        .floating-input::placeholder {
            color: transparent;
        }

        /* ----- Buttons (Gold & Outline) ----- */
        .btn-gold {
            background: linear-gradient(145deg, #f9b81b, #e0a800, #f9c84e);
            border: none;
            color: #0a1f2c;
            font-weight: 700;
            font-size: 18px;
            padding: 16px 25px;
            border-radius: 60px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 15px 30px rgba(249,184,27,0.3), 0 0 0 2px rgba(255,255,255,0.2) inset;
            transition: all 0.25s;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-gold::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 140%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(25deg);
            animation: shine 6s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            20% { left: 100%; }
            100% { left: 100%; }
        }

        .btn-gold:hover {
            background: linear-gradient(145deg, #f9c84e, #f9b81b, #e0a800);
            box-shadow: 0 20px 40px #f9b81b, 0 0 0 3px rgba(255,255,255,0.3) inset;
            transform: scale(1.02);
            color: #000;
        }

        /* Admin login link style */
        .admin-link {
            text-align: center;
            margin-top: 10px;
        }
        .admin-link a {
            color: #f9e6b3;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .admin-link a:hover {
            color: #f9b81b;
        }

        /* ----- Contact Info (as requested) ----- */
        .contact-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(249,184,27,0.3);
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        .contact-info i {
            color: #f9b81b;
            margin-right: 8px;
        }

        .contact-info a {
            color: #f9e6b3;
            text-decoration: none;
            font-weight: 500;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        /* ----- Modal (match new style) ----- */
        .modal-content {
            background: rgba(10, 25, 40, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid #f9b81b;
            border-radius: 40px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(249,184,27,0.3);
            padding: 25px 30px;
        }

        .modal-header h5 {
            color: white;
            font-size: 24px;
            font-weight: 600;
        }

        .modal-header .close {
            color: #f9b81b;
            opacity: 1;
            font-size: 30px;
        }

        .modal-body {
            padding: 35px;
        }

        .modal-footer {
            border-top: 1px solid rgba(249,184,27,0.3);
            padding: 20px 35px;
        }

        /* ----- Responsive ----- */
        @media (max-width: 576px) {
            .card-advanced { padding: 30px 20px; }
            .logo { font-size: 40px; }
        }

        /* Hide old back-to-top if exists */
        .back-to-top { display: none; }
    </style>
</head>
<body>

    <!-- Background image & overlay -->
    <div class="bg-image"></div>
    <div class="overlay"></div>

    <div class="login-wrapper">
        <!-- UOLi Logo at top -->
        <div class="logo-header">
            <div class="logo">
                UOLi
                <span>University of Loralai</span>
            </div>
        </div>

        <!-- Advanced Centered Card - Student Result Portal -->
        <div class="card-advanced">
            <div class="form-header">
                <h2>Student Portal</h2>
                <p>check your results</p>
            </div>

            <!-- Student Result Lookup Form -->
            <form id="student-result-form">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="student_code_main" name="student_code" class="floating-input" placeholder=" " autocomplete="off">
                    <label for="student_code_main" class="floating-label">Registration Number</label>
                </div>

                <button type="submit" class="btn-gold">
                    <i class="fas fa-search"></i> View Result
                </button>

                <div class="admin-link">
                    <a href="#" id="show_admin_login"><i class="fas fa-lock"></i> Admin Login</a>
                </div>
            </form>

            <!-- Contact Info (exactly as requested) -->
            <div class="contact-info">
                <i class="fas fa-exclamation-circle"></i> If anyone face some issues, contact us:<br>
                <i class="fas fa-envelope"></i> <a href="mailto:sajadullah241@gmail.com">sajadullah241@gmail.com</a> &nbsp;|&nbsp;
                <i class="fas fa-phone-alt"></i> <a href="tel:+923363765236">+92 3363765236</a>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal (formerly the main login form) -->
    <div class="modal fade" id="adminLoginModal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-shield" style="color: #f9b81b;"></i> Admin Login
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="admin-login-form">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="password" id="admin_username" name="username" class="floating-input" placeholder=" " autocomplete="new-username">
                            <label type="password" for="admin_username" class="floating-label">Username</label>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="admin_password" name="password" class="floating-input" placeholder=" ">
                            <label for="admin_password" class="floating-label">Password</label>
                        </div>
                        <button type="submit" class="btn-gold" style="margin-bottom: 0;">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden back-to-top (original placeholder) -->
    <a href="#" class="back-to-top" style="display: none;"><i class="icofont-simple-up"></i></a>

    <?php include 'footer.php'; ?>

    <!-- JavaScript (adapted for new structure) -->
    <script>
        // Show admin login modal when clicking the link
        $('#show_admin_login').click(function(e){
            e.preventDefault();
            $('#adminLoginModal').modal('show');
        });

        // Student result form submission
        $('#student-result-form').submit(function(e){
            e.preventDefault();
            start_load();
            // Remove any previous error
            if($(this).find('.alert-danger').length > 0 )
                $(this).find('.alert-danger').remove();

            $.ajax({
                url:'ajax.php?action=login2',  // same endpoint used for student result lookup
                method:'POST',
                data: $(this).serialize(),
                error: err => {
                    console.log(err);
                    end_load();
                },
                success: function(resp){
                    if(resp == 1){
                        location.href = 'student_results.php';
                    } else {
                        $('#student-result-form').prepend('<div class="alert alert-danger" style="background: rgba(220,53,69,0.2); border:1px solid rgba(220,53,69,0.5); color:white; border-radius:60px; padding:14px 20px; margin-bottom:24px;"><i class="fas fa-exclamation-triangle" style="margin-right:10px;"></i> Invalid registration number.</div>');
                        end_load();
                    }
                }
            });
        });

        // Admin login form submission (inside modal)
        $('#admin-login-form').submit(function(e){
            e.preventDefault();
            $('#admin-login-form button[type="submit"]').attr('disabled',true).html('<i class="fas fa-spinner fa-spin"></i> Logging in...');
            if($(this).find('.alert-danger').length > 0 )
                $(this).find('.alert-danger').remove();

            $.ajax({
                url:'ajax.php?action=login',
                method:'POST',
                data: $(this).serialize(),
                error: err => {
                    console.log(err);
                    $('#admin-login-form button[type="submit"]').removeAttr('disabled').html('<i class="fas fa-sign-in-alt"></i> Login');
                },
                success: function(resp){
                    if(resp == 1){
                        location.href = 'index.php?page=home';
                    } else {
                        $('#admin-login-form').prepend('<div class="alert alert-danger" style="background: rgba(220,53,69,0.2); border:1px solid rgba(220,53,69,0.5); color:white; border-radius:60px; padding:14px 20px; margin-bottom:24px;"><i class="fas fa-exclamation-triangle" style="margin-right:10px;"></i> Username or password is incorrect.</div>');
                        $('#admin-login-form button[type="submit"]').removeAttr('disabled').html('<i class="fas fa-sign-in-alt"></i> Login');
                    }
                }
            });
        });

        // Utility functions (likely defined in footer or header)
        function start_load(){
            // Placeholder – you can define a loading indicator if needed
        }
        function end_load(){
            // Placeholder
        }
    </script>
</body>
</html>