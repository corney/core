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

/**
 * Miscelaneous convertion routines
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_Converter extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Singleton access method
     * 
     * @return XLite_Core_Converter
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Convert a string like "test_foo_bar" into the camel case (like "TestFooBar")
     * 
     * @param string $string string to convert
     *  
     * @return string
     * @access public
     * @since  3.0
     */
    public static function convertToCamelCase($string)
    {
        return strval(preg_replace('/((?:\A|_)([a-zA-Z]))/ie', 'strtoupper(\'\\2\')', $string));
    }

    /**
     * Compose string from array 
     * 
     * @param array  $params    params list
     * @param string $glue      char to agglutinate "name" and "value"
     * @param string $separator char to agglutinate <"name", "value"> pairs
     * @param string $quotes    char to quote the "value" param
     *  
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildQuery(array $params, $glue = '=', $separator = '&', $quotes = '')
    {
        $result = array();

        foreach ($params as $name => $value) {
            $result[] = $name . $glue . $quotes . $value . $quotes;
        }

        return implode($separator, $result);
    }

    /**
     * Compose controller class name using target
     * 
     * @param string $target current target
     *  
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getControllerClass($target)
    {
        return 'XLite_Controller_' 
               . (XLite::isAdminZone() ? 'Admin' : 'Customer') 
               . (empty($target) ? '' : '_' . self::convertToCamelCase($target));
    }

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target    Page identifier
     * @param string $action    Action to perform
     * @param array  $params    Additional params
     * @param string $interface Interface script
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        $url = isset($interface) ? $interface : XLite::getInstance()->getScript();

        $parts = array();

        if ($target) {
            $parts[] = 'target=' . $target;
        }

        if ($action) {
            $parts[] = 'action=' . $action;
        }

        if ($params) {
            $parts[] = http_build_query($params);
        }

        if ($parts) {
            $url .= '?' . implode('&', $parts);
        }

        return $url;
    }

    /**
     * Compose full URL from target, action and additional params
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildFullURL($target = '', $action = '', array $params = array())
    {
        return XLite::getInstance()->getShopUrl(self::buildURL($target, $action, $params));
    }

    /**
     * Return array schema 
     * 
     * @param array $keys   keys list
     * @param array $values values list
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function getArraySchema(array $keys = array(), array $values = array())
    {
        return array_combine($keys, $values);
    }

    /**
     * Convert to one-dimensional array 
     * 
     * @param array  $data    array to flat
     * @param string $currKey parameter for recursive calls
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function convertTreeToFlatArray(array $data, $currKey = '')
    {
        $result = array();

        foreach ($data as $key => $value) {
            $key = $currKey . (empty($currKey) ? $key : '[' . $key . ']');
            $result += is_array($value) ? self::convertTreeToFlatArray($value, $key) : array($key => $value);
        }

        return $result;
    }

    /**
     * Return array elements having the corresponded keys 
     * 
     * @param array $data array to filter
     * @param array $keys keys (filter rule)
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function filterArrayByKeys(array $data, array $keys)
    {
        return array_intersect_key($data, array_fill_keys($keys, true));
    }

    /**
     * Gget cropped dimensions 
     * 
     * @param integer $w    Original width
     * @param integer $h    Original height
     * @param integer $maxw Maximum width
     * @param integer $maxh Maximum height
     *  
     * @return array (new width & height)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCroppedDimensions($w, $h, $maxw, $maxh)
    {
        $maxw = max(0, intval($maxw));
        $maxh = max(0, intval($maxh));

        $properties = array(
            'width'  => 0 < $w ? $w : $maxw,
            'height' => 0 < $h ? $h : $maxh,
        );

        if (0 < $w && 0 < $h && (0 < $maxw || 0 < $maxh)) {

            if (0 < $maxw && 0 < $maxh) {
                $kw = $w > $maxw ? $maxw / $w : 1;
                $kh = $h > $maxh ? $maxh / $h : 1;
                $k = $kw < $kh ? $kw : $kh;

            } elseif (0 < $maxw) {
                $k = $w > $maxw ? $maxw / $w : 1;

            } elseif (0 < $maxh) {
                $k = $h > $maxh ? $maxh / $h : 1;

            }

            $properties['width'] = max(1, round($k * $w, 0));
            $properties['height'] = max(1, round($k * $h, 0));
        }

        if (0 == $properties['width']) {
            $properties['width'] = null;
        }

        if (0 == $properties['height']) {
            $properties['height'] = null;
        }

        return array($properties['width'], $properties['height']);
    }

    /**
     * Check - specified string is URL or not
     * 
     * @param string $url URL
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isURL($url)
    {
        static $pattern = '(?:([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$\'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?)';

        return is_string($url) && 0 < preg_match('/^' . $pattern . '$/Ss', $url);
    }
}