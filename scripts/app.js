(function (window, document, undefined) {

  'use strict';

  const IN_STOCK = 'Add to cart';
  const SOLD_OUT = 'Sold Out';
  const AUTO_NOTIFY = 'Auto Notify';

  function buildItemRow(row) {
    const itemRowTemplate = document.querySelector('#item-row').content.firstElementChild.cloneNode(true);
    const iconWrapperTemplate = document.querySelector('#icon-wrapper').content.firstElementChild.cloneNode(true);
    const itemIconTemplate = document.querySelector('#item-icon').content.firstElementChild.cloneNode(true);
    const itemLinkTemplate = document.querySelector('#item-link').content.firstElementChild.cloneNode(true);
    const td = itemRowTemplate.querySelectorAll('td');

    itemIconTemplate.textContent = row['icon'];
    itemLinkTemplate.textContent = row['status'];
    itemLinkTemplate.setAttribute('href', row['url']);
    iconWrapperTemplate.classList.add(row['class']);
    iconWrapperTemplate.appendChild(itemIconTemplate);
    iconWrapperTemplate.appendChild(itemLinkTemplate);

    td[0].textContent = row['product'];
    td[1].appendChild(iconWrapperTemplate);

    return itemRowTemplate;
  }

  function showNotification(row) {
    const notification = new Notification('GPU in Stock', {
      body: row['product'] + ' is in stock.',
    });

    notification.onclick = function (event) {
      event.preventDefault();
      window.open(row['url'], '_blank');
    };
  }

  function updateWindowTitle(inStockCount) {
    const defaultTitle = 'Gib 30 Series';

    document.title = (inStockCount > 0) ? defaultTitle + ' (' + inStockCount + ')' : defaultTitle;
  }

  function showLastUpdatedTime() {
    const lastChecked = document.querySelector('#last-checked');
    const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    const format = Intl.DateTimeFormat('en-US', options);

    lastChecked.textContent = format.format(new Date());
  }

  function handleXhrResponse() {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      const data = this.response;
      const table = document.querySelector('#item-table');
      const tbodyOld = table.querySelector('tbody');
      const tbodyNew = document.createElement('tbody');
      let inStockCount = 0;

      for (let row of data) {
        const storedRow = JSON.parse(window.localStorage.getItem(row['product']));
        let changed = (storedRow === null) || (storedRow['status'] !== row['status']);

        if (row['status'].toLowerCase() === IN_STOCK.toLowerCase()) {
          inStockCount++;

          if (changed) {
            showNotification(row);
          }
        }

        tbodyNew.appendChild(buildItemRow(row));
        window.localStorage.setItem(row['product'], JSON.stringify(row));
      }

      table.replaceChild(tbodyNew, tbodyOld);
      updateWindowTitle(inStockCount);
      showLastUpdatedTime();
    }
  }

  function stockCheckerLoop() {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = handleXhrResponse;
    xhr.responseType = 'json';
    xhr.open('GET', 'https://gib30series.wwu.local/ajax.php');
    xhr.send();
  }

  if (Notification.permission !== 'granted') {
    Notification.requestPermission();
  }

  window.localStorage.clear();
  window.setInterval(stockCheckerLoop, 5000);

})(this, this.document);
