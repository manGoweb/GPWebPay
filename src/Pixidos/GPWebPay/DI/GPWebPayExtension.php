<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 21.10.2015
 * Time: 13:10
 */

namespace Pixidos\GPWebPay\DI;
use Nette;
use Nette\Utils\Validators;

/**
 * Class GPWebPayExtension
 * @package Pixidos\GPWebPay\DI
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */

class GPWebPayExtension extends Nette\DI\CompilerExtension
{
    public $defaults = [
        'depositFlag' => 1,
        'gatewayKey' => 'czk'
    ];

    public function loadConfiguration()
    {
        $config = $this->getConfig();

        $defaults = array_diff_key($this->defaults, $config);
        foreach ($defaults as $key => $val){
            $config[$key] = $this->defaults[$key];
        }

        Validators::assertField($config, 'privateKey');
        Validators::assertField($config, 'privateKeyPassword');
        Validators::assertField($config, 'publicKey');
        Validators::assertField($config, 'url');
        Validators::assertField($config, 'merchantNumber');
        Validators::assertField($config, 'depositFlag');
        Validators::assertField($config, 'gatewayKey');

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('settings'))
            ->setClass('Pixidos\GPWebPay\Settings', array(
                "privateKey" => $config["privateKey"],
                "privateKeyPassword" => $config['privateKeyPassword'],
                'publicKey' => $config['publicKey'],
                'url' => $config['url'],
                'merchantNumber' => $config['merchantNumber'],
                'depositFlag' => $config['depositFlag'],
                'gatewayKey' => $config['gatewayKey']
            ));

	    $builder->addDefinition($this->prefix('signerFactory'))
		    ->setClass('Pixidos\GPWebPay\Intefaces\ISignerFactory')
		    ->setFactory('Pixidos\GPWebPay\SignerFactory', array($this->prefix('@settings')));

        $builder->addDefinition($this->prefix('provider'))
            ->setClass('Pixidos\GPWebPay\Intefaces\IProvider')
            ->setFactory('Pixidos\GPWebPay\Provider', array($this->prefix('@settings'), $this->prefix('@signerFactory')));

        $builder->addDefinition($this->prefix('controlFactory'))
            ->setClass('Pixidos\GPWebPay\Components\GPWebPayControlFactory', array($this->prefix('@provider')));

    }

    public static function register(Nette\Configurator $configurator)
    {
        $configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
            $compiler->addExtension('gpwebpay', new GPWebPayExtension());
        };
    }
}
