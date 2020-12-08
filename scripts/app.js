(function (window, document, undefined) {

  'use strict';

  const IN_STOCK = 'Add to cart';
  const SOLD_OUT = 'Sold Out';
  const AUTO_NOTIFY = 'Auto Notify';

  if (Notification.permission !== 'granted') {
    Notification.requestPermission();
  }

  window.setInterval(function () {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const data = JSON.parse(this.responseText);
        const table = document.querySelector('#item-table');
        const tbodyOld = table.querySelector('tbody');
        const tbodyNew = document.createElement('tbody');
        const lastChecked = document.querySelector('#last-checked');
        const format = Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });

        for (const row of data) {
          if (row['status'] == 'Add to cart') {
            const notification = new Notification('GPU in Stock', {
              body: row['product'] + ' is in stock.',
            });

            notification.onclick = (function (url) {
              return function (event) {
                event.preventDefault();
                window.open(url, '_blank');
              };
            })(row['url']);
          }

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

          tbodyNew.appendChild(itemRowTemplate);
        }

        table.replaceChild(tbodyNew, tbodyOld);

        lastChecked.textContent = format.format(new Date());
      }
    };

    xhr.open('GET', 'https://gib30series.wwu.local/ajax.php');
    xhr.send();
  }, 5000);

})(this, this.document);
