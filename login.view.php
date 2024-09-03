<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .bg-body { background-color: #f0f2f5; }
        .first { padding-left: 33%; }
        .text-blue { color: #1877f2; font-size: 55px; font-weight: 700; }
        .text-black { color: #1c1e81; font-size: 25px; line-height: 28px; }
        .second { padding-left: 7%; }
        .btn-blue { background: #1877f2; color: white; }
        .pass { color: #1877f2; font-size: 13.5px; }
        .px-btn { padding: 0 22%; }
        .card { box-shadow: 0px 0px 12px 3px rgba(0, 0, 0, 0.2); }
        .form-control { height: 52px; }
        ::placeholder { padding-left: 3px; }
        .btn-green { background: #42b72a; color: white; padding: 12px 25px; }
        .error-message { color: red; }
    </style>
</head>
<body class="bg-body">

    <div class="container-fluid" style="margin-top: 7%;">

        <div class="row">
            <div class="col mt-5">
                <div class="first">
                    <p class="text-blue mb-0">facebook</p>
                    <p class="text-black">facebook helps to connect and share<br>with the people in our life</p>
                </div>
            </div>
            <div class="col">
                <div class="second">
                    <div class="card" style="width: 26rem;">
                        <div class="card-body">
                            <form action="loginvalidate.php" method="post">
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="error-message">
                                        <?php
                                        if ($_GET['error'] == 'email_required') {
                                            echo "Email is required.";
                                        } elseif ($_GET['error'] == 'email_invalid') {
                                            echo "Invalid email format.";
                                        } elseif ($_GET['error'] == 'password_required') {
                                            echo "Password is required.";
                                        } elseif ($_GET['error'] == 'login_failed') {
                                            echo "Invalid email or password.";
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <input type="text" name="email" placeholder="Email or phone number" class="form-control" required>
                                <input type="password" name="password" placeholder="Password" class="form-control mt-3" required>
                                <button class="btn btn-blue fw-bold fs-5 mt-3 w-100 py-2" type="submit">Log In</button>
                                <p class="text-center pass mt-3"><a href="#">Forgotten password</a></p>
                                <hr class="mt-4 text-muted">
                                <div class="px-btn">
                                    <button class="btn btn-green fw-bold mt-3" type="button">
                                        <a href="register.view.php" style="color: white; text-decoration: none;">Create New Account</a>
                                    </button>
                                </div>
                            </form>
                            
                        </div>d
                    </div>
                    <p class="last-txt mt-3"><span><b>Create a page</b></span> for a celebrity, brand, or business</p>
                </div>
            </div>
        </div>

    </div>

    <?php require "Partials/script.php" ?>
</body>
</html>


