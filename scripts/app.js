(function (settings, window, document, undefined) {

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
      const previous = table.querySelector('tbody');
      const current = document.createElement('tbody');
      let embeds = [];
      let update = false;

      for (let row of data) {
        let storedRow = JSON.parse(window.localStorage.getItem(row['product']));
        let inStock = row['status'].toLowerCase() === IN_STOCK.toLowerCase();
        let changed = (storedRow === null) || (storedRow['status'] !== row['status']);

        if (changed) {
          update = true;

          if (inStock) {
            embeds.push({
              title: 'GPU Stock Notification',
              description: '[' + row['product'] + '](' + row['url'] + ')'
            });
          }
        }

        current.appendChild(buildItemRow(row));
        window.localStorage.setItem(row['product'], JSON.stringify(row));
      }

      if (update) {
        discord.postMessage([settings.discord_webhook, embeds]);
      }

      table.replaceChild(current, previous);
      showLastUpdatedTime();
    }
  }

  function mainEventLoop() {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = handleXhrResponse;
    xhr.responseType = 'json';
    xhr.open('GET', '/ajax');
    xhr.send();
  }

  const discord = new Worker('scripts/discord.js');

  if (Notification.permission !== 'granted') {
    Notification.requestPermission();
  }

  window.setInterval(mainEventLoop, 5000);

  mainEventLoop();

})(this.settings, this, this.document);
