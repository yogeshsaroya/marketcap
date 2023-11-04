<?php
$this->assign('title', 'Portfolio Summary');

$cap = $this->Data->getCaps();
$theme = $this->request->getSession()->read('theme');

?>
<br>
<h1 class="text-center h1-title">Portfolio Summary</h1>
<br>

<div class="ranking-bar">
  
  <a href="<?= SITEURL?>dashboard"><span class="option active">Portfolio</span></a> 
  <a href="<?= SITEURL?>watchlist"><span class="option ">Watchlist</span></a> 
  <a href="<?= SITEURL?>users/profile"><span class="option ">Profile</span></a> 
  <a href="<?= SITEURL?>users/logout"><span class="option ">Logout</span></a> 
  
</div>

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
            <td class="td-left"><?= strtoupper($list->symbol); ?></td>
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


  var searchInput = document.getElementById("search-input");
  var xhr = new XMLHttpRequest()
  searchInput.addEventListener("keyup", function(e) {
    xhr.abort();
    if (searchInput.value.length == 0) {
      document.getElementById("typeahead-search-results").style.display = "none";
    } else {
      xhr.open('GET', 'homes/search?action=search&query=' + searchInput.value);
      xhr.responseType = 'json';
      xhr.send();
      xhr.onload = function() {
        let searchResponse = xhr.response;
        document.getElementById("typeahead-search-results").style.display = "block";
        var aheadHtml = '';
        var darkPathSupplement;
        var isDarkMode = document.body.classList.contains('dark');
        for (var i in searchResponse) {
          if (isDarkMode && searchResponse[i]["img_dark_png"] == "1") {
            darkPathSupplement = '.D';
          } else {
            darkPathSupplement = '';
          }
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
  });
  searchInput.onfocus = function() {
    if (searchInput.value.length > 0) {
      document.getElementById("typeahead-search-results").style.display = "block";
    }
  };
  var dropdowns = document.querySelectorAll(".dropdown-toggle");
  var currentlyOpenedDropdown;
  for (var i = 0; i < dropdowns.length; i++) {
    console.log(dropdowns);
    dropdowns[i].addEventListener("click", function(evt) {
      evt.preventDefault();
      var newCurrentlyOpenedDropdown = evt.target.parentNode;
      var wasOpen = false;
      if (newCurrentlyOpenedDropdown.querySelector(".dropdown-menu").classList.contains("show")) {
        wasOpen = true;
      }
      if (typeof currentlyOpenedDropdown != 'undefined' && currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.contains("show")) {
        currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.remove("show");
      }
      currentlyOpenedDropdown = newCurrentlyOpenedDropdown;
      if (!wasOpen) {
        currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.add("show");
      }
    });
    window.addEventListener('click', function(e) {
      if (typeof currentlyOpenedDropdown != 'undefined' && !currentlyOpenedDropdown.contains(e.target)) {
        currentlyOpenedDropdown.querySelector(".dropdown-menu").classList.remove("show");
      }
      if (!document.querySelector('.search-form').contains(e.target)) {
        document.querySelector("#typeahead-search-results").style.display = 'none';
      }
    });
  }
</script>

<?php $this->end();  ?>