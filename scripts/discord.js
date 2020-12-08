'use strict';

function sendDiscordNotifications(webhook, embeds) {
  const chunk = 10;

  for (let i = 0, j = embeds.length; i < j; i += chunk) {
    let xhr = new XMLHttpRequest();

    xhr.open('POST', webhook);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ embeds: embeds.slice(i, i + chunk) }));
  }
}

onmessage = function (event) {
  const webhook = event.data[0];
  const embeds = event.data[1];

  sendDiscordNotifications(webhook, embeds);
};
