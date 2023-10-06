<?php 
$a = []; 
if(!empty($pb)){
    foreach($pb as $list){
        $a[]=['d'=>strtotime($list['date']),'v'=>$list['priceToBookRatio']];
    }
    $min = $pb[array_search(min($prices = array_column($pb, 'priceToBookRatio')), $prices)];
    $max = $pb[array_search(max($prices = array_column($pb, 'priceToBookRatio')), $prices)];
}
$last = array_key_last ( $pb );

?>

<div class="profile-container margin-lr-15px pt-3">
    <h1>P/B ratio for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <h2><strong>P/B ratio as of <?= $pb[0]['calendarYear']?>: <span class="background-ya"><?= num_2($pb[0]['priceToBookRatio'])?></span></strong>
    </h2>
    <p class="mt-2">
        According to <strong><?= $data->name; ?></strong>'s latest financial reports and stock price the
        company's current price-to-sales ratio (TTM) is <strong><?= num_2($pb[0]['priceToBookRatio'])?></strong>.
        At the end of <?= $pb[1]['calendarYear']?> the company had a P/B ratio of <strong><?= num_2($pb[1]['priceToBookRatio'])?></strong>. </p>
    <div>
        <h2 class="big">P/B ratio history for <?= $data->name; ?> from <?= $pb[$last]['calendarYear'];?> to <?= $pb[0]['calendarYear']?></h2>
        <svg id="marketcapchart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    </div>
    <div style="overflow-y: scroll;">
        <h3>P/B ratio at the end of each year</h3>
        <table class="table" style="width:100%">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>P/B ratio</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($pb as $list) { ?>
                    <tr>
                        <td><?= $list['calendarYear']; ?></td>
                        <td><?= num_2($list['priceToBookRatio']); ?></td>
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