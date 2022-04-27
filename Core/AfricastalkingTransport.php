<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Core;

use Exception;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Sms\TransportInterface;
use MauticPlugin\MauticAfricastalkingBundle\Core\SDK\AfricasTalking;
use Psr\Log\LoggerInterface;

class AfricastalkingTransport implements TransportInterface {

	private Configuration $configuration;

	private LoggerInterface $logger;

	private AfricasTalking $client;

	public function __construct(Configuration $configuration, LoggerInterface $logger) {
		$this->logger = $logger;
		$this->configuration = $configuration;
	}

	public function sendSms(Lead $lead, $content): bool
    {

		$number = $lead->getLeadPhoneNumber();

		if (empty($number)) {
			$this->logger->addWarning("No phone number.");
			return false;
		}

		try {
			$this->configureClient();

			$this->client->sms()->send([
				'to' => $this->sanitizeNumber($number),
				'message' => $content,
				'from' => $this->configuration->getShortCode(),
			]);

			return true;
		} catch (NumberParseException | Exception $exception) {

			$this->logger->addWarning(
				$exception->getMessage(),
				['exception' => $exception]
			);

			return false;
		}
    }

	/**
	 * @throws NumberParseException
	 */
	private function sanitizeNumber(string $number): string
    {
		$util = PhoneNumberUtil::getInstance();
		$parsed = $util->parse($number, 'KE');

		return $util->format($parsed, PhoneNumberFormat::E164);
	}

    /**
     * @throws Exception
     */
    private function configureClient() {
		if (isset($this->client)) {
			// Already configured
			return;
		}

		$this->client = new SDK\AfricasTalking(
			$this->configuration->getUsername(),
			$this->configuration->getApiKey()
		);

	}
}
