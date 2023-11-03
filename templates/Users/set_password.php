<?php $this->assign('title', 'Reset your password | ' . env('APP_NAME')); ?>
<main class="pb-12">
    <div class="bg-dark-sec pb-12"></div>
    <div class="container mt--50 pad2rem">
        <div class="card  mx-440 formWrap  m-auto">
            <div class="card-body" id="rst">
                <h4 class="card-title mb-3">Reset your password</h4>
                <?= $this->Form->create($user, ['url' => ['controller' => 'users', 'action' => 'setPassword'], 'autocomplete' => 'off', 'id' => 'e_frm']);?>
                <?= $this->Form->hidden('reset_password_key');?>
                <div class="mb-2 form-group"><?= $this->Form->control('new_password', ['value'=>'', 'label' => 'New Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
                <div class="mb-2 form-group"><?= $this->Form->control('repeat_password', ['data-v-equal' => '#new-password', 'label' => 'Repeat Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password1']); ?></div>
                <div class="mb-2"><div id="f_err"></div></div>
                <input type="button" class="btn btn-primary w-100 mb-2" value="Reset password" id="login_sbtn">
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