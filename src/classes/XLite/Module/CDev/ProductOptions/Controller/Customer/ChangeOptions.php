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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\ProductOptions\Controller\Customer;

/**
 * Change options from cart / wishlist item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class ChangeOptions extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Item (cache)
     * 
     * @var    \XLite\Model\OrderItem
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $item = null;

    /**
     * Common method to determine current location 
     * 
     * @return array 
     * @access protected 
     * @since  1.0.0
     */      
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Get page title
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return $this->t(
            '"X product" options',
            array('product' => $this->getItem()->getName())
        );
    }

    /**
     * Initialize controller
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();

        if (!$this->getItem()) {
            $this->redirect();
        }
    }

    /**
     * Perform some actions before redirect
     *
     * :FIXME: check. Action should not be an optional param
     * 
     * @param string $action Current action
     *  
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function actionPostprocess($action = null)
    {
        parent::actionPostprocess($action);

        if ($action) {
            $this->assembleReturnURL();
        }
    }

    /**
     * Assemble return url 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleReturnURL()
    {
        $this->setReturnURL($this->buildURL(\XLite::TARGET_DEFAULT));
        if (\XLite\Core\Request::getInstance()->source == 'cart') {
            $this->setReturnURL($this->buildURL('cart'));
        }
    }

    /**
     * Change product options
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionChange()
    {
        if ('cart' == \XLite\Core\Request::getInstance()->source) {
            $options = $this->getItem()
                ->getProduct()
                ->prepareOptions(\XLite\Core\Request::getInstance()->product_options);

            if (is_array($options) && $this->getItem()->getProduct()->checkOptionsException($options)) {
                $this->getItem()->setProductOptions($options);
                $this->updateCart();

                \XLite\Core\TopMessage::addInfo('Options has been successfully changed');

                $this->setSilenceClose();

            } else {

                \XLite\Core\TopMessage::addError(
                    'The product options you have selected are not valid or fall into an exception.'
                    . ' Please select other product options'
                );

                $this->setInternalRedirect();

                $this->setReturnURL($this->buildURL(
                        'change_options',
                        '',
                        array(
                            'source'     => \XLite\Core\Request::getInstance()->source,
                            'storage_id' => \XLite\Core\Request::getInstance()->storage_id,
                            'item_id'    => \XLite\Core\Request::getInstance()->item_id,
                        )
                    )
                );
            }
        }
    }

    /**
     * Get cart / wishlist item 
     * 
     * @return \XLite\Model\OrderItem
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItem()
    {
        if (is_null($this->item)) {
            $this->item = false;

            if (
                \XLite\Core\Request::getInstance()->source == 'cart'
                && is_numeric(\XLite\Core\Request::getInstance()->item_id)
            ) {
                $item = $this->getCart()->getItemByItemId(\XLite\Core\Request::getInstance()->item_id);

                if (
                    $item
                    && $item->getProduct()
                    && $item->hasOptions()
                ) {
                    $this->item = $item;
                }
            }
        }

        return $this->item;
    }

    /**
     * Get product 
     * 
     * @return \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProduct()
    {
        return $this->getItem()->getProduct();
    }

}
