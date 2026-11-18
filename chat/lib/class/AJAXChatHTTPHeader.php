<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Class to manage HTTP header
class AJAXChatHTTPHeader {

	protected
		$_contentType,
		$_constant,
		$_noCache,
		$_frameOptions;

	public function __construct($encoding='UTF-8', $contentType=null, $noCache=true, $frameOptions='SAMEORIGIN') {
		if($contentType) {
			$this->_contentType = $contentType.'; charset='.$encoding;
			$this->_constant = true;
		} else {
			if(isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') !== false)) {
				$this->_contentType = 'application/xhtml+xml; charset='.$encoding;
			} else {
				$this->_contentType = 'text/html; charset='.$encoding;
			}
			$this->_constant = false;
		}
		$this->_noCache = $noCache;
		$this->_frameOptions = $frameOptions;
	}

	// Method to send the HTTP header:
	public function send() {
		// Prevent caching:
		if($this->_noCache) {
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}

		// Send the content-type-header:
		header('Content-Type: '.$this->_contentType);

		// Send vary header if content-type varies (important for proxy-caches):
		if(!$this->_constant) {
			header('Vary: Accept');
		}

		// Security headers:
		header('X-Content-Type-Options: nosniff');
		header('Referrer-Policy: same-origin');
		// Clickjacking protection: 'SAMEORIGIN' (default) still allows embedding the chat in an iframe of the same site,
		// 'DENY' forbids every frame, null/false sends no header (e.g. if the chat is embedded in another domain):
		if($this->_frameOptions) {
			header('X-Frame-Options: '.$this->_frameOptions);
			header("Content-Security-Policy: frame-ancestors ".(strtoupper($this->_frameOptions) === 'DENY' ? "'none'" : "'self'"));
		}
	}

	// Method to return the content-type string:
	public function getContentType() {
		// Return the content-type string:
		return $this->_contentType;
	}

}
