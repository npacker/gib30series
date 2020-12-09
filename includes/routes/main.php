<?php
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
?>
<!doctype html>
<html>
  <head>
    <title>Gib 30 Series</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="styles/screen.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
      window.settings = Object.freeze(<?php echo json_encode($settings); ?>);
    </script>
    <script type="text/javascript" src="scripts/app.js"></script>
  </head>
  <body>
  <h1>Newegg RTX 3070 Stock Checker</h1>
  <div class="before-content">
    <div class="icon-wrapper">
      <i class="material-icons">history</i>
      <strong>Last Checked:</strong>
      <span id="last-checked">
      <?php $date = new DateTime('now', new DateTimeZone('PST')); echo $date->format('n/j/Y, g:i:s A'); ?>
      </span>
    </div>
  </div>
  <table id="item-table">
    <thead>
      <tr>
        <th>Item</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <template id="item-row">
    <tr>
      <td></td>
      <td></td>
    </tr>
  </template>
  <template id="icon-wrapper">
    <div class="icon-wrapper"></div>
  </template>
  <template id="item-icon">
    <i class="material-icons"></i>
  </template>
  <template id="item-link">
    <a class="item-link" href="" target="_blank"></a>
  </template>
  </body>
</html>
