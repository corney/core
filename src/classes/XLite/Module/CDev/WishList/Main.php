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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\WishList;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getModuleName()
    {
        return 'WishList';
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'This module introduces "Wish List" and "Send to Friend" features';
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init()
    {
        parent::init();
        \XLite::getInstance()->set('WishListEnabled', true);
    }

    /**
     * Modify view lists 
     * FIXME - to remove
     * 
     * @param array $data Annotation data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function modifyViewLists(array &$data)
    {
        $tpls = array(  
            'shopping_cart/parts/item.name.tpl',
            'shopping_cart/parts/item.sku.tpl',
            'shopping_cart/parts/item.weight.tpl',
        );

        foreach ($tpls as $tpl) {

            $list = \XLite\Core\Database::getRepo('XLite\Model\ViewList')->findOneByTplAndList('%' . $tpl, 'cart.item.info');

            if ($list) {
                $newList = new \XLite\Model\ViewList();
                $newList->setList('wishlist.item.info');
                $newList->setTpl($list->getTpl());
                $newList->setWeight($list->getWeight());

                \XLite\Core\Database::getEM()->persist($newList);
            }
        }

        \XLite\Core\Database::getEM()->flush();
    }
}