/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Verify gft certificate box controller into Cart widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
jQuery(document).ready(
  function() {
    jQuery('#shopping-cart form.verify-gc').submit(
      function() {
        return !popup.load(this);
      }
    );
  }
);