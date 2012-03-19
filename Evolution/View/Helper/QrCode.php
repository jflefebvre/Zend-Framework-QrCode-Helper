<?php

/**
 * Output a QR code block.
 *
 * Currently, only via Google Chart API is supported, but it has
 * room to add other sources of qrcode generation.
 *
 * @author     Jean-François Lefebvre (jeff@deepbass.net)
 * @link       https://github.com/jflefebvre/Zend-Framework-QrCode-Helper
 * 
 * @category   Evolution
 * @package    Evolution_View
 * @subpackage Evolution_View_Helper
 */
class Evolution_View_Helper_QrCode extends Zend_View_Helper_Abstract
{
    protected $template = '
        <div class="qrcode">
            <p>Bookmark this page on your mobile</p>
            <img src="%s" alt="QR Code image" />
            <p class="hint"><a href="http://en.wikipedia.org/wiki/QR_Code">What is this?</a></p>
        </div>
        ';

    /**
     * Constructor.
     *
     * @return Evolution_View_Helper_QrCode
     */
    public function qrCode($template = null)
    {
        if (null !== $template) {
            $this->template = $template;
        }
        return $this;
    }

    /**
     * Generate the QR code image via Google's Chart API.
     *
     * @param  array  $params
     * @return string
     */
    public function google($params = array())
    {
    	
    	$scriptUri = isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : '';
    	
        $default = array(
            'text'       => $scriptUri,
            'size'       => '100x100',
            'correction' => 'M',
            'margin'     => 0
        );
        $params = array_merge($default, $params);

        $params['text']   = urlencode($params['text']);
        $params['margin'] = (int)$params['margin'];
        if (!in_array($params['correction'], array('L', 'M', 'Q', 'H'))) {
            $params['correction'] = 'M';
        }
        if (!preg_match('/^\d+x\d+$/', $params['size'])) {
            $params['size'] = '100x100';
        }

        $url = "http://chart.apis.google.com/chart?cht=qr&chl={$params['text']}"
             . "&chld={$params['correction']}|{$params['margin']}"
             . "&chs={$params['size']}";
        return sprintf($this->template, $url);
    }
}
