<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Core;

use Exception;
use Mautic\PluginBundle\Helper\IntegrationHelper;

class Configuration {

	private IntegrationHelper $integrationHelper;

	private string $username;

	private string $apikey;

    private string $shortcode;

    public function __construct(IntegrationHelper $integrationHelper) {
		$this->integrationHelper = $integrationHelper;
	}

    /**
     * @throws Exception
     */
	public function getUsername(): ?string
    {
		$this->setConfiguration();

		return $this->username;
	}

	/**
	 * @throws Exception
	 */
	public function getApiKey(): ?string
    {
		$this->setConfiguration();

		return $this->apikey;
	}

	/**
	 * @throws Exception
	 */
	public function getShortCode(): ?string
    {
		$this->setConfiguration();

		return empty($this->shortcode) ? null : $this->shortcode;
	}

	/**
	 * @throws Exception
	 */
	private function setConfiguration(): void {
		if (isset($this->username)) {
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
