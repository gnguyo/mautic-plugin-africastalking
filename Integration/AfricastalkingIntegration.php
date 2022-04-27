<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Integration;

use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\PluginBundle\Integration\AbstractIntegration;

class AfricastalkingIntegration extends AbstractIntegration implements ConfigFormInterface {

	use ConfigurationTrait;
	use DefaultConfigFormTrait;

	public function getName(): string {
		return 'Africastalking';
	}

	public function getDisplayName(): string {
		return 'Africastalking';
	}

	public function getIcon(): string {
		return 'plugins/MauticAfricastalkingBundle/Assets/img/icon.png';
	}

    public function getSecretKeys(): array
    {
        return ['key'];
    }

	public function getAuthenticationType(): string {
		return 'none';
	}

    /**
     * @return string[]
     */
    public function getRequiredKeyFields(): array
    {
		return [
			'username' => 'mautic.africastalking.form.username',
			'key' => 'mautic.africastalking.form.key',
			'shortcode' => 'mautic.africastalking.form.shortcode',
		];
	}
}