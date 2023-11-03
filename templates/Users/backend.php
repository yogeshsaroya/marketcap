<?php $this->assign('title', 'Login Page');  ?>
<script src="https://kit.fontawesome.com/a3002384c4.js" crossorigin="anonymous"></script>
<div class="app-content content ">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row"></div>
    <div class="content-body">
      <div class="auth-wrapper auth-v2">
        <div class="auth-inner row m-0">
          <!-- Brand logo-->

          <!-- /Brand logo-->
          <!-- Left Text-->
          <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
              <img class="img-fluid" src="<?php echo SITEURL; ?>img/login_page_image.png" alt="Login V2" />
            </div>
          </div>
          <!-- /Left Text-->
          <!-- Login-->
          <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
              <h2 class="card-title font-weight-bold mb-1">Welcome to <?= env('APP_NAME');?>!</h2>
              <p class="card-text mb-2">Please sign-in to your account and start the adventure.</p>
              <?php
              echo $this->Form->create($user_data, ['url' => ['controller' => 'users', 'action' => 'backend'], 'autocomplete' => 'off', 'id' => 'e_frm', 'class' => 'auth-login-form mt-2', 'data-toggle' => 'validator']);
              ?>
              <div class="form-group">
                <?php echo $this->Form->control('email', ['label' => 'Email', 'type' => 'email', 'class' => 'form-control input-sm', 'required' => true, 'autocomplete' => 'new-email']); ?>
                <div class="help-block with-errors"></div>
              </div>
              <div class="form-group">
                <div class="d-flex justify-content-between">
                  <label for="login-password">Password</label>
                  <?php /*?> <a href="<?php echo SITEURL;?>backend_reset_password"><small>Forgot Password?</small></a> <?php */ ?>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                  <?php echo $this->Form->control('password', [
                    'id' => 'login-password', 'label' => false, 'type' => 'password', 'class' => 'form-control form-control-merge',
                    'autocomplete' => 'new-password',
                    'templates' => ['inputContainer' => '{{content}}'], 'required' => true
                  ]); ?>
                  <div class="input-group-append">
                    <span class="input-group-text cursor-pointer">
                      <i data-feather="eye"></i>
                    </span>
                  </div>
                </div>
                <div class="help-block with-errors"></div>
              </div>

              <div id="f_err"></div>
              <input type="button" class="btn btn-primary btn-block" value="Sign in" id="login_sbtn" />
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.form-password-toggle .input-group-text').on('click', function(e) {
      e.preventDefault();
      var $this = $(this),
        inputGroupText = $this.closest('.form-password-toggle'),
        formPasswordToggleIcon = $this,
        formPasswordToggleInput = inputGroupText.find('input');

      if (formPasswordToggleInput.attr('type') === 'text') {
        formPasswordToggleInput.attr('type', 'password');
        if (feather) {
          formPasswordToggleIcon.find('svg').replaceWith(feather.icons['eye'].toSvg({
            class: 'font-small-4'
          }));
        }
      } else if (formPasswordToggleInput.attr('type') === 'password') {
        formPasswordToggleInput.attr('type', 'text');
        if (feather) {
          formPasswordToggleIcon.find('svg').replaceWith(feather.icons['eye-off'].toSvg({
            class: 'font-small-4'
          }));
        }
      }
    });

    $("#e_frm").validator();

    $("#login_sbtn").click(function() {
      $("#e_frm").ajaxForm({
        target: '#f_err',
        headers: {
          'X-CSRF-Token': $('[name="_csrfToken"]').val()
        },
        beforeSubmit: function() {
          $("#login_sbtn").prop("disabled", true);
          $("#login_sbtn").val('Please wait..');
        },
        success: function(response) {
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Sign In');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Sign In');
        },
      }).submit();
    });
  });
</script>