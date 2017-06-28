<?php

namespace Pixidos\GPWebPay\Intefaces;


interface ISignerFactory
{

	/**
	 * @param  null|string $gatewayKey
	 * @return ISigner
	 */
	public function create($gatewayKey = NULL);

}
