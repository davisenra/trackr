<?php

namespace Tests\Unit\Services\Correios;

use App\DTOs\TrackedEvent;
use App\Services\Correios\LinkTrackApiException;
use App\Services\Correios\LinkTrackApiTracker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class LinkTrackApiTrackingServiceTest extends TestCase
{
    private LinkTrackApiTracker $trackingService;
    private MockHandler $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();

        $handlerStack = HandlerStack::create($this->mockHandler);

        $httpClient = new Client([
            'handler' => $handlerStack,
            'http_errors' => false,
        ]);

        $this->trackingService = new LinkTrackApiTracker(
            'fake_user',
            'fake_token',
            $httpClient,
        );
    }

    public function testItCanBeInstantiated(): void
    {
        $this->assertInstanceOf(LinkTrackApiTracker::class, $this->trackingService);
    }

    public function testItCanTrackAPackage(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"codigo":"NL999340452BR","host":"mr","eventos":[{"data":"26/11/2023","hora":"11:27:52","local":"Unidade de Distribuição - VILA VELHA/ES","status":"A entrega não pode ser efetuada - Carteiro não atendido","subStatus":["Origem: Unidade de Distribuição - VILA VELHA/ES"]},{"data":"26/11/2023","hora":"08:41:04","local":"Unidade de Distribuição - VILA VELHA/ES","status":"Objeto saiu para entrega ao destinatário","subStatus":["Origem: Unidade de Distribuição - VILA VELHA/ES"]},{"data":"24/11/2023","hora":"14:16:13","local":"Unidade de Tratamento - SERRA/ES","status":"Objeto encaminhado","subStatus":["Origem: Unidade de Tratamento - SERRA/ES","Destino: Unidade de Distribuição - VILA VELHA/ES"]},{"data":"18/11/2023","hora":"12:48:02","local":"Unidade de Logística Integrada - CURITIBA/PR","status":"Objeto encaminhado","subStatus":["Origem: Unidade de Logística Integrada - CURITIBA/PR","Destino: Unidade de Tratamento - SERRA/ES"]},{"data":"18/11/2023","hora":"12:48:01","local":"Unidade de Logística Integrada - CURITIBA/PR","status":"Saída do Centro Internacional","subStatus":["Origem: Unidade de Logística Integrada - CURITIBA/PR"]},{"data":"18/11/2023","hora":"03:01:37","local":"Unidade de Logística Integrada - CURITIBA/PR","status":"Objeto recebido pelos Correios do Brasil","subStatus":["Origem: Unidade de Logística Integrada - CURITIBA/PR"]},{"data":"13/11/2023","hora":"15:37:29","local":"País","status":"Objeto postado","subStatus":["Origem: País"]},{"data":"13/11/2023","hora":"10:10:58","local":"Unidade de Logística Integrada - CURITIBA/PR","status":"Informações eletrônicas enviadas para análise da autoridade aduaneira","subStatus":["Origem: Unidade de Logística Integrada - CURITIBA/PR"]}],"time":0.138,"quantidade":8,"servico":"","ultimo":"2023-11-26T14:27:00.000Z"}'));

        $trackedEvents = $this->trackingService->track('BR123456789BR');

        $this->assertNotEmpty($trackedEvents);
        $this->assertContainsOnlyInstancesOf(TrackedEvent::class, $trackedEvents);
    }

    public function testAnExceptionIsThrownWhenNoEventsAreFound(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"codigo":"NL999340152BR","host":"yi","eventos":[],"time":0.794,"quantidade":0,"servico":""}'));

        $this->expectException(LinkTrackApiException::class);
        $this->expectExceptionMessage('No events found for package: BR123456789BR');

        $this->trackingService->track('BR123456789BR');
    }
}
