<?php
$data = $data['WhoisRecord'];
$this->assign('title', $data['domainName'] . ' | ' . env('APP_NAME'));
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

  .mt--120 {
    margin-top: -140px;
  }

  h5.card-title {
    color: rgb(107 114 128);
    font-size: .875rem;
    line-height: 1.25rem;
    margin: 0;
  }

  tbody.text-left tr>td:last-child {
    text-align: left;
  }

  .font-600 {
    color: #000;
    font-size: 105%;
  }

  .clrVeg {
    display: inline-block;
    color: rgb(229, 231, 235);
    background-color: rgb(168, 85, 247);
    border-radius: 0.25rem;
    padding: 0.75rem;
    transition: all 500ms ease;
  }

  .clrVeg:hover {
    background-color: rgb(147, 51, 234);
    color: #fff;
  }

  .card-body p,
  tr,
  td {
    font-family: Nexa, serif !important;
  }
</style>

<main class="pb-12">
  <div class="bg-dark-sec pb-12">
    <div class="container">
      <h2 class="text-white fw-bolder">Whois | <?= $data['domainName']; ?></h2>
    </div>
  </div>

  <div class="container mt--120 pad2rem dashboardPage">
    <div class="row">
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Report Website</h5>
            <p> <?= $data['domainName']; ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Owner</h5>
            <p> <?= ($data['registrant']['organization'] ? $data['registrant']['organization'] : 'N/A'); ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Whois Server</h5>
            <p> <?= $data['registryData']['whoisServer']; ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Registration Date</h5>
            <p><?= date('Y-m-d', strtotime($data['createdDate'])); ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Expiration Date</h5>
            <p><?= date('Y-m-d', strtotime($data['expiresDate'])); ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
      <div class="col-md-6 col-lg-4 mt-3">
        <div class="card   m-auto">
          <div class="card-body ">
            <h5 class="card-title">Registrar Abuse</h5>
            <p><?= ($data['contactEmail'] ? $data['contactEmail'] : 'N.A'); ?></p>
          </div>
        </div>
        <!-- end of card -->
      </div>
      <!-- end of col -->
    </div>
    <!-- end of row -->


    <div class="card mt-3">
      <div class="card-body">
        <h4 class="card-title mb-3">Registrant information</h4>
        <div class="table-responsive">
          <table class="table table-border domainTble" style="border: 1px solid #e5e4e4;border-radius: 5px;">
            <thead>
              <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
              </tr>
            </thead>
            <tbody class="text-left">
              <?php if (isset($data['registrant']) && !empty($data['registrant'])) {
                foreach ($data['registrant'] as $k => $v) {
                  if ($k != 'rawText') { ?>
                    <tr>
                      <td>Registrant <?= $k; ?></td>
                      <td><?= $v; ?></td>
                    </tr>
              <?php }
                }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- end of table card -->

    <div class="card mt-3">
      <div class="card-body">
        <h4 class="card-title mb-3">Admin information</h4>
        <div class="table-responsive">
          <table class="table table-border domainTble" style="
    border: 1px solid #e5e4e4;
    border-radius: 5px;
">
            <thead>
              <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
              </tr>
            </thead>
            <tbody class="text-left">
              <?php if (isset($data['administrativeContact']) && !empty($data['administrativeContact'])) {
                foreach ($data['administrativeContact'] as $k1 => $v1) {
                  if ($k1 != 'rawText') { ?>
                    <tr>
                      <td>Admin <?= $k1; ?></td>
                      <td><?= $v1; ?></td>
                    </tr>
              <?php }
                }
              } ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- end of table card -->

    <div class="card mt-3">
      <div class="card-body">
        <h4 class="card-title mb-3">Tech information</h4>
        <div class="table-responsive">
          <table class="table table-border domainTble" style="
    border: 1px solid #e5e4e4;
    border-radius: 5px;
">
            <thead>
              <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
              </tr>
            </thead>
            <tbody class="text-left">
              <?php if (isset($data['technicalContact']) && !empty($data['technicalContact'])) {
                foreach ($data['technicalContact'] as $k2 => $v2) {
                  if ($k2 != 'rawText') { ?>
                    <tr>
                      <td>Tech <?= $k2; ?></td>
                      <td><?= $v2; ?></td>
                    </tr>
              <?php }
                }
              } ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- end of table card -->

    <div class="card m-auto mt-3 link-inherit">
      <div class="card-body ">
        <h4 class="card-title mb-2">Raw Whois request</h4>
        <p class="font-600">
        <?= nl2br($data['rawText']); ?>
        </p>
      </div>
    </div>
    <!-- end of card -->

    <div class="text-center p-3 mt-2">
      <?= $this->Html->link('Back to Search', '/domains/whois/',['class'=>'clrVeg']); ?>
    </div>
  </div>

</main>