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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model;

/**
 * Address model
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table (name="profile_addresses",
 *      indexes={
 *          @Index (name="is_billing", columns={"is_billing"}),
 *          @Index (name="is_shipping", columns={"is_shipping"})
 *      }
 * )
 */
class Address extends \XLite\Model\AEntity
{

    /**
     * Address type codes 
     */
    const BILLING  = 'b';
    const SHIPPING = 's';


    /**
     * Unique id 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $address_id;

    /**
     * Flag: is it a billing address
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $is_billing = false;

    /**
     * Flag: is it a shipping address
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $is_shipping = false;

    /**
     * Address type: residential/commercial
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="1")
     */
    protected $address_type = 'R';

    /**
     * Title
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $title = '';

    /**
     * First name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="128")
     */
    protected $firstname = '';

    /**
     * Last name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="128")
     */
    protected $lastname = '';

    /**
     * Phone
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $phone = '';

    /**
     * Street, number of building, apartment etc
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $street = '';

    /**
     * City
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $city = '';

    /**
     * State
     *
     * @var   \XLite\Model\State
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\State", cascade={"merge","detach"})
     * @JoinColumn (name="state_id", referencedColumnName="state_id")
     */
    protected $state;

    /**
     * Custom state
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $custom_state = '';

    /**
     * Country
     *
     * @var   \XLite\Model\Country
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Country", cascade={"merge","detach"})
     * @JoinColumn (name="country_code", referencedColumnName="code")
     */
    protected $country;

    /**
     * Zip/postal code
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $zipcode = '';

    /**
     * Profile: many-to-one relation with profile entity
     * 
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne (targetEntity="XLite\Model\Profile", inversedBy="addresses")
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;


    /**
     * Get address fields list
     * 
     * @return array(string)
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAddressFields()
    {
        return array(
            'firstname',
            'lastname',
            'phone',
            'street',
            'city',
            'zipcode',
            'state_id',
            'custom_state',
            'country_code',
        );
    }


    /**
     * Get state 
     * 
     * @return \XLite\Model\State
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getState()
    {
        if ($this->state) {

            // Real state object
            $state = $this->state;

        } else {

            // Custom state
            $state = new \XLite\Model\State;
            $state->setState($this->getCustomState());
        }

        return $state;
    }

    /**
     * Set state
     *
     * @param mixed $state State object or state id or custom state name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setState($state)
    {
        if ($state instanceof \XLite\Model\State) {

            // Set by state object
            if ($state->getStateId()) {
                $this->state = $state;
                $this->setCustomState('');

            } else {
                
                $this->state = null;
                
                if ($state->getState()) {
                    $this->setCustomState($state->getState());
                }
            }


        } elseif (is_string($state)) {

            // Set custom state
            $this->state = null;
            $this->setCustomState($state);

        }
    }

    /**
     * Get full name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return trim($this->getFirstname() . ' ' . $this->getLastname());
    }

    /**
     * Set full name 
     *
     * @param string $value Full name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setName($value)
    {
        $parts = array_map('trim', explode(' ', trim($value), 2));

        $this->setFirstname($parts[0]);
        $this->setLastname(isset($parts[1]) ? $parts[1] : '');
    }

    /**
     * Get state Id 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getStateId()
    {
        return $this->getState() ? $this->getState()->getStateId() : null;
    }

    /**
     * Get country code
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCountryCode()
    {
        return $this->getCountry() ? $this->getCountry()->getCode() : null;
    }

    /**
     * Get billing address-specified required fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBillingRequiredFields()
    {
        return array(
            'name',
            'street',
            'city',
            'zipcode',
            'state',
            'country',
        );
    }

    /**
     * Get shipping address-specified required fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShippingRequiredFields()
    {
        return array(
            'name',
            'street',
            'city',
            'zipcode',
            'state',
            'country',
        );
    }

    /**
     * Get required fields by address type 
     * 
     * @param string $atype Address type code
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequiredFieldsByType($atype)
    {
        switch ($atype) {
            case self::BILLING:
                $list = $this->getBillingRequiredFields();
                break;

            case self::SHIPPING:
                $list = $this->getShippingRequiredFields();
                break;

            default:
                $list = null;
                // TODO - add throw exception
        }

        return $list;
    }

    /**
     * Get required and empty fields 
     * 
     * @param string $atype Address type code
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequiredEmptyFields($atype)
    {
        $result = array();

        foreach ($this->getRequiredFieldsByType($atype) as $name) {
            $method = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($name);
            // $method assebled from 'get' + \XLite\Core\Converter::getInstance()->convertToCamelCase() method
            if (!$this->$method()) {
                $result[] = $name;
            }
        }

        return $result;
    }

    /**
     * Check - address is completed or not
     * 
     * @param string $atype Address type code
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCompleted($atype)
    {
        return 0 == count($this->getRequiredEmptyFields($atype));
    }

    /**
     * Clone
     *
     * @return \XLite\Model\AEntity
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function cloneEntity()
    {
        $entity = parent::cloneEntity();

        if ($this->getCountry()) {
            $entity->setCountry($this->getCountry());
        }

        if ($this->getState()) {
            $entity->setState($this->getState());
        }

        if ($this->getProfile()) {
            $entity->setProfile($this->getProfile());
        }

        return $entity;
    }

    /**
     * Update record in database
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function update()
    {
        return $this->checkAddress() && parent::update();
    }

    /**
     * Create record in database
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function create()
    {
        return $this->checkAddress() && parent::create();
    }


    /**
     * Check if address has duplicates
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAddress()
    {
        $result = true;

        $sameAddress = \XLite\Core\Database::getRepo('XLite\Model\Address')->findSameAddress($this);

        if ($sameAddress) {
            \XLite\Core\TopMessage::addWarning('Address was not saved as other address with specified fields is already exists.');
            $result = false;
        }

        return $result;
    }
}
