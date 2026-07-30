<?php 
session_start();
include './user.php';

$user = new User();

if (isset($_POST['register']) && $_POST['register'] == "click"){
    $fullName = trim($_POST['fullName']);
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. FullName-ը բաժանում ենք firstName-ի և lastName-ի
    $nameParts = explode(' ', $fullName, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // 2. Գաղտնաբառի հեշավորում
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Փոխանցում ենք firstName և lastName դաշտերը Register ֆունկցիային
    $user->Register($firstName, $lastName, $email, $hashedPassword);
}
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Գրանցում | NoteApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /* Midnight Indigo & Violet Գրադիենտ ֆոն */
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4c1d95 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .bg-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 45vh;
            background: radial-gradient(circle at 50% 100%, rgba(168, 85, 247, 0.2) 0%, transparent 75%);
            z-index: 0;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            color: rgba(238, 242, 255, 0.15);
            font-size: 5.5rem;
            z-index: 0;
            user-select: none;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
        }

        @keyframes floatAnim1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @keyframes floatAnim2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-12deg); }
        }

        .icon-top-left { top: 10%; left: 8%; animation: floatAnim1 6s ease-in-out infinite; }
        .icon-top-right { top: 12%; right: 9%; animation: floatAnim2 7s ease-in-out infinite; }
        .icon-bottom-left { bottom: 12%; left: 9%; animation: floatAnim2 8s ease-in-out infinite; }
        .icon-bottom-right { bottom: 10%; right: 8%; animation: floatAnim1 6.5s ease-in-out infinite; }

        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 430px;
            padding: 40px 32px;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-box {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #ffffff;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            margin-bottom: 20px;
            animation: pulseIcon 3s ease-in-out infinite;
        }

        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .input-group {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);
        }

        .input-group-text {
            background: transparent !important;
            border: none;
            color: #6366f1;
            padding-left: 16px;
            padding-right: 10px;
        }

        .form-control {
            background: transparent !important;
            color: #0f172a !important;
            border: none !important;
            padding: 14px 14px 14px 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-control::placeholder { color: #94a3b8; }
        .form-control:focus { box-shadow: none !important; }

        .btn-glow {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-glow:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.55);
            color: #ffffff;
        }

        .back-home {
            position: absolute;
            top: 24px;
            left: 24px;
            color: #64748b;
            font-size: 1.3rem;
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .back-home:hover { color: #6366f1; transform: translateX(-3px); }

        .strength-meter {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .link-accent {
            color: #6366f1;
            text-decoration: none;
            font-weight: 700;
        }

        .link-accent:hover { color: #4f46e5; text-decoration: underline; }
    </style>
</head>
<body>

<i class="bi bi-journal-bookmark floating-icon icon-top-left"></i>
<i class="bi bi-pencil-square floating-icon icon-top-right"></i>
<i class="bi bi-sticky floating-icon icon-bottom-left"></i>
<i class="bi bi-file-earmark-text floating-icon icon-bottom-right"></i>

<div class="bg-wave"></div>

<div class="glass-card text-center">
    <a href="index.php" class="back-home"><i class="bi bi-arrow-left"></i></a>

    <div class="icon-box mt-2">
        <i class="bi bi-person-plus-fill fs-2"></i>
    </div>

    <h3 class="fw-bold mb-1" style="color: #0f172a;">Ստեղծել հաշիվ ✨</h3>
    <p class="small text-muted mb-4">Միացեք NoteApp-ին վայրկյանների ընթացքում</p>

    <form action="./reg.php" method="POST">
        <div class="mb-3 text-start">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="fullName" class="form-control" placeholder="Անուն Ազգանուն" required>
            </div>
        </div>

        <div class="mb-3 text-start">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Էլ. հասցե..." required>
            </div>
        </div>

        <div class="mb-4 text-start">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="passInput" class="form-control" placeholder="Գաղտնաբառ" oninput="checkStrength(this.value)" required>
                <button class="btn border-0 text-muted pe-3" type="button" id="togglePass">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            <div class="progress mt-1" style="height: 4px; background: #e2e8f0;">
                <div class="strength-meter" id="strengthMeter"></div>
            </div>
        </div>

        <button type="submit" name="register" value="click" class="btn btn-glow w-100 mb-3">Գրանցվել <i class="bi bi-arrow-right-short fs-5 align-middle"></i></button>

        <div class="small text-muted">
            <span>Արդեն ունե՞ք հաշիվ:</span> 
            <a href="login.php" class="link-accent">Մուտք գործել այստեղ</a>
        </div>
    </form>
</div>

<script>
    const togglePass = document.getElementById('togglePass');
    const passInput = document.getElementById('passInput');
    togglePass.addEventListener('click', () => {
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);
        togglePass.querySelector('i').classList.toggle('bi-eye');
        togglePass.querySelector('i').classList.toggle('bi-eye-slash');
    });

    function checkStrength(val) {
        const meter = document.getElementById('strengthMeter');
        let strength = 0;
        if (val.length >= 6) strength += 33;
        if (val.match(/[A-Z]/) && val.match(/[0-9]/)) strength += 33;
        if (val.length >= 10 && val.match(/[^a-zA-Z0-9]/)) strength += 34;

        meter.style.width = strength + '%';
        if (strength <= 33) meter.style.background = '#ef4444';
        else if (strength <= 66) meter.style.background = '#f59e0b';
        else meter.style.background = '#10b981';
    }
</script>

</body>
</html>