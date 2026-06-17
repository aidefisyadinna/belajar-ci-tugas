<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SINNA STORE - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px;
    }
    .login-card {
      background: #fff;
      border-radius: 16px;
      padding: 40px 32px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0,0,0,.3);
    }
    .logo-area {
      text-align: center;
      margin-bottom: 28px;
    }
    .logo-area .brand-icon {
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
    }
    .logo-area .brand-icon i {
      font-size: 28px;
      color: #fff;
    }
    .logo-area h4 {
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 4px;
    }
    .logo-area p {
      font-size: 14px;
      color: #6c757d;
      margin: 0;
    }
    .input-icon {
      position: relative;
    }
    .input-icon .form-control {
      padding-left: 40px;
      height: 46px;
    }
    .input-icon .icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #adb5bd;
      font-size: 18px;
      z-index: 5;
      pointer-events: none;
    }
  </style>
</head>
<body>

<div class="login-card">
  <div class="logo-area">
    <div class="brand-icon">
      <i class="bi bi-shop"></i>
    </div>
    <h4>SINNA STORE</h4>
    <p>Masuk untuk melanjutkan</p>
  </div>

  <?php
  if (session()->getFlashData('failed')) {
  ?>
  <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
    <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
    <div><?= session()->getFlashData('failed') ?></div>
  </div>
  <?php
  }
  ?>

  <?= form_open('login') ?>
  <div class="mb-3 input-icon">
    <i class="bi bi-person icon"></i>
    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required minlength="6">
  </div>
  <div class="mb-3 input-icon">
    <i class="bi bi-lock icon"></i>
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required minlength="7">
  </div>
  <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
    <i class="bi bi-box-arrow-in-right me-1"></i> Login
  </button>
  <?= form_close() ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
