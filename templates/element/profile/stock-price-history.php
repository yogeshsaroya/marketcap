<?php 
$array = $price['historical'];
$min = $max = null;
$a = [];
if(!empty($array)){
    foreach($array as $list){
        $a[]=['d'=>strtotime($list['date']),'v'=>$list['close']*$data->usd_rate];
    }
    $min = $array[array_search(min($prices = array_column($array, 'close')), $prices)];
    $max = $array[array_search(max($prices = array_column($array, 'close')), $prices)];
}
$last = array_key_last ( $array );
?>


<div class="profile-container margin-lr-15px pt-3">
    <h1>Stock price history for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    <p>Highest end of day price: $<?= num_2($max['close']*$data->usd_rate);?> USD on <?= $max['date'];?></p>
    <p>Lowest end of day price: $<?= num_2($min['close']*$data->usd_rate);?> USD on <?= $min['date'];?></p>
    <h2 class="big">Stock price history of <?= $data->name; ?> from <?= date('Y',strtotime($array[$last]['date']));?> to <?= date('Y',strtotime($array[0]['date']));?></h2>
    <svg id="pricechart" class="companiesmarketcap-chart" height="550" width="100%" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"></svg>
    <div>
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
    chart1.loadChart(document.getElementById('pricechart'), data);
</script>