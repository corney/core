{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="itemsList.product.modify.common.admin.columns", weight="30")
 *}

<td><a class="name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.getProductId()))}">{if:product.getName()}{product.getName():h}{else:}N/A{end:}</a></td>
