<?php $this->assign('title', 'Chat | ' . env('APP_NAME'));
$auth = $this->request->getSession()->read('Auth.User');
?>
<style>
  @font-face {
    font-display: swap;
    font-family: Nexa;
    font-style: normal;
    font-weight: 400;
    src: url(<?= SITEURL; ?>font/NexaRegular.woff2) format("woff2")
  }

  @font-face {
    font-display: swap;
    font-family: Nexa;
    font-style: normal;
    font-weight: 700;
    src: url(<?= SITEURL; ?>font/NexaBold.woff2) format("woff2")
  }

  .time_date {
    font-family: Nexa, serif !important;
    color: rgb(75 85 99);
    display: block;
    font-size: 12px;
    margin: 8px 0 0;
    font-size: 16px;
  }

  .received_withd_msg {
    width: 57%;
  }

  .received_withd_msg p {

    background: rgb(209 213 219);
    border-radius: 0.5rem;
    font-size: 16px;
    padding: 10px;
    color: #000;
  }

  .mesgs {
    float: left;
    padding: 30px 15px 0 25px;
    width: 60%;
  }

  .outgoing_msg p {
    text-align: right;
  }

  .sent_msg p {
    background: rgb(134 239 172);
    border-radius: 3px;
    font-size: 14px;
    margin: 0;
    color: #000;
    padding: 10px;
    width: 100%;
    border-radius: 0.5rem;
    font-size: 16px;

  }

  .outgoing_msg {
    overflow: hidden;
    margin: 26px 0 26px;
  }

  .outgoing_msg .time_date {
    text-align: right;
  }

  .sent_msg {
    float: right;
    width: 46%;
  }

  .msg_history {
    height: 400px;
    overflow-y: scroll;
    overflow-x: hidden;
  }

  @media only screen and (max-width: 786px) and (min-width: 300px) {
    .received_withd_msg {
      width: 100%;
    }

    .sent_msg {
      float: right;
      width: 100%;
    }
  }
</style>
<main class="pb-12">
  <div class="bg-dark-sec pb-12">
    <div class="container">
      <h2 class="text-white"><strong>Contact</strong></h2>
    </div>
  </div>

  <div class="container mt--90 pad2rem">
    <div class="card   m-auto">
      <div class="card-body minHt250">
        <?php if (!$messages->isEmpty()) { ?>
          <div class="msg_history">
            <?php foreach ($messages as $message) {
              if ($message->message_type == 1) { ?>
                <div class="outgoing_msg">
                  <div class="sent_msg">
                    <span class="time_date"><?= $message->created->format('d/m/Y, H:i:s'); ?> - <?= $auth->email; ?></span>
                    <p><?= $message->message; ?></p>
                  </div>
                </div>
              <?php } elseif ($message->message_type == 2) { ?>
                <div class="incoming_msg">
                  <div class="received_msg">
                    <div class="received_withd_msg">
                      <span class="time_date"><?= $message->created->format('d/m/Y, H:i:s'); ?></span>
                      <p><?= $message->message; ?></p>
                    </div>
                  </div>
                </div>
            <?php }
            } ?>
          </div>
        <?php } else { ?>
          <p class="text-gray-600 text-center">No messages yet</p>
        <?php } ?>

      </div>
    </div>
    <!-- end of card -->
    <div class="contctFrom mt-3">
      <?php
      echo $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'msg_frm']);
      echo $this->Form->hidden('type', ['value' => 'msg']);
      echo $this->Form->hidden('message_type', ['value' => 1]);
      echo $this->Form->hidden('user_id', ['value' => $auth->id]);
      ?>
      <div>
        <div class="mb-2 form-group"><?= $this->Form->control('message', ['label' => false, 'type' => 'textarea', 'rows' => 3, 'class' => 'form-control mb-3', 'required' => true, 'placeholder' => 'Enter your message here']); ?></div>
        <div class="mb-2">
          <div id="msg_err"></div>
        </div>
        <input type="button" class="btn btn-primary w-100 mb-2" value="Send" id="mk_msg">
      </div>
      <?php echo $this->Form->end(); ?>

      <div class="card   m-auto mt-3">
        <div class="card-body ">
          <h4 class="card-title mb-2">Alternative contact options</h4>
          <p>Discord: <a class="text-blue-500" href="https://discord.gg/dnMuH6JgRD">https://discord.gg/dnMuH6JgRD</a> </p>
          <p>Email: <a href="mailto:cosmorecovery@interbloc.org" class="clrInherit">cosmorecovery@interbloc.org</a> </p>
        </div>
      </div>
      <!-- end of card -->

      <div class="card   m-auto mt-3">
        <div class="card-body ">
          <h4 class="card-title mb-2">File upload</h4>
          <p>You can use following uplaod form to share images/screenshots with us.</p>
          <?php
          echo $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'img_frm', 'type' => 'file']);
          echo $this->Form->hidden('type', ['value' => 'images']);
          echo $this->Form->hidden('user_id', ['value' => $auth->id]);
          ?>
          <div class="mt-2 mb-2 fileBtn"><?= $this->Form->file('submittedfile', ['required' => true, 'accept' => 'image/*']); ?></div>
          <div class="mb-2">
            <div id="img_err"></div>
          </div>
          <input type="button" class="btn btn-secondary w-100 mb-2" value="Upload" id="img_sbtn">
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
      <!-- end of card -->

      <?php if (!$photos->isEmpty()) { ?>
        <div class="card   m-auto mt-3">
          <div class="card-body">
            <div class="uploadFileView">
              <div class="row">
                <?php foreach ($photos as $photo) { ?>
                  <div class="col-lg-4 col-sm-6">
                    <img src="<?= SITEURL . "cdn/files/" . $photo->image; ?>" class="img-thumbnail" alt="...">
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</main>


<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    $(".msg_history").animate({
      scrollTop: $('.msg_history').prop("scrollHeight")
    }, 1000);

    $('#mk_msg').jbvalidator({
      errorMessage: true,
      successClass: true,
    });
    $('#msg_frm').jbvalidator({
      errorMessage: true,
      successClass: true,
    });


    $("#mk_msg").click(function() {
      $("#msg_frm").ajaxForm({
        target: '#msg_err',
        headers: {
          'X-CSRF-Token': $('[name="_csrfToken"]').val()
        },
        beforeSubmit: function() {
          $("#mk_msg").prop("disabled", true);
          $("#mk_msg").val('Please wait..');
        },
        success: function(response) {
          $("#mk_msg").prop("disabled", false);
          $("#mk_msg").val('Send');
        },
        error: function(response) {
          $('#f_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#mk_msg").prop("disabled", false);
          $("#mk_msg").val('Send');
        },
      }).submit();
    });

    $("#img_sbtn").click(function() {
      $("#img_frm").ajaxForm({
        target: '#img_err',
        headers: {
          'X-CSRF-Token': $('[name="_csrfToken"]').val()
        },
        beforeSubmit: function() {
          $("#img_sbtn").prop("disabled", true);
          $("#img_sbtn").val('Please wait..');
        },
        success: function(response) {
          $("#img_sbtn").prop("disabled", false);
          $("#img_sbtn").val('Upload');
        },
        error: function(response) {
          $('#w_err').html('<div class="alert alert-danger">Sorry, this is not working at the moment. Please try again later.</div>');
          $("#img_sbtn").prop("disabled", false);
          $("#img_sbtn").val('Upload');
        },
      }).submit();
    });

  });
</script>
<?php $this->end();  ?>