<?php $this->assign('title', 'Reset your password | ' . env('APP_NAME')); ?>
<main class="pb-12">
  <div class="bg-dark-sec pb-12"></div>
  <div class="container mt--50 pad2rem">
    <div class="card  mx-440 formWrap  m-auto">
      <div class="card-body" id="rst">
        <h4 class="card-title mb-3">Reset your password</h4>
        <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'resetPassword'], 'autocomplete' => 'off', 'id' => 'e_frm']); ?>
        <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email address', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
        <p>Enter your email address and we will send you a link to reset your password.</p>
        <div class="mb-2"><div id="f_err"></div></div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Send password reset email" id="login_sbtn">
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</main>



<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    let validator = $('#e_frm').jbvalidator({
      errorMessage: true,
      successClass: true,
    });
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
          $("#login_sbtn").val('Send password reset email');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Send password reset email');
        },
      }).submit();
    });
  });
</script>
<?php $this->end();  ?>