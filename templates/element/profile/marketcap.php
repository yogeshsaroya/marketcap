<div class="profile-container margin-lr-15px pt-3">
    <h1>Market capitalization of <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <h2><strong>Market cap: <span class="background-ya">$<?= nice_number($data->market_cap); ?></span></strong></h2>
    <p class="mt-2">As of <?= date('M Y'); ?> <strong><?= $data->name; ?></strong> has a market cap of <strong>$<?= nice_number($data->market_cap); ?></strong>.
        This makes <?= $data->name; ?> the world's <strong></strong> most valuable company by market cap according to our data. The market capitalization, commonly called market cap, is the total market value of a publicly traded company's outstanding shares and is commonly used to measure how much a company is worth.</p>
    <div>
        <h2 class="big">Market cap history of <?= $data->name; ?> from 2001 to 2023</h2>
        <svg id="marketcapchart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    </div>
    <?php if (!empty($market_cap)) { ?>
        <h3>Market Cap History</h3>
        <div style="overflow-y: scroll;">
            <table class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Market cap</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $a = [];
                    foreach ($market_cap as $list) {
                        $a[] = ['d' => strtotime($list['date']), 'v' => $list['marketCap'] * $data->usd_rate];

                    ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($list['date'])); ?></td>
                            <td>$<?= nice_number($list['marketCap'] * $data->usd_rate); ?></td>

                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>
    <?php } ?>
    <h2 class="big">End of Day market cap according to different sources</h2>
    <br><br>
</div>

<?php if (!empty($market_cap)) { ?>
    <script src="<?= SITEURL; ?>js/chart1.js"></script>
    <script type="text/javascript">
        data = <?= json_encode($a) ?>;
        var chart1 = new CmcChart();
        chart1.loadChart(document.getElementById('marketcapchart'), data);
    </script>
<?php } ?>