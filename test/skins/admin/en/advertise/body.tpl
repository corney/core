{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Advertise block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="adv-block">
  <div class="internal">
    <a href="#" onclick="javascript: this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;" class="close"><img src="images/spacer.gif" alt="Close" /></a>
    <table cellspacing="0">
      <tr>
        <td class="block"><widget template="{getBlockName()}" /></td>
        <td class="qualiteam">
          <img src="images/logo_qualiteam.png" alt="Qualiteam services" /><br />
          <br />
          <a href="http://www.qtmsoft.com">www.qtmsoft.com</a>
        </td>
      </tr>
    </table>
  </div>
</div>
{foreach:getJSFiles(),src}
<script type="text/javascript" src="skins/admin/en/{src}"></script>
{end:}
{foreach:getCSSFiles(),src}
<link rel="stylesheet" type="text/css" href="skins/admin/en/{src}" />
{end:}
<widget class="XLite_View_HelpdeskRequest" />
