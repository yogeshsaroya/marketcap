function addListener(selector, action, fAction) { var selection = document.querySelectorAll(selector); for (i = 0; i < selection.length; i++) { selection[i].addEventListener(action, fAction); } }
function lightOn() {
        document.body.classList.remove('dark'); document.cookie = 'darkmode=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; var companyLogos = document.querySelectorAll(".company-logo, .company-profile-logo"); for (i = 0; i < companyLogos.length; i++) { if (companyLogos[i].hasAttribute("data-img-path")) { companyLogos[i].src = companyLogos[i].getAttribute("data-img-path"); } }
        return false;
}
function lightOff() {
        document.body.classList.add('dark'); document.cookie = 'darkmode=1;  max-age=91536000000; path=/;'; var companyLogos = document.querySelectorAll(".company-logo, .company-profile-logo"); for (i = 0; i < companyLogos.length; i++) { if (companyLogos[i].hasAttribute("data-img-dark-path")) { companyLogos[i].src = companyLogos[i].getAttribute("data-img-dark-path"); } }
        return false;
}
addListener("#light-on-btn", "click", function (e) { lightOn(); }); addListener("#light-off-btn", "click", function (e) { lightOff(); }); var searchInput = document.getElementById("search-input"); var xhr = new XMLHttpRequest()
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
navCollapseVisible = false; document.querySelector(".navbar .navbar-toggler").addEventListener("click", function (evt) { if (!navCollapseVisible) { document.querySelector(".navbar .navbar-collapse").classList.add("show"); navCollapseVisible = true; } else { document.querySelector(".navbar .navbar-collapse").classList.remove("show"); navCollapseVisible = false; } }); var getCellValue = function (tr, idx) { return tr.children[idx].getAttribute('data-sort') || tr.children[idx].innerText || tr.children[idx].textContent; }
var comparer = function (idx, asc) { return function (a, b) { return function (v1, v2) { return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2); }(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx)); } }; function resetSortings() { document.querySelectorAll('.marketcap-table th.sorting').forEach(sortingTH => { sortingTH.classList.remove("sorting_asc"); sortingTH.classList.remove("sorting_desc"); }); }
function updateSortingOfTH(th, sortClass) { if (th.hasAttribute("tid")) { document.querySelectorAll(".th-id-" + th.getAttribute("tid")).forEach(nTH => { nTH.classList.add(sortClass); }); } else { th.classList.add(sortClass); } }
function loadTableSortEvents() {
        Array.prototype.slice.call(document.querySelectorAll('.marketcap-table th, .thead-copy th')).forEach(function (th) {
                if (th.classList.contains('sorting')) {
                        th.addEventListener('click', function () {
                                if (th.classList.contains('sorting_asc')) { resetSortings(); updateSortingOfTH(th, "sorting_desc"); this.asc = true; } else if (th.classList.contains('sorting_desc')) { resetSortings(); updateSortingOfTH(th, "sorting_asc"); this.asc = false; } else { resetSortings(); updateSortingOfTH(th, "sorting_asc"); this.asc = false; }
                                const tbody = table.querySelector('tbody'); var thIndex = Array.prototype.slice.call(th.parentNode.children).indexOf(th); Array.prototype.slice.call(tbody.querySelectorAll('tr')).sort(comparer(thIndex, this.asc = !this.asc)).forEach(function (tr) { if (!tr.classList.contains('no-sort')) { tbody.appendChild(tr); } else { tbody.removeChild(tr); } });
                        })
                }
        });
}
document.querySelectorAll('.tooltip-title').forEach(function (tElement) {
        tElement.addEventListener("mouseenter", function (event) {
                console.log(typeof event.target.classList); var initiatorRect = event.target.getBoundingClientRect(); if (typeof event.target.classList != 'undefined') {
                        var tooltip = document.getElementById('tooltip'); if (tooltip == null) { var tooltip = document.createElement('div'); tooltip.setAttribute('id', 'tooltip'); tooltip.setAttribute('class', 'tooltip-style'); document.body.appendChild(tooltip); }
                        tooltip.classList.remove('hidden');
                }
                tooltip.innerHTML = event.target.getAttribute('tooltip-title'); console.log(event.target.classList); var tooltipRect = tooltip.getBoundingClientRect(); tooltip.style.left = (initiatorRect.x + initiatorRect.width / 2 - tooltipRect.width / 2) + 'px'; tooltip.style.top = (initiatorRect.y + initiatorRect.height + 5) + 'px';
        }); tElement.addEventListener("mouseleave", function (event) { tooltip.classList.add('hidden'); });
}); function insertSuggestionPopup() {
        var popupHtml = `<div class="popup report-popup">
                <div style="position:absolute; right: 20px;">
                        <a href="#" class="close-popup"><span class="popup-close-icon"></span></a>
                </div>
                <div class="popup-title">Propose a change</div>
                <div class="popup-body popup-greatings">
                        <div class="popup-success hidden">Your message has been sent!</div>
                        <div class="popup-error hidden">An error has occured, please write us an E-Mail</div>
                        <div class="label"><label for="suggestion">Suggestion</label></div>
                        <div><textarea class="form-input" name="suggestion" id="suggestion-text"></textarea></div>
                        <div class="label"><label for="email">E-Mail</label></div>
                        <div><input class="form-input" placeholder="Optional" type="email" name="email" id="suggestion-email"></div>
                        <div class="popup-buttons">
                                        <button type="button" class="button-primary" id="suggestion-submit">Submit</button>
                        </div>
                        <div class="report-notes">
                                Edit suggestions are usually processed within 48hours.
                        </div>
                </div>
        </div>`; var popupElement1 = document.createElement('div'); popupElement1.className = "popup-overlay report-popup-overlay hidden"; document.body.appendChild(popupElement1); var popupElement2 = document.createElement('div'); popupElement2.className = "popup-container hidden"; popupElement2.innerHTML = popupHtml; document.body.appendChild(popupElement2);
}
insertSuggestionPopup(); function closePopup() { document.querySelectorAll('.popup-overlay').forEach(popupOverlay => { popupOverlay.classList.add("hidden"); }); document.querySelectorAll('.popup').forEach(popup => { popup.parentElement.classList.add("hidden"); }); document.querySelector('body').classList.remove('overflow-hidden'); }
document.querySelectorAll('.close-popup').forEach(function (cElement) { cElement.addEventListener("click", function (event) { event.preventDefault(); closePopup(); }); }); addListener("#suggestion-submit", "click", function (event) { var suggestion = document.getElementById('suggestion-text').value; var email = document.getElementById('suggestion-email').value; var params = 'suggestion=' + suggestion + '&email=' + email + '&url=' + window.location.href; var xhr = new XMLHttpRequest(); xhr.open('POST', '/suggest.php', true); xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); xhr.responseType = 'json'; xhr.send(params); xhr.onload = function () { console.log(xhr.response); if (xhr.response.success) { document.querySelector(".report-popup .popup-success").classList.remove('hidden'); document.getElementById('suggestion-text').value = ''; document.getElementById('suggestion-email').value = ''; } else { document.querySelector(".report-popup popup-error").classList.remove('hidden'); } }; }); addListener(".popup-overlay, .popup-container", "click", function (e) {
        var targetElement = e.target; if (targetElement.classList.contains('not-closable')) { return; }
        if (targetElement.classList.contains('popup-container')) { closePopup(); }
}); addListener(".edit-list", "click", function (e) { e.preventDefault(); document.querySelector('body').classList.add('overflow-hidden'); document.querySelector(".report-popup-overlay").classList.remove('hidden'); document.querySelector(".report-popup").parentNode.classList.remove('hidden'); }); loadTableSortEvents(); function showMoreRankingTabs() {
        var rankTabs = document.querySelectorAll('.ranking-bar .expandable-rank'), i; for (i = 0; i < rankTabs.length; ++i) { rankTabs[i].classList.remove('expandable-rank'); }
        initialTheadTop = document.querySelector('.default-table thead').getBoundingClientRect().top + window.scrollY;
}