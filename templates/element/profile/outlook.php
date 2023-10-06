<div class="profile-container margin-lr-15px pt-3">
    
    <h1><?= $data->name; ?> (<?= strtoupper($data->symbol); ?>) - Company Outlook</h1>
    

    <?php 
    
    if ( isset($outlook['financialsAnnual']['balance']) && !empty($outlook['financialsAnnual']['balance'])) { ?>
    <br><br>
        <h3>Company Outlook</h3>
        <div style="overflow-y: scroll;">
            <table class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Receivables</th>
                        
                        <th>Debt</th>
                        <th>Liabilities</th>
                        <th>Assets</th>
                        <th>Current Assets</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($outlook['financialsAnnual']['balance'] as $list) { ?>
                        <tr>
                            <td><?= $list['calendarYear'] ?></td>
                            <td><?= nice_number($list['netReceivables']*$data->usd_rate); ?></td>
                            
                            <td><?= nice_number($list['netDebt']*$data->usd_rate); ?></td>
                            <td><?= nice_number($list['totalLiabilities']*$data->usd_rate); ?></td>
                            <td><?= nice_number($list['totalAssets']*$data->usd_rate); ?></td>
                            <td><?= nice_number($list['totalCurrentAssets']*$data->usd_rate); ?></td>

                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <br><br>
</div>