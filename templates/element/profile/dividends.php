<?php

$dev = $dividends['historical'];
$tot = array_sum(array_column($dev, 'dividend'));
$a = [];
if (!empty($dev)) {
    foreach ($dev as $list) {
        $a[]=['d'=>strtotime($list['date']),'v'=>(float)$list['dividend']*$data->usd_rate];
    }
}
$last = array_key_last ( $dev );
?>

<div class="profile-container margin-lr-15px pt-3">
    <h1>Dividend history for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <p>
        <?= $data->name; ?> made a total of <?= count($dev) ?> dividend payments.<br />
        The sum of all dividends (adjusted for stock splits) is : $<?= num_2($tot*$data->usd_rate) ?><br /></p>
    <h2 class="big">Dividend payments for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>) from <?= date('Y',strtotime($dev[$last]['date']));?> to <?= date('Y',strtotime($dev[0]['date']));?></h2>
    <svg id="dividendschart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    
    <?php if (!empty($settings->banner)) { ?>
        <center class="ads"><?= $settings->banner; ?></center>
    <?php } ?>
    <h3>Annual dividend payments</h3>
    <table class="table" style="width:100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Dividend</th>
                <th>Adj Dividend</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dev as $list) { ?>
                <tr>
                    <td><?= $list['label']; ?></td>
                    <td>$<?= num_2($list['dividend']*$data->usd_rate); ?></td>
                    <td>$<?= num_2($list['adjDividend']*$data->usd_rate); ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>


</div>
</div>
<script src="<?= SITEURL; ?>js/chart1.js"></script>
<script type="text/javascript">
    data = <?= json_encode($a) ?>;
    var chart1 = new CmcChart();
    chart1.formatCursorValue = function(val) {
        if (val < 0.01 && val > -0.01) {
            return '$' + Math.round(val * 10000) / 10000;
        }
        if (val < 0.1 && val > -0.1) {
            return '$' + Math.round(val * 1000) / 1000;
        }
        if (val < 10 && val > -10) {
            return '$' + Math.round(val * 100) / 100;
        } else {
            return '$' + Math.round(val * 10) / 10;
        }
        return val;
    };
    chart1.dateFormat = 'y';
    chart1.loadChart(document.getElementById('dividendschart'), data);
</script>