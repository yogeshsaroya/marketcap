<?php
$this->assign('title', 'Profile');

$cap = $this->Data->getCaps();
$theme = $this->request->getSession()->read('theme');
echo $this->Html->css(['login'], ['block' => 'css'])

?>
<br>
<h1 class="text-center h1-title">Profile</h1>
<br>

<div class="ranking-bar">

<a href="<?= SITEURL ?>watchlist"><span class="option ">Watchlist</span></a>
    <a href="<?= SITEURL ?>dashboard"><span class="option active">Portfolio</span></a>
    
    <a href="<?= SITEURL ?>users/profile"><span class="option ">Profile</span></a>
    <a href="<?= SITEURL ?>users/logout"><span class="option ">Logout</span></a>

</div>

<div class="table-container shadow">
    <div class="login-page">
        <div class="form">
            <h2>Update Profile</h2>
            <br>
            <?php 
            echo $this->Form->create($profile, ['url' => ['controller' => 'users', 'action' => 'profile'], 'autocomplete' => 'off', 'class' => 'login-form', 'id' => 'e_frm']); 
            echo $this->Form->hidden('id');
            ?>
            <div class="mb-2 form-group"><?= $this->Form->control('first_name', ['label' => 'First Name', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-first-name']); ?></div>
            <div class="mb-2 form-group"><?= $this->Form->control('last_name', ['label' => 'Last Name', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-last-name']); ?></div>
            <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email', 'type' => 'email', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-email']); ?></div>
            <div class="mb-2 form-group"><?= $this->Form->control('password1', ['label' => ['escape' => false, 'text' => 'Password <small>(leave blank if do not want to change)</small>'], 'type' => 'password', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-password']); ?></div>
            <div class="mb-2">
                <div id="f_err"></div>
            </div>
            <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Update" id="login_sbtn">

            <?php echo $this->Form->end(); ?>
        </div>
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
                    $("#login_sbtn").val('Update');
                },
                error: function(response) {
                    $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
                    $("#login_sbtn").prop("disabled", false);
                    $("#login_sbtn").val('Update');
                },
            }).submit();
        });
    });
</script>
<?php $this->end();  ?>