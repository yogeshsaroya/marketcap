<?php $this->assign('title', 'Dashboard | ' . env('APP_NAME'));
$auth = $this->request->getSession()->read('Auth.User');
if (isset($settings->hcaptcha_sitekey) && !empty($settings->hcaptcha_sitekey)) {
  echo $this->Html->script(['https://www.hCaptcha.com/1/api.js'], ['block' => 'scriptBottom', 'async', 'defer']);
}
?>


<main class="pb-12">
  <div class="bg-dark-sec pb-12">
    <div class="container">
      <h2 class="text-white"><strong>Dashboard</strong></h2>
    </div>
  </div>

  <div class="container mt--90 pad2rem dashboardPage">
    <div class="card   m-auto">
      <div class="card-body ">
        <h4 class="card-title mb-3">Report Website</h4>
        <?= $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'e_frm']); ?>
        <div class="mb-2 form-group"><?= $this->Form->control('domain', ['label' => 'Domain', 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-domain']); ?></div>
        <div class="mb-2 form-group"><?= $this->Form->control('reason', ['label' => 'Reason', 'type' => 'textarea', 'rows' => 3, 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-reason']); ?></div>
        <?php if (isset($auth->email) && !empty($auth->email)) {
          echo $this->Form->hidden('email', ['value' => $auth->email]);
        } else { ?>
          <div class="mb-2 form-group"><?= $this->Form->control('email', ['label' => 'Email (Optional)', 'type' => 'email', 'class' => 'form-control', 'required' => false, 'autocomplete' => 'new-email']); ?></div>
        <?php } ?>

        <?php if (isset($settings->hcaptcha_sitekey) && !empty($settings->hcaptcha_sitekey)) { ?>
          <div class="mb-2">
            <div class="h-captcha" data-sitekey="<?= $settings->hcaptcha_sitekey; ?>"></div>
          </div>
        <?php } ?>

        <div class="mb-2">
          <div id="f_err"></div>
        </div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Report" id="login_sbtn">
        <?php echo $this->Form->end(); ?>
      </div>
    </div>

    <div class="card   m-auto mt-3">
      <div class="card-body ">
        <h4 class="card-title mb-3">Latest Reports</h4>
        <div class="table-responsive">
          <table class="table domainTble">
            <thead>
              <tr>
                <th scope="col">Domain</th>
                <th scope="col">Reason</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (!$data->isEmpty()) {
                foreach ($data as $list) {  ?>
                  <tr>
                    <td><?= $this->Html->link($list->domain, '/domains/whois/' . $list->domain); ?></td>
                    <td><?= $list->reason; ?></td>
                    <td class="
                    <?php
                    if ($list->status == 1) {
                      echo 'text-danger';
                    } elseif ($list->status == 2) {
                      echo 'text-danger';
                    } elseif ($list->status == 3) {
                      echo 'text-success';
                    }
                    ?>">
                      <?php
                      if ($list->status == 1) {
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16px" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                      } elseif ($list->status == 2) {
                        echo 'Fraudulent';
                      } elseif ($list->status == 3) {
                        echo 'No fraud detected';
                      }
                      ?></td>
                  </tr>
              <?php }
              } ?>

            </tbody>
          </table>

        </div>
        <p class="text-italic">Press the Domain name to see the according whois record</p>
      </div>
    </div>
    <!-- end of card -->
    <div class="card   m-auto mt-3">
      <div class="card-body link-inherit">
        <h4 class="card-title mb-2">About the CosmoRecovery Scam Blacklist</h4>
        <p>The Scam Blacklist contains all fraudulent websites that have been detected through our monitoring or by user reports. The blacklist itself is updated multiple times a week and syncronised with the Keplr <a href="https://github.com/chainapsis/phishing-block-list">Phishing block list</a>. The Keplr Phishing black list is a joint effort between Chainapsis and CosmoRecovery to prevent scams before they happen.
          <br><br>
          If you want to integrate the Blacklist into your project you can do so by using the github repository or our API which comes with more features like a search and pagination. If you need a custom solution feel free to reach out to us and we'll do our best to help you.
        </p>
      </div>
    </div>
    <!-- end of card -->

    <div class="card m-auto mt-3 link-inherit">
      <div class="card-body ">
        <h4 class="card-title mb-2">Hacked wallet recovery</h4>
        <p> A hacked cryptocurrency wallet puts the victim in a bad situation. But as long as the coins didn't leave the wallet (for instance because of a unbonding period) they aren't lost and there might be a chance to get them back through a recovery.
          <br><br>
          To achieve this we've developed a script which submits transactions to the blockchain very fast in order to beat the scammer.
          <br><br>
          You can contact us at any time through the contact site to get helped with your hacked wallet.
        </p>
      </div>
    </div>
    <!-- end of card -->
  </div>

</main>




<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    let validator = $('#e_frm').jbvalidator({
      errorMessage: true,
      successClass: true,
    });

    <?php if (isset($settings->hcaptcha_sitekey) && !empty($settings->hcaptcha_sitekey)) { ?>
      $("#login_sbtn").click(function() {
        $('#f_err').html('');
        var hcaptchaVal = $('[name=h-captcha-response]').val();
        if (hcaptchaVal === "") {
          $('#f_err').html('<div class="alert alert-danger">Please complete the hCaptcha</div>');
        } else {
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
              $("#login_sbtn").val('Report');
            },
            error: function(response) {
              $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
              $("#login_sbtn").prop("disabled", false);
              $("#login_sbtn").val('Report');
            },
          }).submit();
        }

      });
    <?php } else { ?>
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
            $("#login_sbtn").val('Report');
          },
          error: function(response) {
            $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
            $("#login_sbtn").prop("disabled", false);
            $("#login_sbtn").val('Report');
          },
        }).submit();


      });
    <?php } ?>


  });
</script>
<?php $this->end();  ?>