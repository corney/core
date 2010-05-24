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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Add / update gift certificate widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_View_AddGiftCertificate extends XLite_View_Dialog
{
    /**
     * Gift certificate (cache)
     * 
     * @var    XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gc = null;

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('gift_certificate');

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Add gift certificate';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/GiftCertificates/add_gift_certificate';
    }

    /**
     * Get gift certificate 
     * 
     * @return XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGc()
    {
        if (is_null($this->gc)) {

            if (XLite_Core_Request::getInstance()->gcid) {

                // Get from request
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate(
                    XLite_Core_Request::getInstance()->gcid
                );

            } else {

                // Set default form values
                $this->setDefaultGiftCertificate();
            }
        }

        return $this->gc;
    }

    /**
     * Set default gift certificate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setDefaultGiftCertificate()
    {
        $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();

        $this->gc->set('send_via', 'E');
        $this->gc->set('border', 'no_border');
        if ($this->auth->isLogged()) {
            $profile = $this->auth->get('profile');
            $this->gc->set(
                'purchaser',
                $profile->get('billing_title')
                . ' '
                . $profile->get('billing_firstname')
                . ' '
                . $profile->get('billing_lastname')
            );
        }
        $this->gc->set('recipient_country', $this->config->General->default_country);
    }

    /**
     * Check - gift certificate is added or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isGCAdded()
    {
        $found = false;

        if (!is_null($this->getGc()) && $this->getGc()->isPersistent) {

            foreach ($this->getCart()->get('items') as $item) {
                if ($item->get('gcid') == $this->getGc()->get('gcid')) {
                    $found = true;
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/add_gift_certificate.js';
        if ($this->config->GiftCertificates->enablePostGC) {
            $list[] = 'js/select_states_begin.js';
            $list[] = 'js/select_states_end.js';
        }

        $list[] = 'popup/jquery.blockUI.js';
        $list[] = 'popup/popup.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'popup/popup.css';

        return $list;
    }

}