<?php
$this->assign('title', 'Register | ' . env('APP_NAME'));

$theme = $this->request->getSession()->read('theme');
echo $this->Html->css(['login'], ['block' => 'css'])
?>
<div class="login-page">
  <div class="form">
    <h2>Register</h2>
    <br>
    <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'register'], 'autocomplete' => 'off', 'class' => 'login-form', 'id' => 'e_frm']); ?>
    <div class="mb-2 form-group"><?= $this->Form->control('first_name', ['label' => 'First Name', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-first-name']); ?></div>
    <div class="mb-2 form-group"><?= $this->Form->control('last_name', ['label' => 'Last Name', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-last-name']); ?></div>
    <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
    <div class="mb-2 form-group"><?= $this->Form->control('password', ['label' => 'Enter Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
    <div class="mb-2">
      <div id="f_err"></div>
    </div>
    <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Sign Up" id="login_sbtn">
    <div class="text-center">
      <a href="<?= SITEURL ?>login" class="btn btn-secondary w-100">already have account? Sign in</a>
      <br>

    </div>
    <?php echo $this->Form->end(); ?>
  </div>
</div>




<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {

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
          $("#login_sbtn").val('Register');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Register');
        },
      }).submit();
    });
  });
</script>
<?php $this->end();  ?>