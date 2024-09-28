<?php

include_once "config/init.php";

if (isset($_GET["ref"])) {
    Session::unsetSession("tfaChallenge");
    Session::unsetSession("uid");
}

if (isset($_GET["ref_"])) {
    Cookie::clear("remember_me");
    Cookie::clear("uid");
}

if ($login->isLoggedIn()) {
    header("Location: admin/index.php");
    die();
}

if ($login->isTfaLoggedIn()) {
    header("Location: challenge.php");
}

if ($login->isRememberSet()) {
    $user = new User();
    $user_id = Cookie::get("uid");
    $uid = Others::decryptData($user_id, ENCRYPTION_KEY);
    $row = $user->getUserData($uid);

    if(empty($row)){
        Cookie::clear("remember_me");
        Cookie::clear("uid");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | <?= APP_NAME ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-9/assets/css/login-9.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <!--    Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css"/>
</head>

<body>


<!-- Login 9 - Bootstrap Brain Component -->
<section class="bg-primary py-3 py-md-5 py-xl-8">
  <div class="container">
    <div class="row gy-4 align-items-center">
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-bg-primary">
          <div class="col-12 col-xl-9">
            <img class="img-fluid rounded mb-4" loading="lazy" src="./assets/img/bsb-logo-light.svg" width="245" height="80" alt="BootstrapBrain Logo">
            <hr class="border-primary-subtle mb-4">
            <h2 class="h1 mb-4">We make digital products that drive you to stand out.</h2>
            <p class="lead mb-5">We write words, take photos, make videos, and interact with artificial intelligence.</p>
            <div class="text-endx">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-5">
        <div class="card border-0 rounded-4">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-4">
                <?php if (!$login->isRememberSet()): ?>
                        <h4 class="card-title">Login to <?= APP_NAME ?> </h4>
                        <?php else: ?>
                            <h4 class="card-title">Login as <?= htmlentities(
                                $row["username"]
                            ) ?></h4>
                        <?php endif; ?>
                </div>
              </div>
            </div>
            <form action="#!">
              <div class="row gy-3 overflow-hidden">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                    <label for="email" class="form-label">Email</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                    <label for="password" class="form-label">Password</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">
                    <label class="form-check-label text-secondary" for="remember_me">
                      Keep me logged in
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">Log in now</button>
                  </div>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-12">
                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                  <a href="#!">Forgot password</a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <p class="mt-4 mb-4">Or continue with</p>
                <div class="d-flex gap-2 gap-sm-3 justify-content-centerX">
                  <a href="#!" class="btn btn-outline-danger bsb-btn-circle bsb-btn-circle-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                      <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                    </svg>
                  </a>
                  <a href="#!" class="btn btn-outline-primary bsb-btn-circle bsb-btn-circle-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                      <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                    </svg>
                  </a>
                  <a href="#!" class="btn btn-outline-dark bsb-btn-circle bsb-btn-circle-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-apple" viewBox="0 0 16 16">
                      <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                      <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="h-100">
    <div class="container h-100">
        <div class="row justify-content-md-center h-100">
            <div class="card-wrapper">
                <div class="brand">
                    <img src="assets/images/logo/E-vote.jpg" alt="logo">
                </div>
                <div class="card fat">
                    <div class="card-body">
                      
                        <form method="POST" id="login_form" >
                            <input type="hidden" name="token" id="token" value="<?= htmlentities(
                                CSRF::generate("login_form")
                            ) ?>">

                            <?php if (!$login->isRememberSet()): ?>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" type="text" class="form-control" name="username" value="" autofocus>
                            </div>
                            <?php else: ?>
                                <input id="username" type="hidden" class="form-control" name="username" value="<?= $row[
                                    "username"
                                ] ?>" autofocus>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="password">Password
                                    <a href="#" data-toggle="modal" data-target="#forgotPasswordModal" class="float-right">
                                        Forgot Password?
                                    </a>
                                </label>
                                <input id="password" type="password" class="form-control" name="password" data-eye>
                            </div>

                            <?php if (!$login->isRememberSet()): ?>
                            <div class="form-group">
									<div class="custom-checkbox custom-control">
										<input type="checkbox" name="remember" id="remember" class="custom-control-input">
										<label for="remember" class="custom-control-label">Remember Me</label>
									</div>
								</div>
                            <?php endif; ?>
                            <div class="form-group m-0">
                                <button type="submit" id="login_button" class="btn btn-primary btn-block">
                                     Log in
                                </button>
                            </div>
                            <div class="mt-4 text-center">
                            <?php if (!$login->isRememberSet()): ?>
                                Don't have an account? <a href="register.php">Create One</a>
                                <?php else: ?>
                                    Not your account? <a href="login.php?ref_">Log in</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="footer">
                    Copyright &copy; <?= date("Y") ?> &mdash; <?= APP_NAME ?>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Forgot Password Modal -->
        <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Forgot my Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <p class="mb-1">To continue, please enter your email for recovery.</p>
                        <div class="form-group">
                            <label for="forgotPasswordEmail">Email</label>
                            <input type="email" id="forgotPasswordEmail" class="form-control" placeholder="Enter your email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="forgotPasswordBtn" data-bs-dismiss="modal" class="btn btn-primary">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/sha512.min.js"></script>
<script src="assets/js/login.js"></script>

</body>
</html>
