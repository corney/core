<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_View_Button_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_View_Button_Abstract extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_LABEL = 'label';
    const PARAM_BUTTON_STYLE = 'style';

    /**
     * allowedJSEvents 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedJSEvents = array('onclick' => 'One click');


    /**
     * Return button text 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getButtonLabel()
    {
        return $this->getParam(self::PARAM_LABEL);
    }

    /** 
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_LABEL => new XLite_Model_WidgetParam_String('Label', '', true),
            self::PARAM_BUTTON_STYLE => new XLite_Model_WidgetParam_String('Button style', '', false),
        );
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        return array_merge(parent::getCSSFiles(), array('button/button.css'));
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function getJSFiles()
    {
        return array_merge(parent::getJSFiles(), array('button/button.js'));
    }
}

