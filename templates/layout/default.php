<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="<?= SITEURL; ?>favicon.ico" type="image/x-icon">
    <meta name="robots" content="INDEX,FOLLOW" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="content-language" content="en-us">

    <title><?= $this->fetch('title') ?></title>
    <meta name="description" content="<?= $this->fetch('description') ?>" />

    <?= $this->Html->css(['bt-replacement', 'style', 'magnific-popup']) ?>
    <?= $this->fetch('meta'); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>
    <?php
    $theme = $this->request->getSession()->read('theme');
    $cap = $this->Data->getCaps();
    $auth = $this->request->getSession()->read('Auth.User');

    ?>

</head>

<body class="cmkt <?= ($theme == 'dark' ? 'dark' : null); ?>" id="cmkt">

    <div class="row site-header-row">
        <div class="site-header">

            companies: <span class="font-weight-bold"><?= number_format($cap['companies']); ?></span> &nbsp;&nbsp;&nbsp;
            <span class="responsive-hidden"> total market cap: <span class="font-weight-bold">$<?= $cap['market_cap']; ?></span>
            </span>

            <div class="header-actions responsive-hidden1">
                <?php
                if (isset($auth->email) && !empty($auth->email)) {
                    echo $this->html->link('Dashboard', '/dashboard', ['class' => 'login_btn']);
                } else {
                    echo $this->html->link('Login', 'javascript:void(0);', ['class' => 'login_btn', 'onclick' => 'doLogin()']);
                }

                ?>

                <a href="javascript:void(0);" onclick="web_bg(2)" class="dark-hidden" id="light-off-btn" title="change to dark mode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 70 1000 1000" width="16" height="16">
                        <path d="M525.3,989.5C241.2,989.5,10,758.3,10,474.1c0-196.8,109.6-373.6,285.9-461.4c7.9-3.9,17.5-2.4,23.7,3.8c6.2,6.2,7.9,15.8,4,23.7c-32.2,65.4-48.5,135.7-48.5,208.9c0,261.4,212.7,474.1,474.1,474.1c74,0,145-16.7,211-49.5c7.9-3.9,17.5-2.4,23.7,3.8c6.3,6.3,7.9,15.8,3.9,23.7C900.5,879,723.3,989.5,525.3,989.5z">
                        </path>
                    </svg>
                </a>
                <a href="javascript:void(0);" onclick="web_bg(1)" class="dark-shown" id="light-on-btn" title="change to light mode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="10 10 220 220" width="16" height="16">
                        <path d="M120,189.697c-38.492,0-69.696-31.205-69.696-69.697S81.508,50.303,120,50.303c38.494,0,69.699,31.205,69.699,69.697 S158.494,189.697,120,189.697z M203.975,140L240,120l-36.025-20V140z M36.025,100L0,120l36.025,20V100z M120,240l20-36.025h-40 L120,240z M120,0l-20,36.025h40L120,0z M46.481,165.238l-11.333,39.615l39.616-11.33L46.481,165.238z M165.238,46.477l28.283,28.285 l11.332-39.613L165.238,46.477z M35.148,35.148L46.48,74.762l28.283-28.285L35.148,35.148z M165.238,193.523l39.615,11.33 l-11.332-39.615L165.238,193.523z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <nav class="navbar nav-bar-companiesmarketcap navbar-expand-lg navbar-light">
        <div class="navbar-collapse-container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="<?= SITEURL; ?>" title="Marketcap.tools - companies ranked by market capitalization" class="responsive-hidden1">
                    <?php /* ?><div class="companiesmarketcap-logo"></div><?php */ ?>
                    <img src="<?= SITEURL; ?>img/logo.svg" style="width: auto;height: 25px;margin: 8px 0;">
                </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav">

                    <li class="nav-item dropdown megamenu-li">
                        <a class="nav-link nav-link-companiesmarketcap dropdown-toggle" href="#" id="dropdown-countries" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ranking by countries</a>
                        <div class="dropdown-menu megamenu" aria-labelledby="dropdown-countries">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <h5>America</h5>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/us/largest-companies-in-the-usa-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/us.png"> United States</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/ca/largest-companies-in-canada-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/ca.png"> Canada</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/mx/largest-companies-in-mexico-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/mx.png"> Mexico</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/br/largest-companies-in-brazil-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/br.png"> Brazil</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/cl/largest-companies-in-chile-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/cl.png"> Chile</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <h5>Europe</h5>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/de/largest-companies-in-germany-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/de.png"> Germany</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/uk/largest-companies-in-the-uk-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/uk.png"> United Kingdom</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/fr/largest-companies-in-france-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/fr.png"> France</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/es/largest-companies-in-spain-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/es.png"> Spain</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/nl/largest-companies-in-the-netherlands-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/nl.png"> Netherlands</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/se/largest-companies-in-sweden-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/se.png"> Sweden</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/it/largest-companies-in-italy-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/it.png"> Italy</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/ch/largest-companies-in-switzerland-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/ch.png"> Switzerland</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/pl/largest-companies-in-poland-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/pl.png"> Poland</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/fi/largest-companies-in-finland-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/fi.png"> Finland</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <h5>Asia</h5>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/fn/largest-companies-in-china-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/cn.png"> China</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/jp/largest-companies-in-japan-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/jp.png"> Japan</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/kr/largest-companies-in-south-korea-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/kr.png"> South Korea</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/hk/largest-companies-in-hong-kong-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/hk.png"> Hong Kong</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/sg/largest-companies-in-singapore-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/sg.png"> Singapore</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/id/largest-companies-in-indonesia-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/id.png"> Indonesia</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/in/largest-companies-in-india-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/in.png"> India</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/my/largest-companies-in-malaysia-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/my.png"> Malaysia</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/tw/largest-companies-in-taiwan-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/tw.png"> Taiwan</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/th/largest-companies-in-thailand-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/th.png"> Thailand</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <h5>Others</h5>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/au/largest-companies-in-australia-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/au.png"> Australia</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/nz/largest-companies-in-new-zealand-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/nz.png"> New Zealand</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/il/largest-companies-in-israel-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/il.png"> Israel</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/sa/largest-companies-in-saudi-arabia-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/sa.png"> Saudi Arabia</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/tr/largest-companies-in-turkey-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/tr.png"> Turkey</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/ru/largest-companies-in-russia-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/ru.png"> Russia</a>
                                    <a class="dropdown-item" href="<?= SITEURL;?>country/za/largest-companies-in-south-africa-by-market-cap"><img class="flag" src="<?= SITEURL;?>img/flags/za.png"> South Africa</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown megamenu-li">
                        <a class="nav-link nav-link-companiesmarketcap dropdown-toggle" href="index#" id="dropdown-categories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ranking by categories</a>
                        <div class="dropdown-menu megamenu" aria-labelledby="dropdown-categories">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <a class="dropdown-item" href="assets-by-market-cap">🏆 All assets by Market
                                        Cap</a>
                                    <a class="dropdown-item" href="automakers/largest-automakers-by-market-cap">🚗
                                        Automakers</a>
                                    <a class="dropdown-item" href="airlines/largest-airlines-by-market-cap">✈️
                                        Airlines</a>
                                    <a class="dropdown-item" href="airports/largest-airport-operating-companies-by-market-cap">🛫
                                        Airports</a>
                                    <a class="dropdown-item" href="aircraft-manufacturers/largest-aircraft-manufacturers-by-market-cap">✈️
                                        Aircraft manufacturers</a>
                                    <a class="dropdown-item" href="banks/largest-banks-by-market-cap">🏦 Banks</a>
                                    <a class="dropdown-item" href="hotels/largest-hotel-companies-by-market-cap">🏨
                                        Hotels</a>
                                    <a class="dropdown-item" href="pharmaceuticals/largest-pharmaceutical-companies-by-market-cap">💊
                                        Pharmaceuticals</a>
                                    <a class="dropdown-item" href="e-commerce/largest-e-commerce-companies-by-market-cap">🛒
                                        E-Commerce</a>
                                    <a class="dropdown-item" href="healthcare/largest-healthcare-companies-by-market-cap">⚕️
                                        Healthcare</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <a class="dropdown-item" href="delivery-services/largest-delivery-companies-by-market-cap">📦
                                        Courier services</a>
                                    <a class="dropdown-item" href="media-press/largest-media-and-press-companies-by-market-cap">📰
                                        Media/Press</a>
                                    <a class="dropdown-item" href="alcoholic-beverages/largest-alcoholic-beverage-companies-by-market-cap">🍷
                                        Alcoholic beverages</a>
                                    <a class="dropdown-item" href="beverages/largest-beverage-companies-by-market-cap">🥤 Beverages</a>
                                    <a class="dropdown-item" href="clothing/largest-clothing-companies-by-market-cap">👚 Clothing</a>
                                    <a class="dropdown-item" href="mining/largest-mining-companies-by-market-cap">⛏️ Mining</a>
                                    <a class="dropdown-item" href="railways/largest-railways-companies-by-market-cap">🚂 Railways</a>
                                    <a class="dropdown-item" href="insurance/largest-insurance-companies-by-market-cap">🏦 Insurance</a>
                                    <a class="dropdown-item" href="real-estate/largest-real-estate-companies-by-market-cap">🏠 Real
                                        estate</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <a class="dropdown-item" href="ports/largest-port-operating-companies-by-market-cap">⚓ Ports</a>
                                    <a class="dropdown-item" href="professional-services/largest-professional-service-companies-by-market-cap">💼
                                        Professional services</a>
                                    <a class="dropdown-item" href="food/largest-food-companies-by-market-cap">🍴
                                        Food</a>
                                    <a class="dropdown-item" href="restaurant-chains/largest-restaurant-chain-companies-by-market-cap">🍔
                                        Restaurant chains</a>
                                    <a class="dropdown-item" href="software/largest-software-companies-by-market-cap">‍💻 Software</a>
                                    <a class="dropdown-item" href="semiconductors/largest-semiconductor-companies-by-market-cap">📟
                                        Semiconductors</a>
                                    <a class="dropdown-item" href="tobacco/largest-tobacco-companies-by-market-cap"> 🚬 Tobacco</a>
                                    <a class="dropdown-item" href="financial-services/largest-financial-service-companies-by-market-cap">💳
                                        Financial services</a>
                                    <a class="dropdown-item" href="oil-gas/largest-oil-and-gas-companies-by-market-cap">🛢 Oil&Gas</a>
                                    <a class="dropdown-item" href="electricity/largest-electricity-companies-by-market-cap">🔋
                                        Electricity</a>
                                </div>
                                <div class="col-sm-6 col-lg-3 border-left">
                                    <a class="dropdown-item" href="chemicals/largest-chemical-companies-by-market-cap">🧪 Chemicals</a>
                                    <a class="dropdown-item" href="investment/largest-investment-companies-by-market-cap">💰
                                        Investment</a>
                                    <a class="dropdown-item" href="telecommunication/largest-telecommunication-companies-by-market-cap">📡
                                        Telecommunication</a>
                                    <a class="dropdown-item" href="retail/largest-retail-companies-by-market-cap">🛍️ Retail</a>
                                    <a class="dropdown-item" href="internet/largest-internet-companies-by-market-cap">🖥️ Internet</a>
                                    <a class="dropdown-item" href="construction/largest-construction-companies-by-market-cap">🏗
                                        Construction</a>
                                    <a class="dropdown-item" href="video-games/largest-video-game-companies-by-market-cap">🎮 Video
                                        Game</a>
                                    <a class="dropdown-item" href="tech/largest-tech-companies-by-market-cap">💻
                                        Tech</a>
                                    <a class="dropdown-item" href="artificial-intelligence/largest-ai-companies-by-marketcap">🦾 AI</a>
                                    <a class="dropdown-item" href="all-categories"><strong>&#x3E;&#x3E; All
                                            Categories</strong></a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content_div">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>


    <div class="footer-container">
        <div class="footer-section">
            <div class="footer row">
                <div class="col-sm-6">
                    <br>
                    <h4><a href="lexicon/market-cap-of-a-company">What is the market capitalization of a
                            company?</a></h4>
                    The market capitalization sometimes referred as Marketcap, is the value of a publicly listed
                    company.
                    In most cases it can be easily calculated by multiplying the share price with the amount of
                    outstanding shares.<br /><br />
                    <h4>DISCLAIMER</h4>
                    Marketcap.tools is not associated in any way with CoinMarketCap.com<br />
                    Stock prices are delayed, the delay can range from a few minutes to several hours.<br />
                    Company logos are from the <a class="text-underline" href="https://companieslogo.com/">CompaniesLogo.com logo database</a> and belong to their
                    respective copyright holders. Companies Marketcap displays them for editorial purposes only.
                </div>
                <div class="col-sm-6">
                    <h4>Contact</h4>
                    For inquiries or if you want to report a problem write to <span class="contact-email">in<span class="hidden">nospam</span>fo@market<span class="hidden">(nospam)</span>cap.tools</span>
                    <br /> <br />
                    <p class="social-media-icons"><a href="https://www.facebook.com/CompaniesMarketCap/" title="Companies Market Cap Facebook page">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 155 155">
                                <path d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184 c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452 v20.341H37.29v27.585h23.814v70.761H89.584z">
                                </path>
                            </svg></a>
                        <a href="https://twitter.com/CompaniesMarke1" title="Companies Market Cap Twitter account">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="80 75 250 250">
                                <rect fill="none" width="30" height="30"></rect>
                                <path d="M153.62,301.59c94.34,0,145.94-78.16,145.94-145.94,0-2.22,0-4.43-.15-6.63A104.36,104.36,0,0,0,325,122.47a102.38,102.38,0,0,1-29.46,8.07,51.47,51.47,0,0,0,22.55-28.37,102.79,102.79,0,0,1-32.57,12.45,51.34,51.34,0,0,0-87.41,46.78A145.62,145.62,0,0,1,92.4,107.81a51.33,51.33,0,0,0,15.88,68.47A50.91,50.91,0,0,1,85,169.86c0,.21,0,.43,0,.65a51.31,51.31,0,0,0,41.15,50.28,51.21,51.21,0,0,1-23.16.88,51.35,51.35,0,0,0,47.92,35.62,102.92,102.92,0,0,1-63.7,22A104.41,104.41,0,0,1,75,278.55a145.21,145.21,0,0,0,78.62,23">
                                </path>
                            </svg></a>
                    </p>
                    <p>
                        &#169; 2023 Marketcap.tools
                    </p>
                </div>
            </div>
        </div>
    </div>



    <?php echo $this->Html->script(['jquery-3.6.3.min', 'jquery.form.min', 'script', 'jquery.magnific-popup.min']); ?>

    <script>
        $(".is_fev").click(function() {
            var id = $(this).attr('data-id');
            if ($(this).hasClass("add_star") === true) {
                // $(this).removeClass("add_star").addClass("rm_star");
                // $(this).attr("src", "<?= SITEURL; ?>img/star_dark.svg");

                $.ajax({
                    type: 'POST',
                    url: '<?php echo SITEURL; ?>homes/star/add/' + id,
                    success: function(data) {
                        $("#cover")(data);
                    },
                    error: function(comment) {
                        $("#cover")(comment);
                    }
                });


            } else {
                // $(this).removeClass("rm_star").addClass("add_star");
                // $(this).attr("src", "<?= SITEURL; ?>img/star.svg");


                $.ajax({
                    type: 'POST',
                    url: '<?php echo SITEURL; ?>homes/star/rm/' + id,
                    success: function(data) {
                        $("#cover")(data);
                    },
                    error: function(comment) {
                        $("#cover")(comment);
                    }
                });
            }


        });

        function web_bg(t) {
            if (t == 1) {
                $("#cmkt").removeClass('dark').addClass('dark');
            } else if (t == 2) {
                $("#cmkt").removeClass('dark');
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo SITEURL; ?>homes/theme/' + t,
                success: function(data) {
                    $("#cover")(data);
                },
                error: function(comment) {
                    $("#cover")(comment);
                }
            });
        }

        function doLogin() {
            var d = "<?php echo urlencode(SITEURL . "users/login_popup/"); ?>";
            $.ajax({
                type: 'POST',
                url: '<?php echo SITEURL; ?>homes/open_pop/2',
                data: {
                    url: d
                },
                success: function(data) {
                    $("#cover")(data);
                },
                error: function(comment) {
                    $("#cover")(comment);
                }
            });
        }
    </script>


    <?= $this->fetch('scriptBottom'); ?>
    <div id="cover"></div>
</body>