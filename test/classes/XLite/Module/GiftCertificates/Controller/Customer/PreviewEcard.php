<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Gift Certificate E-Card preview controller
 * 
 * @package    XLite
 * @subpackage Controller
 * @since      3.0.0
 */
class XLite_Module_GiftCertificates_Controller_Customer_PreviewEcard extends XLite_Controller_Abstract
{	
    /**
     * params 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
	public $params = array('target', 'gcid');	

    /**
     * gc 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $gc = null;	
    
    /**
     * getGC 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getGC()
    {
        if (is_null($this->gc)) {
            $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->gcid);
        }
        return $this->gc;
    }

    /**
     * getRegularTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        return 'modules/GiftCertificates/preview.tpl';
    }


}
