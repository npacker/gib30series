(function (window, document, undefined) {

  'use strict';

  const IN_STOCK = 'Add to cart';
  const SOLD_OUT = 'Sold Out';
  const AUTO_NOTIFY = 'Auto Notify';
  const WINDOW_TITLE = 'Gib 30 Series';
  const FETCH_ENDPOINT = 'https://gib30series.wwu.local/ajax.php';
  const DISCORD_WEBHOOK = 'https://discordapp.com/api/webhooks/785772587632427019/BRsi-suF7jUSrPxlDxn5XM5JFs15bR5Cy1z8mqWgyM-xuMa6T99ANekDvsKbem3YI_8U';

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

  function sendDiscordNotifications(embeds) {
    const chunk = 10;

    for (let i = 0, j = embeds.length; i < j; i += chunk) {
      let xhr = new XMLHttpRequest();

      xhr.open('POST', DISCORD_WEBHOOK);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify({ embeds: embeds.slice(i, i + chunk) }));
    }
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
      let count = 0;

      for (let row of data) {
        let storedRow = JSON.parse(window.localStorage.getItem(row['product']));
        let changed = (storedRow === null) || (storedRow['status'] !== row['status']);

        if (row['status'].toLowerCase() === IN_STOCK.toLowerCase()) {
          count++;

          if (changed) {
            embeds.push({
              title: 'GPU Stock Notification',
              description: '[' + row['product'] + '](' + row['url'] + ')'
            });
          }
        }

        current.appendChild(buildItemRow(row));
        window.localStorage.setItem(row['product'], JSON.stringify(row));
      }

      table.replaceChild(current, previous);
      document.title = (count > 0) ? WINDOW_TITLE + ' (' + count + ')' : WINDOW_TITLE;
      showLastUpdatedTime();
      sendDiscordNotifications(embeds);
    }
  }

  function mainEventLoop() {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = handleXhrResponse;
    xhr.responseType = 'json';
    xhr.open('GET', FETCH_ENDPOINT);
    xhr.send();
  }

  if (Notification.permission !== 'granted') {
    Notification.requestPermission();
  }

  window.localStorage.clear();
  window.setInterval(mainEventLoop, 5000);

})(this, this.document);
