<?php $this->assign('title', 'Whois | ' . env('APP_NAME')); ?>
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

  .form-control,
  #login_sbtn {
    font-family: Nexa, serif !important;
  }

  #login_sbtn {
    color: #fff;
    background-color: rgb(147 51 234);
  }
</style>
<main class="pb-12">
  <div class="bg-dark-sec pb-12">
    <div class="container">
      <h2 class="text-white mb-4"><strong>Whois</strong></h2>

    </div>
  </div>

  <div class="container  mt--140">
    <div class="card   m-auto">
      <div class="card-body ">

        <div class="mb-2 form-group"><?= $this->Form->control('domain', ['placeholder' => 'google.com', 'label' => false, 'type' => 'text', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'new-domain']); ?></div>
        <div class="mb-2">
          <div id="f_err"></div>
        </div>
        <input type="button" class="btn btn-light mb-2" value="Lookup" id="login_sbtn" onclick="chk()">

      </div>
    </div>
    <div class="card m-auto mt-3">
      <div class="card-body ">
        <h4 class="card-title mb-2">About</h4>
        <p class="text-gray-800">Whois is a protocol to query information about a domain or a ip address. The data returned when performing a whois request is very useful to be able to identify and report scam websites. For instance, the registration date is a very good indicator as new websites are more likely to be scam as old websites.</p>
        <p>Furthermore, each provider has to provide a abuse contact in the whois record. This can be used to directly report the website.</p>

      </div>
    </div>
  </div>

</main>



<?php $this->append('scriptBottom');  ?>
<script>
  function chk() {
    var val = $.trim($("#domain").val());
    if (val == '') {
      alert('No valid domain provided.');
    } else {
      if (/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/.test(val)) {
        window.location.href = '<?= SITEURL; ?>domains/whois/' + val;
      } else {
        alert("No valid domain provided.");
      }
    }

  }
</script>
<?php $this->end();  ?>