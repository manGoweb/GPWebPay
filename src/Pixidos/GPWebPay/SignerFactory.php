<?php

namespace Pixidos\GPWebPay;

use Pixidos\GPWebPay\Intefaces\ISigner;
use Pixidos\GPWebPay\Intefaces\ISignerFactory;


class SignerFactory implements ISignerFactory
{

	/** @var Settings $settings */
	private $settings;


	public function __construct(Settings $settings)
	{
		$this->settings = $settings;
	}


	/**
	 * @param  null|string $gatewayKey
	 * @return ISigner
	 */
	public function create($gatewayKey = NULL)
	{
		return new Signer(
			$this->settings->getPrivateKey($gatewayKey),
			$this->settings->getPrivateKeyPassword($gatewayKey),
			$this->settings->getPublicKey()
		);
	}

}
