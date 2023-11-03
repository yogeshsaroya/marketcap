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
        <?= $this->Form->create($user, ['url' => ['controller' => 'users', 'action' => 'setPassword'], 'autocomplete' => 'off','class'=>'login-form', 'id' => 'e_frm']);?>
        <?= $this->Form->hidden('reset_password_key'); ?>
        <div class="mb-2 form-group"><?= $this->Form->control('new_password', ['value' => '', 'label' => 'New Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('repeat_password', ['data-v-equal' => '#new-password', 'label' => 'Repeat Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password1']); ?></div>

        <div class="mb-2">
            <div id="f_err"></div>
        </div>
        <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Reset password" id="login_sbtn">
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
                    $("#login_sbtn").val('Reset password');
                },
                error: function(response) {
                    $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
                    $("#login_sbtn").prop("disabled", false);
                    $("#login_sbtn").val('Reset password');
                },
            }).submit();
        });
    });
</script>
<?php $this->end();  ?>