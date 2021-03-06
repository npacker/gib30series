<?php

interface Status {

  const IN_STOCK = 'In stock';

  const SOLD_OUT = 'Sold Out';

  const AUTO_NOTIFY = 'Auto Notify';

  const IN_STOCK_CLASS = 'in-stock';

  const IN_STOCK_ICON = 'check_circle';

  const SOLD_OUT_CLASS = 'sold-out';

  const SOLD_OUT_ICON = 'cancel';

  const AUTO_NOTIFY_CLASS = 'notify';

  const AUTO_NOTIFY_ICON = 'info';

}
