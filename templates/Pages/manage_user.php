<?php $this->assign('title', 'Manage User');
?>
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Manage User</h2>

                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12  ">

            </div>
        </div>
        <div class="content-body">
            <!-- Blog Edit -->
            <div class="blog-edit-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                echo $this->Form->create($get_data, ['autocomplete' => 'off', 'id' => 'e_frm', 'class' => 'mt-2', 'data-toggle' => 'validator']);
                                echo $this->Form->hidden('id');
                                ?>

                                <div class="row">

                                    <div class="col-md-4 col-12 form-group mb-2"><?php echo $this->Form->control('first_name', ['class' => 'form-control', 'required' => true]); ?><div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-4 col-12 form-group mb-2"><?php echo $this->Form->control('last_name', ['class' => 'form-control', 'required' => true]); ?><div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-4 col-12 form-group mb-2"><?php echo $this->Form->control('email', ['class' => 'form-control', 'required' => true]); ?><div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-4 col-12 form-group mb-2"><?php

                                                                                    if (empty($get_data)) {
                                                                                        echo $this->Form->control('password1', ['class' => 'form-control', 'required' => true, 'label' => ['escape' => false, 'text' => 'Password']]);
                                                                                    } else {
                                                                                        echo $this->Form->control('password1', ['class' => 'form-control', 'required' => false, 'label' => ['escape' => false, 'text' => 'Password <small>(Leave blank if do not want to change password)</small>']]);
                                                                                    }
                                                                                    ?>
                                        <div class="help-block with-errors"></div>
                                    </div>


                                    <div class="col-12 mt-50">
                                        <div id="f_err"></div>
                                    </div>

                                    <div class="col-12 mt-50">
                                        <input type="button" class="btn btn-primary mr-1" value="Save" id="save_frm" />
                                        <?= $this->Html->link('Back to List', '/pages/users', ['class' => 'btn btn-default mr-1 float-right']); ?>

                                    </div>
                                </div>
                                </form>
                                <!--/ Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Blog Edit -->

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#e_frm").validator();

        $("#save_frm").click(function() {
            $("#e_frm").ajaxForm({
                target: '#f_err',
                headers: {
                    'X-CSRF-Token': $('[name="_csrfToken"]').val()
                },
                beforeSubmit: function() {
                    $("#save_frm").prop("disabled", true);
                    $("#save_frm").val('Please wait..');
                },
                success: function(response) {
                    $("#save_frm").prop("disabled", false);
                    $("#save_frm").val('Save Changes');
                },
                error: function(response) {
                    $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
                    $("#save_frm").prop("disabled", false);
                    $("#save_frm").val('Save Changes');
                },
            }).submit();
        });
    });
</script>