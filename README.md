#Parametrizacao - MocaBonita

Modulo Parametrização para o MocaBonita

```sh
$ composer require jhorlima/siguema:dev-master --update-no-dev
``` 

Para integrar o modulo ao plugin, basta adicionar uma controller para a Parametrizacao e depois obter a view e método de salvar.

```php
<?php

//... restante do código

use MocaBonita\controller\MbController;
use MocaBonita\tools\MbException;
use MocaBonita\tools\MbRequest;
use MocaBonita\tools\MbResponse;
use Parametrizacao\model\Parametrizacao;

class ParametrizacaoController extends MbController
{
    public function indexAction(MbRequest $mbRequest, MbResponse $mbResponse)
    {
        return Parametrizacao::getMbView([
            'sigws_url',
            'sigws_name',
            'sigws_token',
            'timeout',
            'hash_query_lister',
            'hash_query',
        ]);
    }

    public function salvarAction(MbRequest $mbRequest, MbResponse $mbResponse)
    {
        $mbView = $this->indexAction($mbRequest, $mbResponse);

        try {
            Parametrizacao::salvarParametro($mbRequest->input());
            $mbResponse->redirect($mbRequest->fullUrlWithNewAction('index'));
        } catch (MbException $e) {
            $e->setExceptionData($mbView);
            throw $e;
        } catch (\Exception $e) {
            throw new MbException($e->getMessage(), $e->getCode(), $mbView);
        }
    }
}
```