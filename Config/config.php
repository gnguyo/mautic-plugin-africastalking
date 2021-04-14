<?php declare (strict_types = 1);

return [
	'name' => 'Example Bundle',
	'description' => 'It does nothing. But it\'s looking great doing so!',
	'version' => '0.0.0',
	'author' => 'Mautic Community',

	'services' => [
		'commands' => [
			'cronfig.command.do_nothing' => [
				'class' => \MauticPlugin\MauticAfricastalkingBundle\Command\DoNothing::class,
				'tag' => 'console.command',
			],
		],
		'other' => [
			'mautic.sms.transport.africastalking' => [
				'class' => \MauticPlugin\MauticAfricastalkingBundle\Core\AfricastalkingTransport::class,
				'arguments' => [
					'mautic.sms.africastalking.configuration',
					'monolog.logger.mautic',
				],
				'tag' => 'mautic.sms_transport',
				'tagArguments' => [
					'integrationAlias' => 'Africastalking',
				],
				'serviceAliases' => [
					'sms_api',
					'mautic.sms.api',
				],
			],
			'mautic.sms.africastalking.configuration' => [
				'class' => \MauticPlugin\MauticAfricastalkingBundle\Core\Configuration::class,
				'arguments' => [
					'mautic.helper.integration',
				],
			],
		],
		'integrations' => [
			// Basic definitions with name, display name and icon
			'mautic.integration.africastalking' => [
				'class' => \MauticPlugin\MauticAfricastalkingBundle\Integration\AfricastalkingIntegration::class,
				'tags' => [
					'mautic.integration',
					'mautic.config_integration',
				],
				'arguments' => [
					'event_dispatcher',
					'mautic.helper.cache_storage',
					'doctrine.orm.entity_manager',
					'session',
					'request_stack',
					'router',
					'translator',
					'logger',
					'mautic.helper.encryption',
					'mautic.lead.model.lead',
					'mautic.lead.model.company',
					'mautic.helper.paths',
					'mautic.core.model.notification',
					'mautic.lead.model.field',
					'mautic.plugin.model.integration_entity',
					'mautic.lead.model.dnc',
				],
			],
		],
	],

	'menu' => [
		'main' => [
			'items' => [
				'mautic.sms.smses' => [
					'route' => 'mautic_sms_index',
					'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
					'parent' => 'mautic.core.channels',
					'checks' => [
						'integration' => [
							'Africastalking' => [
								'enabled' => true,
							],
						],
					],
					'priority' => 70,
				],
			],
		],
	],
];
