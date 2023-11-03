<?php $this->assign('title', 'Register | ' . env('APP_NAME')); ?>

<main class="pb-12">
  <div class="bg-dark-sec pb-12"></div>

  <div class="container mt--50">
    <div class="card formWrap mx-440 m-auto">
      <div class="card-body ">
        <h4 class="card-title mb-3">Register</h4>
        <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'register'], 'autocomplete' => 'off', 'id' => 'e_frm']); ?>
        <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('username', ['label' => 'Username', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-username']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('password', ['label' => 'Enter Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('re_password', ['data-v-equal' => '#password', 'label' => 'Repeat Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password1']); ?></div>
        <div class="mb-2"><?= $this->Form->control('terms', ['type' => 'checkbox', 'label' => 'Agree Terms', 'value' => 1, 'class' => 'form-check-input', 'required' => true]); ?></div>
        <div class="mb-2"><div id="f_err"></div></div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Register" id="login_sbtn">
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