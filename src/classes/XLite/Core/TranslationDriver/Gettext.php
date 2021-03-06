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

namespace XLite\Core\TranslationDriver;

/**
 * gettext-based driver
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Gettext extends \XLite\Core\TranslationDriver\ATranslationDriver
{
    const DOMAIN        = 'xlite';
    const CHARSET       = 'UTF-8';
    const SHORT_CHARSET = 'utf8';
    const CATEGORY      = 'LC_MESSAGES';


    /**
     * Dynamic domains list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $domains = array();

    /**
     * Last language code
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $lastLanguage = null;

    /**
     * msgfmt script path (cache)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $msgfmtPath = null;
 
    /**
     * Translate label
     * 
     * @param string $name Label name
     * @param string $code Language code
     *  
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function translate($name, $code)
    {
        if ($this->lastLanguage != $code || !$this->checkCurrentLocale($code)) {
            $this->setLocale($code);
            if ($this->lastLanguage != $code) {
                $this->checkIndex($code);
            }

            $this->lastLanguage = $code;
        }

        return dgettext($this->getDomain($code), $name);
    }

    /**
     * Check - valid driver or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isValid()
    {
        $result = function_exists('dgettext');

        if ($result) {
            if (!file_exists(LC_LOCALE_DIR)) {
                \Includes\Utils\FileManager::mkdirRecursive(LC_LOCALE_DIR);
            }

            if (!file_exists(LC_TMP_DIR)) {
                \Includes\Utils\FileManager::mkdirRecursive(LC_TMP_DIR);
            }

            $result = file_exists(LC_LOCALE_DIR)
                && is_dir(LC_LOCALE_DIR)
                && is_readable(LC_LOCALE_DIR)
                && is_writable(LC_LOCALE_DIR)
                && file_exists(LC_TMP_DIR)
                && is_dir(LC_TMP_DIR)
                && is_readable(LC_TMP_DIR)
                && is_writable(LC_TMP_DIR);
        }

        return $result;
    }

    /**
     * Reset language driver
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function reset()
    {
        \Includes\Utils\FileManager::unlinkRecursive(LC_LOCALE_DIR);

        $this->domains = array();
        $this->lastLanguage = null;
    }

    /**
     * Check - current locale is indetical specified language code or not
     * 
     * @param string $code Language code
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkCurrentLocale($code)
    {
        return getenv(self::CATEGORY) == $this->getLocaleByCode($code);
    }

    /**
     * Set current locale 
     * 
     * @param string $code Language code
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setLocale($code)
    {
        if (defined(self::CATEGORY)) {
            $locale = $this->getLocaleByCode($code);

            putenv(self::CATEGORY . '=' . $locale);
            setlocale(constant(self::CATEGORY), $locale);
        }
    }

    /**
     * Get locale code by language code 
     * 
     * @param string $code Language code
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocaleByCode($code)
    {
        return $code . '_' . strtoupper($code) . '.' . self::SHORT_CHARSET;
    }

    /**
     * Get dynamic domain name by language code
     * 
     * @param string $code Language code
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDomain($code)
    {
        if (!isset($this->domains[$code])) {
            $pattern = LC_LOCALE_DIR . LC_DS . $this->getLocaleByCode($code) . LC_DS . self::CATEGORY . LC_DS . '*.mo';
            foreach (glob($pattern) as $file) {
                $this->domains[$code] = substr(basename($file), 0, -3);
                break;
            }

            if (!isset($this->domains[$code])) {
                $this->domains[$code] = self::DOMAIN . '.' . (string)microtime(true);
            }

            bindtextdomain($this->domains[$code], LC_LOCALE_DIR);
            bind_textdomain_codeset($this->domains[$code], self::CHARSET);
        }

        return $this->domains[$code];
    }

    /**
     * Check index file (.mo file)
     * 
     * @param string $code Language code
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkIndex($code)
    {
        $result = true;

        $path = LC_LOCALE_DIR . LC_DS . $this->getLocaleByCode($code) . LC_DS . self::CATEGORY;
        if (!file_exists($path)) {
            \Includes\Utils\FileManager::mkdirRecursive($path);
        }

        $path .= LC_DS . $this->getDomain($code) . '.mo';
        if (!file_exists($path)) {
            $result = false;
            if ($this->getMsgFmtExecutable()) {
                $result = $this->createIndexFileBin($path, $code);
            }

            if (!$result) {
                $result = $this->createIndexFile($path, $code);
            }
        }

        return $result;
    }

    /**
     * Create index file (.mo file)
     * 
     * @param string $path Output path
     * @param string $code Language code
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function createIndexFile($path, $code)
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\LanguageLabel')
            ->findLabelsByCode($code);

        $result = false;

        // .mo-file format source: http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files
        $fp = @fopen($path, 'wb');
        if ($fp) {
 
            $n = count($list);
            $o = 28;
            $t = $o + $n * 8;
            $s = 0;
            $h = $t + $n * 8;
 
            // Writing the header and offsets
            fwrite($fp, pack('LLLLLLL', hexdec('950412de'), 0, $n, $o, $t, $s, $h));
 
            $spointer = $h + $s * 4;
 
            // Writing the table containing the lengths and offsets of language label names
            foreach ($list as $n => $v) {
                $l = strlen($n);
                fwrite($fp, pack('LL', $l, $spointer));
                $spointer += $l+1;
            }
 
            // Writing the table containing the lengths and offsets of language label values
            foreach ($list as $v) {
                $l = strlen($v);
                fwrite($fp, pack('LL', $l, $spointer));
                $spointer += $l + 1;
            }   

            $nul = chr(0);
 
            // Writing NUL terminated language label names
            foreach ($list as $n => $v) {
                fwrite($fp, $n . $nul);
            }
    
            // Writing NUL terminated language label values
            foreach ($list as $v) {
                fwrite($fp, $v . $nul);
            }
 
            fclose($fp);

            $result = true;
        }
 
        return $result;
    }

    /**
     * Create index file (.mo file) with msgfmt console script
     * 
     * @param string $path Output path
     * @param string $code Language code
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function createIndexFileBin($path, $code)
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\LanguageLabel')
            ->findLabelsByCode($code);

        $result = false;

        if (!file_exists(LC_TMP_DIR)) {
            \Includes\Utils\FileManager::mkdirRecursive(LC_TMP_DIR);
        }
        $poPath = LC_TMP_DIR . 'translate.' . $code . '.po';
        $fp = @fopen($poPath, 'wb');
        if ($fp) {

            foreach ($list as $k => $v) {
                fwrite(
                    $fp, 
                    'msgid "' . addcslashes($k, '"\\') . '"' . "\n" . 'msgstr "' . addcslashes($v, '"\\') . '"' . "\n\n"
                );
            }

            fclose($fp);

            $exec = $this->getMsgFmtExecutable();
            if ($exec) {
                $exec .= ' ' . $poPath . ' -o ' . $path;
                exec($exec);
                unlink($poPath);
            }

            $result = file_exists($path);
        }

        return $result;
    }

    /**
     * Get msgfmt script path
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMsgFmtExecutable()
    {
        if (is_null($this->msgfmtPath)) {
            $this->msgfmtPath = func_find_executable('msgfmt');
        }

        return $this->msgfmtPath;
    }
}
