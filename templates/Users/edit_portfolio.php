<div id="custom-content" class="white-popup-block offer-pop" style="max-width:500px; margin: 20px auto;">
    <style>
        .form {

            filter: unset;
            border-radius: 5px;
            width: 100%;
            padding: 0px;
        }

        .login-page {
            padding: 20px 10px;

        }
    </style>
    <div class="app-contentcontent ">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Add to Portfolio  </h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12  ">

                </div>
            </div>
            <div class="content-body">
                <!-- Blog Edit -->
                <div class="blog-edit-wrapper">

                    <div class="table-container shadow1">
                        <div class="login-page">
                            <div class="form">
                                <h4><?= $data->name?> <small>(<?= strtoupper($data->symbol)?>)</small></h4>
                                <br>

                                <?php
                                echo $this->Form->create($data, ['url' => ['controller' => 'users', 'action' => 'updatePortfolio'], 'autocomplete' => 'off', 'class' => 'login-form', 'id' => 'e_frm']);
                                echo $this->Form->hidden('id');
                                ?>
                                <div class="mb-2 form-group"><?= $this->Form->control('buy_date', ['label' => 'Buy Date', 'readonly' => true, 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-buy-date']); ?></div>
                                <div class="mb-2 form-group"><?= $this->Form->control('qty', ['label' => 'Total Quantity', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-qty']); ?></div>
                                <div class="mb-2 form-group"><?= $this->Form->control('rate', ['label' => 'Buy Price', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-rate']); ?></div>
                                <div class="mb-2">
                                    <div id="f_err"></div>
                                </div>
                                <input type="button" class="btn btn-primary w-100 mb-2 login_sbtn" value="Save" id="login_sbtn">

                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var numberMask = IMask(document.getElementById('qty'), {
        mask: Number,
        scale: 0,
        min: 1,
        max: 9999999,
        thousandsSeparator: ''
    }).on('accept', function() {
        document.getElementById('qty').innerHTML = numberMask.masked.number;
    });

    var ratMask = IMask(document.getElementById('rate'), {
        mask: Number,
        scale: 2,
        min: 0.01,
        max: 9999999,

        radix: '.', // fractional delimiter
        mapToRadix: ['.'], // symbols to process as radix
        thousandsSeparator: ''
    }).on('accept', function() {
        document.getElementById('rate').innerHTML = ratMask.masked.number;
    });



    $(document).ready(function() {
        $("#buy-date").datepicker({
            maxDate: 0,
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true
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
                    $("#login_sbtn").val('Save');
                },
                error: function(response) {
                    $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
                    $("#login_sbtn").prop("disabled", false);
                    $("#login_sbtn").val('Save');
                },
            }).submit();
        });

    });
</script>

<?php $this->append('scriptBottom');  ?>
<script>
    $(document).ready(function() {

        
    });
</script>
<?php $this->end();  ?>