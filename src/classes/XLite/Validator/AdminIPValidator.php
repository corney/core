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
 * @subpackage Validator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define(
    'IPV4_REGEXP_WILDCARD',
    '/^(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)$/'
);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Validator_AdminIPValidator extends XLite_Validator_Abstract
{
    public $template = "common/ip_validator.tpl";

    function isValid()
    {
        if (isset($_REQUEST[$this->get('field')]) && isset($_REQUEST[$this->get('field2')]) && isset($_REQUEST[$this->get('field3')]) && isset($_REQUEST[$this->get('field4')])) {
            $ip = $_REQUEST[$this->get('field')] . "." . $_REQUEST[$this->get('field2')] . "." . $_REQUEST[$this->get('field3')] . "." . $_REQUEST[$this->get('field4')];

            // validate
            return preg_match(IPV4_REGEXP_WILDCARD, $ip);
        }
        return true;
    }
}