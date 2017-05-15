<?php

namespace Parametrizacao\model;

use MocaBonita\MocaBonita;
use MocaBonita\tools\validation\MbStringValidation;
use MocaBonita\tools\validation\MbValidation;
use MocaBonita\view\MbView;

/**
 * Class Parametrizacao
 * @package Parametrizacao\model
 */
class Parametrizacao
{
    /**
     * @var string
     */
    protected static $prefix = 'siguema_';

    /**
     * @param array $dados
     * @return \array[]|mixed|null
     * @throws \Exception
     */
    public static function salvarParametro(array $dados)
    {
        $validation = MbValidation::validate($dados)
            ->setValidations('nome', MbStringValidation::getInstance(), ['min' => 5, 'max' => 20])
            ->setValidations('valor', MbStringValidation::getInstance(), ['min' => 1]);

        $validation->check(true);

        if (!update_option(self::$prefix . $validation->getData('nome'), $validation->getData('valor'))) {
            throw new \Exception("Não foi possível salvar o parametro {$validation->getData('nome')}!");
        }

        return $validation->getData('valor');
    }

    /**
     * @param $nome
     * @param null $defaul
     * @return mixed|void
     */
    public static function getParametro($nome, $defaul = null)
    {
        return get_option(self::$prefix . $nome, $defaul);
    }

    /**
     * @param array $nomes
     * @return array
     */
    public static function getParametros(array $nomes)
    {
        $dados = [];
        foreach ($nomes as $nome) {
            $dados [$nome] = self::getParametro($nome);
        }

        return $dados;
    }

    /**
     * @param array $params
     * @return MbView
     */
    public static function getMbView(array $params){
        $mbView = new MbView();

        $mbView->setMbResponse(MocaBonita::getInstance()->getMbResponse());
        $mbView->setMbRequest(MocaBonita::getInstance()->getMbRequest());
        $mbView->setTemplate('index');
        $mbView->setPage('parametrizacao');
        $mbView->setAction('index');
        $mbView->setViewPath(__DIR__ . "/../view/");

        $mbView->with('salvar', MocaBonita::getInstance()->getMbRequest()->fullUrlWithNewAction('salvar'));
        $mbView->with('parametros', Parametrizacao::getParametros($params));

        return $mbView;

    }
}