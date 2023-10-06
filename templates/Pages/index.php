<?php
$this->assign('title', 'Companies ranked by Market Cap');
$this->assign('description', "Ranking the world's top companies by market cap, market value, revenue and many more metrics");

$cap = $this->Data->getCaps();

?>

<h1 class="text-center h1-title">Largest Companies by Market Cap</h1>
<div class="category-stats-bar">
    companies: <span class="companies-count font-weight-bold"><?= number_format($cap['companies']); ?></span>
    total market cap: <span class="font-weight-bold">$<?= $cap['market_cap']; ?></span>
</div>
<?php /* ?>
<div class="ranking-bar"><span class="ranked-by">Rank by </span><a href="index.html"><span class="option active">Market Cap</span></a> <a href="most-profitable-companies.html"><span class="option ">Earnings</span></a> <a href="largest-companies-by-revenue.html"><span class="option ">Revenue</span></a> <a href="largest-companies-by-number-of-employees.html"><span class="option ">Employees</span></a> <a href="top-companies-by-pe-ratio.html"><span class="option ">P/E
            ratio</span></a> <a href="top-companies-by-dividend-yield.html"><span class="option ">Dividend
            %</span></a> <a href="top-companies-by-market-cap-gain.html"><span class="option ">Market Cap
            gain</span></a> <a href="top-companies-by-market-cap-loss.html"><span class="option  expandable-rank">Market Cap loss</span></a> <a href="top-companies-by-operating-margin.html"><span class="option  expandable-rank">Operating
            Margin</span></a> <a href="companies-with-the-highest-cost-to-borrow.html"><span class="option  expandable-rank">Cost to borrow</span></a> <a href="top-companies-by-total-assets.html"><span class="option  expandable-rank">Total assets</span></a> <a href="top-companies-by-net-assets.html"><span class="option  expandable-rank">Net assets</span></a> <a href="companies-with-the-highest-liabilities.html"><span class="option  expandable-rank">Total
            liabilities</span></a> <a href="companies-with-the-highest-debt.html"><span class="option  expandable-rank">Total debt</span></a> <a href="companies-with-the-highest-cash-on-hand.html"><span class="option  expandable-rank">Cash on
            hand</span></a> <a href="companies-with-lowest-pb-ratio.html"><span class="option  expandable-rank">P/B
            ratio</span></a> <a href="#" onclick="this.style.display = 'none.html';showMoreRankingTabs(); return false;"><span class="option more-option">More +</span></a> 
        </div>
        <?php */ ?>
<div class="table-container shadow">
    <table class="default-table table marketcap-table dataTable" style="width:100%">
        <thead>
            <tr>
                <th tid="2" class="th-id-2 th-name sorting">Name</th>
                <th tid="3" class="th-id-3 th-mcap sorting text-right">Market Cap</th>
                <th tid="4" class="th-id-4 th-price sorting text-right">Price</th>
                <th tid="7" class="th-id-7 th-country sorting">Country</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$data->isEmpty()) {
                foreach ($data as $list) {
            ?>
                    <tr>
                        <td class="name-td">
                            <div class="logo-container">
                                <?php if (!empty($list->logo)) { ?>
                                    <img loading="lazy" class="company-logo" alt="logo" src="<?= $list->logo; ?>" />
                                <?php } ?>
                            </div>

                            <div class="name-div"><a href="<?= SITEURL . $list->slug; ?>">
                                    <div class="company-name"><?= $list->name; ?></div>
                                    <div class="company-code"><span class="rank d-none"></span><?= $list->symbol; ?></div>
                                </a></div>
                        </td>
                        <td class="td-right" data-sort="<?= $list->market_cap; ?>">$<?= nice_number($list->market_cap); ?></td>
                        <td class="td-right" data-sort="<?= $list->stock_price; ?>">$<?= num_2($list->stock_price); ?></td>

                        <td><img class="flag" src="img/flags/<?= $list->country; ?>.png"> <span class="responsive-hidden"><?= $list->country; ?></span></td>
                    </tr>
            <?php }
            } ?>


        </tbody>
    </table>
</div>
<div class="bottom-table-description">
    <p>This is the list of the world's biggest companies by market capitalization. It ranks the most valuable public
        companies. Private companies are not included in our lists as it is difficult to calculate their market
        value and know their financials.</p>
</div>
<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <?php
            if ($this->Paginator->hasNext()) {
                echo $this->Paginator->next('Next »', ['class' => 'page-link']);
            }
            ?>
        </li>
    </ul>
</nav>