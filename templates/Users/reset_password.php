<?php
$this->assign('title', 'Reset your password | ' . env('APP_NAME'));
$cap = $this->Data->getCaps();
$theme = $this->request->getSession()->read('theme');
echo $this->Html->css(['login'], ['block' => 'css'])
?>

<div class="login-page">
  <div class="form" id="rst">
    <h2>Reset your password</h2>
    <br>
    <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'resetPassword'], 'autocomplete' => 'off', 'class' => 'login-form', 'id' => 'e_frm']); ?>
    <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email address', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
    <small class="text-small">Enter your email address and we will send you a link to reset your password.</small>
    <div class="mb-2">
      <div id="f_err"></div>
    </div>
    <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Submit" id="login_sbtn">
    <?php echo $this->Form->end(); ?>
  </div>
</div>



<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    
    $("#login_sbtn").click(function() {
      $('#f_err').html('');
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
          $("#login_sbtn").val('Submit');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Submit');
        },
      }).submit();
    });
  });
</script>
<?php $this->end();  ?>