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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_DrupalConnector_Model_Profile 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_Module_DrupalConnector_Model_Profile extends XLite_Model_Profile implements XLite_Base_IDecorator
{
	/**
     * Builds the SQL INSERT statement query for this object properties
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _buildInsert()
    {
        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $this->properties['cms_name'] = XLite_Module_DrupalConnector_Handler::getInstance()->getCMSName();
        }

        return parent::_buildInsert();
    }

    /**
     * Builds the SQL UPDATE statement for updating this object database record
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _buildUpdate()
    {
        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            unset($this->properties['cms_name']);
            unset($this->properties['cms_profile_id']);
        }

        return parent::_buildUpdate();
    }
}