<?php
$this->assign('title', 'Companies ranked by Market Cap');
$this->assign('description', "Ranking the world's top companies by market cap, market value, revenue and many more metrics");
$cap = $this->Data->getCaps();
$theme = $this->request->getSession()->read('theme');

?>

<style>
    .search-form {

        margin: auto;
    }

    .pagination li {
        float: left;
        margin-left: 2px;
        margin-right: 2px;
        list-style-type: none;
    }

    .pagination a,
    .pagination span {
        padding: 5px;
        padding-left: 10px;
        padding-right: 10px;
        border-radius: 5px;
        background-color: #f7f7f7;
        font-size: 20px;
        font-weight: 700;
        color: #444;
    }

    .pagination li.active a,
    .pagination span.active {
        color: #fff;
        background-color: #b599f1;
    }

    .nav_pag {
        margin: 30px 0;
    }
</style>
<h1 class="text-center h1-title">Largest Companies by Market Cap</h1>
<div class="category-stats-bar">
    companies: <span class="companies-count font-weight-bold"><?= number_format($cap['companies']); ?></span>
    total market cap: <span class="font-weight-bold">$<?= $cap['market_cap']; ?></span>
</div>
<div class="row align-center">
    <form class="search-form form-inline">
        <input id="search-input" class="form-control search-input" type="search" placeholder="Company name, ticker..." aria-label="Company name, ticker..." autocomplete="off">
        <button onclick="return false;" class="btn-search" type="submit" disabled><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" width="18" height="18">
                <g stroke-width="2" stroke="#6c6c6c" fill="none">
                    <path d="M11.29 11.71l-4-4"></path>
                    <circle cx="5" cy="5" r="4"></circle>
                </g>
            </svg></button>
        <div id="typeahead-search-results" class="typeahead-search-results"></div>
    </form>

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
                <th tid="1"></th>
                <th tid="1" class="th-id-1 th-name sorting">Rank</th>
                <th tid="2" class="th-id-2 th-name sorting">Name</th>
                <th tid="3" class="th-id-3 th-mcap sorting text-right">Market Cap</th>
                <th tid="4" class="th-id-4 th-price sorting text-right">Price</th>
                <th tid="7" class="th-id-7 th-country sorting">Country</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$data->isEmpty()) {
                $num = $this->Paginator->counter('{{start}}');
                foreach ($data as $list) {

                    $logo = $logo_dark = $logo_nrm =  $list->logo;
                    if (!empty($list->logo_bright)) {
                        $logo_nrm = SITEURL."logo/".$list->logo_bright;
                    }
                    if (!empty($list->logo_dark)) {
                        $logo_dark = SITEURL."logo/".$list->logo_dark;
                    }
                    $logo = (empty($theme) || $theme == 'dark' ? $logo_dark : $logo_nrm);
                    
            ?>
                    <tr>
                        <td>
                            <img src="<?= SITEURL . (isset($star[$list->id]) ? 'img/star_dark.svg' : 'img/star.svg'); ?>" width="32px" alt="" class="is_fev <?= (isset($star[$list->id]) ? 'rm_star' : 'add_star'); ?>" id="sel_<?= $list->id; ?>" data-id="<?= $list->id; ?>" />
                            
                        </td>
                        <td class="td-center" data-sort="<?= $num; ?>"><?= $num; ?></td>
                        <td class="name-td">
                            <div class="logo-container">
                                <?php if (!empty($logo)) { ?>
                                    <img loading="lazy" alt="logo" loading="lazy" class="company-logo" data-img-path="<?= $logo_nrm; ?>" data-img-dark-path="<?= $logo_dark; ?>" src="<?= $logo; ?>" />
                                <?php } ?>
                            </div>

                            <div class="name-div"><a href="<?= SITEURL . $list->slug; ?>">
                                    <div class="company-name"><?= $list->name; ?></div>
                                    <div class="company-code"><span class="rank d-none"></span><?= $list->symbol; ?></div>
                                </a></div>
                        </td>
                        <td class="td-right" data-sort="<?= $list->market_cap; ?>">$<?= nice_number($list->market_cap); ?></td>
                        <td class="td-right" data-sort="<?= $list->stock_price; ?>">$<?= num_2($list->stock_price); ?></td>

                        <td>
                            <?php if (!empty($list->country)) { ?>
                                <img class="flag" src="img/flags/<?= strtolower($list->country); ?>.png"> <span class="responsive-hidden"><?= $list->country; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
            <?php $num++;
                }
            } ?>


        </tbody>
    </table>
</div>
<div class="bottom-table-description">
    <p>This is the list of the world's biggest companies by market capitalization. It ranks the most valuable public
        companies. Private companies are not included in our lists as it is difficult to calculate their market
        value and know their financials.</p>
</div>
<?php
/* ?>
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
<?php */ ?>

<nav class="nav_pag">
    <ul class=" pagination justify-content-center">
        <li class="page-item">
            <?php
            if ($this->request->is('mobile')) {
                echo $this->Paginator->first(__('First', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "page-link"));
                echo $this->Paginator->prev('Prev', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                echo $this->Paginator->next('Next', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                echo $this->Paginator->last(__('Last', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "page-link"));
            } else {
                echo $this->Paginator->first(__('First', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "page-link"));
                echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                echo $this->Paginator->numbers(['first' => 1, 'last' => 1], [['separator' => '', 'tag' => 'li', 'currentLink' => true, 'currentClass' => 'active', 'currentTag' => 'a']]);
                echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                echo $this->Paginator->last(__('Last', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "page-link"));
            }
            ?>
    </ul>
</nav>