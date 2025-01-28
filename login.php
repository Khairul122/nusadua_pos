<?php include 'template/header.php'; ?>

<body class="login-page bg-body-secondary">
  <div class="login-box">
    <div class="login-logo">
      <a href="../index2.html"><b>Admin</b>LTE</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="login-user.php" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required />
            <div class="input-group-text"><span class="bi bi-person-fill"></span></div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required />
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Sign In</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>