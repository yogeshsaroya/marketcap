<?php $this->assign('title', 'Wallet | ' . env('APP_NAME'));
$auth = $this->request->getSession()->read('Auth.User');
?>
<style>
  .table-border tbody tr > td:last-child {
    text-align: left;
}
</style>

<main class="pb-12">
  <div class="bg-dark-sec pb-12">
    <div class="container">
      <h2 class="text-white"><strong>Wallet</strong></h2>
    </div>
  </div>
  <div class="container mt--90 pad2rem dashboardPage">
    <div class="card   m-auto">
      <div class="card-body ">
        <p>This page will help you to have an overview about your wallet and to monitor all upcoming
          unbondings. The time is always displayed in your own local timezone!
          <br><br>
          To be able to use our recovery service you will need to add your wallet by importing the seed. We are fully
          aware that it's against every best practice to enter your seed on a website
          but it is unfortunately the only way for us to perform the recovery and try to return you your funds. If you
          have further question about how this page works and what we do with your seed feel free to ask us through
          the Contact page (chat)!
          <br><br>
          After having added your wallet you can add all unbondings by simply pasting in the according address, select the associated wallet and the coin.
          The unbondings will be parsed automatically from the chain and appear on the page immediately.
        </p>
      </div>
    </div>
    <div class="card mt-3 m--lr">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-border domainTble">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col" class="text-left">Seed</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (!$data->isEmpty()) {
                foreach ($data as $list) {  ?>
                  <tr>
                    <td><?= $list->name;?></td>
                    <td class="text-left"><?= $list->seed;?></td>
                    <?php /* ?><td><a href="#" class="deleteBtn">Delete</a></td> <?php */ ?>
                  </tr>
              <?php }
              } ?>

            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- end of card -->
    <div class="card   m-auto mt-3">
      <div class="card-body">
        <?php
        echo  $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'e_frm']);
        echo $this->Form->hidden('type', ['value' => 'wallet_1']);
        echo $this->Form->hidden('user_id', ['value' => $auth->id]);
        ?>
        <div class="mb-2 form-group"><?= $this->Form->control('name', ['label' => 'Name to identify this wallet', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-name']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('seed', ['label' => 'Seed or private key', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-seed']); ?></div>
        <div class="mb-2">
          <div id="f_err"></div>
        </div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Save" id="login_sbtn">
        <?php echo $this->Form->end(); ?>
      </div>
    </div>

    <div class="card   m-auto mt-3">
      <div class="card-body">
        <h4 class="card-title mb-2">Add Unbondings (Parsed automatically - submission may take some time)</h4>
        <?php
        echo $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'w_frm']);
        echo $this->Form->hidden('type', ['value' => 'wallet_2']);
        echo $this->Form->hidden('user_id', ['value' => $auth->id]);
        ?>
        <div class="mb-2 form-group"><?= $this->Form->control('wallet_address', ['label' => 'Wallet address', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-wallet_address']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('coin', ['label' => 'Coin', 'options' => seeds(), 'empty' => 'select', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-coin']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('wallet_id', ['label' => 'Associated key/seed','options' => $seeds, 'empty' => 'select', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-wallet_id']); ?></div>
        <div class="mb-2"><div id="w_err"></div></div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Save" id="w_sbtn">
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</main>



<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    $('#e_frm').jbvalidator({
      errorMessage: true,
      successClass: true,
    });
    $('#w_frm').jbvalidator({
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
          $("#login_sbtn").val('Save');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#login_sbtn").prop("disabled", false);
          $("#login_sbtn").val('Save');
        },
      }).submit();
    });

    $("#w_sbtn").click(function() {
      $("#w_frm").ajaxForm({
        target: '#w_err',
        headers: {
          'X-CSRF-Token': $('[name="_csrfToken"]').val()
        },
        beforeSubmit: function() {
          $("#w_sbtn").prop("disabled", true);
          $("#w_sbtn").val('Please wait..');
        },
        success: function(response) {
          $("#w_sbtn").prop("disabled", false);
          $("#w_sbtn").val('Save');
        },
        error: function(response) {
          $('#w_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#w_sbtn").prop("disabled", false);
          $("#w_sbtn").val('Save');
        },
      }).submit();
    });

  });
</script>
<?php $this->end();  ?>