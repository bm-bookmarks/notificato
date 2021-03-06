<?php

namespace Wrep\Notificato\Apns;

class MessageFactory
{
	private $certificateFactory;

	/**
	 * Create the MessageFactory
	 *
	 * @param CertificateFactory|null The certificate factory to use when no specific certificate is given on message creation
	 */
	public function __construct(CertificateFactory $certificateFactory = null)
	{
		$this->setCertificateFactory($certificateFactory);
	}

	/**
	 * Set a certificate factory to fetch the default certificate from
	 *
	 * @param CertificateFactory|null The certificate factory to use when no specific certificate is given on message creation
	 */
	public function setCertificateFactory(CertificateFactory $certificateFactory = null)
	{
		$this->certificateFactory = $certificateFactory;
	}

	/**
	 * Get the current certificate factory
	 *
	 * @return CertificateFactory|null
	 */
	public function getCertificateFactory()
	{
		return $this->certificateFactory;
	}

	/**
	 * Create a Message
	 *
	 * @param string Receiver of this message
	 * @param Certificate|null The certificate that must be used for the APNS connection this message is send over, null to use the default certificate
	 */
	public function createMessage($deviceToken, Certificate $certificate = null)
	{
		// Check if a certificate is given, if not use the default certificate
		if (null == $certificate && $this->getCertificateFactory() != null) {
			$certificate = $this->getCertificateFactory()->getDefaultCertificate();
		}

		// Check if there is a certificate to use after falling back on the default certificate
		if (null == $certificate) {
			throw new \RuntimeException('No certificate given for the creation of the message and no default certificate available.');
		}

		// Create and return the new Message
		return new Message($deviceToken, $certificate);
	}
}