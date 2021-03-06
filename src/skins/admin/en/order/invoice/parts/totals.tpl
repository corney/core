{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.base", weight="40")
 *}
<table cellspacing="0" class="totals">

  <tr>
    <td class="title">{t(#Subtotal#)}:</td>
    <td class="value">{formatPrice(order.getSubtotal(),order.getCurrency())}</td>
  </tr>

  <tr FOREACH="order.getSurcharges(),surcharge" class="{surcharge.getType()}">
    <td class="title">{surcharge.getName()}:</td>
    <td class="value">{if:surcharge.getAvailable()}{formatPrice(surcharge.getValue(),order.getCurrency())}{else:}{t(#n/a#)}{end:}</td>
  </tr>

  <tr class="total">
    <td class="title">{t(#Grand total#)}:</td>
    <td class="value">{formatPrice(order.getTotal(),order.getCurrency())}</td>
  </tr>

</table>
