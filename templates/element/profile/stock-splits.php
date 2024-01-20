<div class="profile-container margin-lr-15px pt-3">
    <h1>Stock split history for <?= $data->name; ?> (<?= strtoupper($data->symbol); ?>)</h1>
    
    <p class="mt-2">
        <?= $data->name; ?> stock (symbol: <?= strtoupper($data->symbol); ?>) underwent a total of <?= count($splits['historical']) ?> stock splits.<br>
        <?php if (!empty($splits['historical'])) { ?>
        The most recent stock split occured on <?= date('M d, Y',strtotime($splits['historical'][0]['date']));?>.
        <?php } ?>
    </p>
    <?php if (!empty($settings->banner)) { ?>
        <center class="ads"><?= $settings->banner; ?></center>
    <?php } ?>
    <?php if (!empty($splits['historical'])) { ?>
        <h3>Annual Revenue</h3>
        <div style="overflow-y: scroll;">
            <table class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Splite</th>
                        <th>Multiple</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($splits['historical'] as $list) { ?>
                        <tr>
                            <td><?= $list['date'] ?></td>
                            <td><?= $list['numerator'] . ":" . $list['denominator']; ?></td>
                            <td><?= $list['numerator']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <br><br>
</div>