<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Core;

use Exception;
use Mautic\PluginBundle\Helper\IntegrationHelper;

class Configuration {
	/**
	 * @var IntegrationHelper
	 */
	private $integrationHelper;

	/**
	 * @var string
	 */
	private $sendingPhoneNumber;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $apikey;

	/**
	 * Configuration constructor.
	 */
	public function __construct(IntegrationHelper $integrationHelper) {
		$this->integrationHelper = $integrationHelper;
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 */
	public function getUsername() {
		$this->setConfiguration();

		return $this->username;
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 */
	public function getApiKey() {
		$this->setConfiguration();

		return $this->apikey;
	}

	/**
	 * @return string
	 *
	 * @throws Exception
	 */
	public function getShortCode() {
		$this->setConfiguration();

		return empty($this->shortcode) ? null : $this->shortcode;
	}

	/**
	 * @throws Exception
	 */
	private function setConfiguration() {
		if ($this->username) {
			return;
		}

		$integration = $this->integrationHelper->getIntegrationObject('Africastalking');

		if (!$integration || !$integration->getIntegrationSettings()->getIsPublished()) {
			throw new Exception();
		}

		$keys = $integration->getDecryptedApiKeys();
		if (empty($keys['username']) || empty($keys['key'])) {
			throw new Exception("Africastalking configuration not set.");
		}

		$this->username = $keys['username'];
		$this->apikey = $keys['key'];
		$this->shortcode = $keys['shortcode'];
	}
}
