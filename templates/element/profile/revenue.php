<div class="profile-container margin-lr-15px pt-3">

<h1>Revenue of <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <h2><strong>Revenue in <?= $revenue[0]['calendarYear']; ?> (TTM): <span class="background-ya">$<?= nice_number($revenue[0]['revenue']* $data->usd_rate); ?></span></strong></h2>
    <p class="mt-2">
        According to <strong><?= $data->name; ?></strong>'s latest financial reports the company's current
        revenue (TTM) is <strong>$<?= nice_number($revenue[0]['revenue']* $data->usd_rate); ?></strong>.
        The revenue is the total amount of income that a company generates by the sale of goods or services. Unlike with the earnings no expenses are subtracted.
    </p>
    <div>
        <h2 class="big">Revenue history of <?= $data->name; ?></h2>
        <svg id="marketcapchart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    </div>
    <?php if (!empty($revenue)) { ?>
        <h3>Annual Revenue</h3>
        <div style="overflow-y: scroll;">
            <table class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Revenue</th>
                        <th>Gross Profit</th>
                        <th>Ebitda</th>
                        <th>Income Before Tax</th>
                        <th>Net Income</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($revenue as $list) { ?>
                        <tr>
                            <td><?= $list['calendarYear'] ?></td>
                            <td>$<?= nice_number($list['revenue']* $data->usd_rate); ?></td>
                            <td>$<?= nice_number($list['grossProfit']* $data->usd_rate); ?></td>
                            <td>$<?= nice_number($list['ebitda']* $data->usd_rate); ?></td>
                            <td>$<?= nice_number($list['incomeBeforeTax']* $data->usd_rate); ?></td>
                            <td>$<?= nice_number($list['netIncome']* $data->usd_rate); ?></td>

                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>
    <?php } ?>
    
    <br><br>
</div>

<?php if (!empty($revenue)) {
    $r = null;
    foreach ($revenue as $list) {
        $r[$list['calendarYear']] =   $list['revenue']* $data->usd_rate;
    }
?>
    <script src="<?= SITEURL; ?>js/chart1.js"></script>
    <script type="text/javascript">
        data = <?= json_encode($r) ?>;
        var chart1 = new CmcChart();
        chart1.loadChart(document.getElementById('marketcapchart'), data);
    </script>
<?php } ?>