<?php 
$a = []; 
if(!empty($pe)){
    foreach($pe as $list){
        $a[]=['d'=>strtotime($list['date']),'v'=>$list['priceEarningsRatio']];
    }
    $min = $pe[array_search(min($prices = array_column($pe, 'priceEarningsRatio')), $prices)];
    $max = $pe[array_search(max($prices = array_column($pe, 'priceEarningsRatio')), $prices)];
}
$last = array_key_last ( $pe );

?>

<div class="profile-container margin-lr-15px pt-3">
    <h1>P/E ratio for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <h2><strong>P/E ratio as of <?= $pe[0]['calendarYear']?>: <span class="background-ya"><?= num_2($pe[0]['priceEarningsRatio'])?></span></strong>
    </h2>
    <p class="mt-2">
        According to <strong><?= $data->name; ?></strong>'s latest financial reports and stock price the
        company's current price-to-earnings ratio (TTM) is <strong><?= num_2($pe[0]['priceEarningsRatio'])?></strong>.
        At the end of <?= $pe[1]['calendarYear']?> the company had a P/E ratio of <strong><?= num_2($pe[1]['priceEarningsRatio'])?></strong>. </p>
    <div>
        <h2 class="big">P/E ratio history for <?= $data->name; ?> from <?= $pe[$last]['calendarYear'];?> to <?= $pe[0]['calendarYear']?></h2>
        <svg id="marketcapchart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    </div>
    <?php if (!empty($settings->banner)) { ?>
        <center class="ads"><?= $settings->banner; ?></center>
    <?php } ?>
    <div style="overflow-y: scroll;">
        <h3>PE ratio at the end of each year</h3>
        <table class="table" style="width:100%">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>P/E ratio</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($pe as $list) { ?>
                    <tr>
                        <td><?= $list['calendarYear']; ?></td>
                        <td><?= num_2($list['priceEarningsRatio']); ?></td>
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
        if (val < 10 && val > -10) {
            return Math.round(val * 100) / 100;
        } else {
            return Math.round(val * 10) / 10;
        }
        return val;
    };
    chart1.loadChart(document.getElementById('marketcapchart'), data);
</script>