<?php
$this->assign('title', $data->name . ' - Market capitalization');
$this->assign('description', "Ranking the world's top companies by market cap, market value, revenue and many more metrics");
$price_history = json_decode($data->price_history, true);
$stock_peers = json_decode($data->stock_peers, true);
$company_outlook = json_decode($data->company_outlook, true);
$market_cap_list = json_decode($data->market_cap_list, true);

?>
<style>
    .profile-container.margin-lr-15px.pt-3 {
        margin-top: 30px;
    }

    .tab-medium-hide {
        display: none;
    }

    .tab-medium-show {
        display: block;
    }

    @media (min-width: 992px) {
        .tab-medium-hide {
            display: block;
        }

        .tab-medium-show {
            display: none;
        }
    }
</style>
<div class="table-container">
    <div class="row">
        <div class="col-lg-2">
            <div class="company-logo-container">
                <img loading="lazy" class="company-profile-logo" title="Logo" alt="Logo" src="<?= $data->logo; ?>" />
            </div>
            <div class="company-title-container">
                <div class="company-name"><?= $data->name; ?></div>
                <div class="company-code"><?= strtoupper($data->symbol); ?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="info-box">
                    <div class="line1">$<?= nice_number($data->market_cap); ?></div>
                    <div class="line2">Marketcap</div>
                </div>
                <div class="info-box">
                    <div class="line1">$<?= num_2($data->stock_price); ?></div>
                    <div class="line2">Share price</div>
                </div>
                <div class="info-box">
                    <div class="line1"><a href="javascript:void(0);"><img class="flag" src="<?= SITEURL; ?>img/flags/<?= $data->country; ?>.png"><span class="responsive-hidden"> <?= $data->country; ?></a></span></div>
                    <div class="line2">Country</div>
                </div>
            </div>
            <div class="row">

                <div class="info-box">
                    <div class="line1"><span class="percentage-green"><?= (isset($company_outlook['profile']['changes']) ? "$".num_2($company_outlook['profile']['changes']*$data->usd_rate) : 'N/A') ?></span></div>
                    <div class="line2">Change (1 day)</div>
                </div>
                <div class="info-box">
                    <div class="line1"><span class="percentage-green"><?= (isset($company_outlook['metrics']['yearHigh']) ? "$".num_2($company_outlook['metrics']['yearHigh']*$data->usd_rate) : 'N/A') ?></span></div>
                    <div class="line2">Year High</div>
                </div>

                <div class="info-box">
                    <div class="line1"><span class="percentage-green"><?= (isset($company_outlook['metrics']['yearLow']) ? "$".num_2($company_outlook['metrics']['yearLow']*$data->usd_rate ): 'N/A') ?></span></div>
                    <div class="line2">Year Low</div>
                </div>
            </div>
            <div class="info-box categories-box">
                <div class="line1">
                    <a href="javascript:void(0);" class="badge badge-light category-badge"><?= $data->industry; ?></a>

                </div>
                <div class="line2">Categories</div>
            </div>
        </div>
        <div class="col-lg-4 company-description">
            <p><?= $data->description; ?></p>
        </div>
    </div>

    <ul class="nav nav-tabs mt-4 margin-lr-15px">
        <li class="nav-item"> <a class="nav-link <?= ((empty($type) || $type == 'marketcap') ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/marketcap">Market cap</a> </li>
        <li class="nav-item"> <a class="nav-link <?= ($type == 'revenue' ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/revenue">Revenue</a> </li>
        <li class="nav-item"> <a class="nav-link <?= ($type == 'earnings' ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/earnings">Earnings</a> </li>
        <li class="nav-item"> <a class="nav-link tab-medium-hide <?= ($type == 'stock-price-history' ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/stock-price-history">Price history</a> </li>
        <li class="nav-item"> <a class="nav-link tab-medium-hide <?= ($type == 'pe-ratio' ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/pe-ratio">P/E ratio</a> </li>
        <li class="nav-item"> <a class="nav-link tab-medium-hide <?= ($type == 'ps-ratio' ? 'active' : null); ?>" href="<?= SITEURL . $data->slug; ?>/ps-ratio">P/S ratio</a> </li>
        <li class="nav-item dropdown position-relative">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">More</a>
            <div class="dropdown-menu">
                <a class="dropdown-item tab-medium-show" href="<?= SITEURL . $data->slug; ?>/pe-ratio">P/E ratio</a>
                <a class="dropdown-item tab-medium-show" href="<?= SITEURL . $data->slug; ?>/ps-ratio">P/S ratio</a>
                <a class="dropdown-item tab-medium-show" href="<?= SITEURL . $data->slug; ?>/stock-price-history">Price history</a>
                <a class="dropdown-item" href="<?= SITEURL . $data->slug; ?>/pb-ratio">P/B ratio</a>
                <a class="dropdown-item" href="<?= SITEURL . $data->slug; ?>/dividends">Dividends</a>
                <a class="dropdown-item" href="<?= SITEURL . $data->slug; ?>/stock-splits">Stock Splits</a>
                <a class="dropdown-item" href="<?= SITEURL . $data->slug; ?>/outlook">Company Outlook</a>
            </div>
        </li>
    </ul>
    <?php
    if (empty($type) || $type == 'marketcap') {
        echo $this->element('profile/marketcap', ['data' => $data, 'market_cap' => $market_cap]);
    } elseif ($type == 'revenue') {
        echo $this->element('profile/revenue', ['data' => $data, 'revenue' => $revenue]);
    } elseif ($type == 'earnings') {
        echo $this->element('profile/earnings', ['data' => $data, 'revenue' => $earnings]);
    } elseif ($type == 'stock-price-history') {
        echo $this->element('profile/stock-price-history', ['data' => $data, 'price' => $price]);
    } elseif ($type == 'pe-ratio') {
        echo $this->element('profile/pe-ratio', ['data' => $data, 'pe' => $pe]);
    } elseif ($type == 'ps-ratio') {
        echo $this->element('profile/ps-ratio', ['data' => $data, 'ps' => $ps]);
    } elseif ($type == 'pb-ratio') {
        echo $this->element('profile/pb-ratio', ['data' => $data, 'pb' => $pb]);
    } elseif ($type == 'dividends') {
        echo $this->element('profile/dividends', ['data' => $data, 'dividends' => $dividends]);
    } elseif ($type == 'stock-splits') {
        echo $this->element('profile/stock-splits', ['data' => $data, 'splits' => $splits]);
    } elseif ($type == 'outlook') {
        echo $this->element('profile/outlook', ['data' => $data, 'outlook' => $outlook]);
    }




    ?>
    <div>

        <?php if ( isset($peers) && !$peers->isEmpty() && (empty($type) || $type == 'marketcap')) { ?>

            <div style="clear:both;">
                <h3>Market capitalization for similar companies or competitors</h3>
                <div style="overflow-y: scroll;">
                    <table class="table w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Market cap</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($peers as $plist) { ?>
                                <tr>
                                    <td class="name-td"><a href="<?= SITEURL . $plist->slug; ?>">
                                            <div class="float-left pt-1"><img loading="lazy" class="company-logo" alt=" Logo" src="<?= $plist->logo; ?>"></div>
                                            <div class="name-div">
                                                <div class="company-name"><?= $plist->name; ?></div>
                                                <div class="company-code"><span class="rank d-none"></span>MSFT</div>
                                        </a>

                                    </td>
                                    <td>$<?= nice_number($plist->market_cap); ?></td>

                                    <td><img class="flag" src="<?= SITEURL; ?>img/flags/<?= $plist->country; ?>.png"> <span class="responsive-hidden"><?= $plist->country; ?></span></td>


                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

    </div>