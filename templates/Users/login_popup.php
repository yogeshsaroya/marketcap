<div id="custom-content" class="white-popup-block offer-pop" style="max-width:500px; margin: 20px auto;">
    <?php
    echo $this->Html->css(['login'])
    ?>
    <style>
        .form {

            filter: unset !important;
            padding: 0 !important;
        }

        .login-page {
            padding: 40px 10px 10px 10px;

        }

        .dark .white-popup-block {
            background: #343e59;
            border-radius: 10px;
        }

        .dark .form-control:focus {
            background-color: #fff;
        }

        .white-popup-block {
            border-radius: 10px;
        }
    </style>
    <div class="login-page">
        <div class="form">
            <h2>SIGN IN TO YOUR ACCOUNT</h2>
            <br>
            <?= $this->Form->create(null, ['url' => ['controller' => 'users', 'action' => 'loginPopup'], 'autocomplete' => 'off', 'class' => 'login-form', 'id' => 'e_frm']); ?>
            <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email address', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
            <div class="mb-2 form-group"><?= $this->Form->control('password', ['label' => 'Password', 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
            <div class="mb-2">
                <div id="f_err"></div>
            </div>
            <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Sign in" id="login_sbtn">
            <div class="text-center">
                <a href="<?= SITEURL ?>register" class="btn btn-secondary w-100">Create your Portfolio!</a>
                <br>

                <a href="<?= SITEURL ?>reset-password" class="linkTxt">Forgot your password?</a>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


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
                    $("#login_sbtn").val('Sign in');
                },
                error: function(response) {
                    $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
                    $("#login_sbtn").prop("disabled", false);
                    $("#login_sbtn").val('Sign in');
                },
            }).submit();
        });
    });
</script>