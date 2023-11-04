<?php
$this->assign('title', 'Companies ranked by Market Cap');
$this->assign('description', "Ranking the world's top companies by market cap, market value, revenue and many more metrics");
$cap = $this->Data->getCaps();
$theme = $this->request->getSession()->read('theme');

?>

<style>
   
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
                <th tid="1" class="th-id-1 th-name sorting"></th>
                <th tid="2" class="th-id-2 th-rank sorting">Rank</th>
                <th tid="3" class="th-id-3 th-name sorting">Name</th>
                <th tid="4" class="th-id-4 th-name sorting">Symbol</th>
                <th tid="5" class="th-id-5 th-mcap sorting text-right">Market Cap</th>
                <th tid="6" class="th-id-6 th-price sorting text-right">Price</th>
                <th tid="7" class="th-id-7 th-country sorting">Country</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$data->isEmpty()) {
                $num = $this->Paginator->counter('{{start}}');
                foreach ($data as $list) {

                    $logo = $logo_dark = $logo_nrm =  $list->logo;
                    if (!empty($list->logo_bright)) {
                        $logo_dark = $logo_nrm = SITEURL . "logo/" . $list->logo_bright;
                    }
                    if (!empty($list->logo_dark)) {
                        $logo_dark = SITEURL . "logo/" . $list->logo_dark;
                    }
                    $logo = ($theme == 'dark' ? $logo_dark : $logo_nrm);

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
                        <td class="td-left"> <span class="badge badge-company"><?= strtoupper($list->symbol); ?></span></td>
                        <td class="td-left" data-sort="<?= $list->market_cap; ?>">$<?= nice_number($list->market_cap); ?></td>
                        <td class="td-left" data-sort="<?= $list->stock_price; ?>">$<?= num_2($list->stock_price); ?></td>

                        <td class="td-left">
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

<?php
echo $this->Html->script(['tableScript'], ['block' => 'scriptBottom']);
$this->append('scriptBottom');  ?>
<script>
    const table = document.querySelector('.marketcap-table');
    const thRank = table.querySelector('.th-rank');
    const thPrice = table.querySelector('.th-price');
    const thMcap = table.querySelector('.th-mcap');
    const thMcapLoss = table.querySelector('.th-mcap-loss');
    const thMcapGain = table.querySelector('.th-mcap-gain');
    const th1d = table.querySelector('.th-1d');
    const thCountry = table.querySelector('.th-country');
    var rankIndex = Array.prototype.slice.call(thRank.parentNode.children).indexOf(thRank);
    var priceIndex = Array.prototype.slice.call(thRank.parentNode.children).indexOf(thPrice);
    var th1dIndex = Array.prototype.slice.call(thRank.parentNode.children).indexOf(th1d);
    var addedMovers = false;

    function makeMobile() {
        //unmake mobile
        if (window.innerWidth > 996) {
            // add movers
            if (!addedMovers) {
                table.querySelectorAll('tr td:nth-child(' + (rankIndex + 1) + ')').forEach(function(td) {
                    var moves = td.getAttribute('moves')
                    if (moves != null) {
                        if (moves > 0) {
                            td.innerHTML = '<span class="mover up responsive-hidden"><i class="arrow"></i> ' + Math.abs(moves) + '</span>' + td.innerHTML
                        } else if (moves < 0) {
                            td.innerHTML = '<span class="mover down responsive-hidden"><i class="arrow"></i> ' + Math.abs(moves) + '</span>' + td.innerHTML
                        }
                    }
                });
                addedMovers = true;
            }
            thRank.innerHTML = 'Rank';
            if (thMcap != null) {
                thMcap.innerHTML = 'Market Cap';
            }
            if (thMcapGain != null) {
                thMcapGain.innerHTML = 'Market Cap gain';
            }
            if (thMcapLoss != null) {
                thMcapLoss.innerHTML = 'Market Cap loss';
            }
            
            thCountry.innerHTML = 'Country';
            table.querySelectorAll('tr').forEach(function(tr) {
                tr.querySelector('th:nth-child(' + (rankIndex + 1) + '), td:nth-child(' + (rankIndex + 1) + ')').classList.remove("d-none")
                const spanRank = tr.querySelector('td span.rank');
                if (spanRank != null) {
                    spanRank.classList.add('d-none');
                    spanRank.innerHtml = '';
                }
            });
        }
        //make mobile
        if (window.innerWidth <= 996) {
            thRank.innerHTML = '#';
            if (thMcap != null) {
                thMcap.innerHTML = 'M. Cap';
            }
            if (thMcapGain != null) {
                thMcapGain.innerHTML = 'M. Cap gain';
            }
            if (thMcapLoss != null) {
                thMcapLoss.innerHTML = 'M. Cap loss';
            }
            th1d.innerHTML = '1d';
            thCountry.innerHTML = 'C.';
            table.querySelectorAll('tr').forEach(function(tr) {
                tr.querySelector('th:nth-child(' + (rankIndex + 1) + '), td:nth-child(' + (rankIndex + 1) + ')').classList.add("d-none")
                const spanRank = tr.querySelector('td span.rank');
                if (spanRank != null) {
                    spanRank.classList.remove('d-none');
                    spanRank.innerHTML = tr.querySelector('td:nth-child(' + (rankIndex + 1) + ')').getAttribute('data-sort');
                }
                const priceTd = tr.querySelector('td:nth-child(' + (priceIndex + 1) + ')');
                if (priceTd != null) {
                    if (window.innerWidth <= 650) {
                        if (!priceTd.classList.contains('pt-2')) {
                            priceTd.classList.add('pt-2');
                            if (priceTd != null) {
                                priceTd.innerHTML = '<div class="price">' + priceTd.innerHTML + '</div><div>' + tr.querySelector('td:nth-child(' + (th1dIndex + 1) + ')').innerHTML + '</div>';
                            }
                        }
                    } else {
                        if (priceTd.classList.contains('pt-2')) {
                            priceTd.classList.remove('pt-2');
                            if (priceTd != null) {
                                priceTd.innerHTML = priceTd.querySelector('.price').innerHTML;
                            }
                        }
                    }
                }
            });
        }
    }
    makeMobile();
    window.addEventListener('resize', makeMobile);


var searchInput = document.getElementById("search-input"); var xhr = new XMLHttpRequest()
searchInput.addEventListener("keyup", function (e) {
        xhr.abort(); if (searchInput.value.length == 0) { document.getElementById("typeahead-search-results").style.display = "none"; } else {
                xhr.open('GET', 'homes/search?action=search&query=' + searchInput.value); xhr.responseType = 'json'; xhr.send(); xhr.onload = function () {
                        let searchResponse = xhr.response; document.getElementById("typeahead-search-results").style.display = "block"; var aheadHtml = ''; var darkPathSupplement; var isDarkMode = document.body.classList.contains('dark'); for (var i in searchResponse) {
                                if (isDarkMode && searchResponse[i]["img_dark_png"] == "1") { darkPathSupplement = '.D'; } else { darkPathSupplement = ''; }
                                aheadHtml = aheadHtml.concat('<a href="' + searchResponse[i]["url"] + '">' +
                                        '    <div class="float-left pt-1 clear-both"><img class="company-logo" src="' + searchResponse[i]["logo"] + '"></div>' +
                                        '    <div class="pl-5">' +
                                        '        <div class="company-name">' + searchResponse[i]["name"] + '</div>' +
                                        '        <div class="company-code">' + searchResponse[i]["symbol"] + '</div>' +
                                        '    </div>' +
                                        '</a>');
                        }
                        document.getElementById("typeahead-search-results").innerHTML = aheadHtml;
                };
        }
}); searchInput.onfocus = function () { if (searchInput.value.length > 0) { document.getElementById("typeahead-search-results").style.display = "block"; } }; var dropdowns = document.querySelectorAll(".dropdown-toggle"); var currentlyOpenedDropdown; for (var i = 0; i < dropdowns.length; i++) {
        console.log(dropdowns); dropdowns[i].addEventListener("click", function (evt) {
                evt.preventDefault(); var newCurrentlyOpenedDropdown = evt.target.parentNode; var wasOpen = false; if (newCurrentlyOpenedDropdown.querySelector(".dropdown-menu").classList.contains("show")) { wasOpen = true; }
                if (typeof currentlyOpenedDropdown != 'undefined' && currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.contains("show")) { currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.remove("show"); }
                currentlyOpenedDropdown = newCurrentlyOpenedDropdown; if (!wasOpen) { currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.add("show"); }
        }); window.addEventListener('click', function (e) {
                if (typeof currentlyOpenedDropdown != 'undefined' && !currentlyOpenedDropdown.contains(e.target)) { currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.remove("show"); }
                if (!document.querySelector('.search-form').contains(e.target)) { document.querySelector("#typeahead-search-results").style.display = 'none'; }
        });
}
</script>

<?php $this->end();  ?>