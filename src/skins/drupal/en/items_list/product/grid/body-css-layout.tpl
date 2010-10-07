{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list (grid variant)
 * NOTE: Unfortunately TABLE layout is the only cross-browser way to line up buttons
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{displayViewListContent(#itemsList.product.cart#)}

<!--
<table class="list-body list-body-grid list-body-grid-{getParam(#gridColumns#)}-columns" IF="!isCSSLayout()">
-->
<div class="{getContainerClass()}">

  <ul class="products-grid grid-list" IF="getPageData()">
    <li FOREACH="getPageData(),product" class="product-cell">
      <div class="product productid-{product.getProductId()}">
        {displayListPart(#info#,_ARRAY_(#product#^product))}
      </div>
    </li>
    <li FOREACH="getNestedViewList(#items#),item" class="product-cell">{item.display()}</li>
  </ul>

  <div IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}">{getMoreLinkText()}</a>
  </div>

</div>