<?php

namespace MauticPlugin\MauticAfricastalkingBundle\Core;

use Exception;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Sms\TransportInterface;
use Psr\Log\LoggerInterface;

class AfricastalkingTransport implements TransportInterface {
	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var string
	 */
	private $sendingPhoneNumber;

	/**
	 * TwilioTransport constructor.
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger) {
		$this->logger = $logger;
		$this->configuration = $configuration;
	}

	/**
	 * @param string $content
	 *
	 * @return bool|string
	 */
	public function sendSms(Lead $lead, $content) {

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
		} catch (NumberParseException $exception) {

			$this->logger->addWarning(
				$exception->getMessage(),
				['exception' => $exception]
			);

			return $exception->getMessage();
		} catch (Exception $exception) {
			$this->logger->addWarning(
				$exception->getMessage(),
				['exception' => $exception]
			);

			return $exception->getMessage();
		}
	}

	/**
	 * @param string $number
	 *
	 * @return string
	 *
	 * @throws NumberParseException
	 */
	private function sanitizeNumber($number) {
		$util = PhoneNumberUtil::getInstance();
		$parsed = $util->parse($number, 'KE');

		return $util->format($parsed, PhoneNumberFormat::E164);
	}

	private function configureClient() {
		if ($this->client) {
			// Already configured
			return;
		}

		$this->client = new SDK\AfricasTalking(
			$this->configuration->getUsername(),
			$this->configuration->getApiKey()
		);

	}
}
