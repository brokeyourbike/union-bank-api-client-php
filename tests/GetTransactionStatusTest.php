<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\UnionBank\Models\GetTransactionStatusResponse;
use BrokeYourBike\UnionBank\Interfaces\TransactionInterface;
use BrokeYourBike\UnionBank\Interfaces\ConfigInterface;
use BrokeYourBike\UnionBank\Enums\PaymentStatusEnum;
use BrokeYourBike\UnionBank\Enums\ErrorCodeEnum;
use BrokeYourBike\UnionBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GetTransactionStatusTest extends TestCase
{
    private string $token = 'secure-token';
    private string $remoteReference = 'REF-1234';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getRemoteReference')->willReturn($this->remoteReference);

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "responseCode": "'. ErrorCodeEnum::SUCCESS->value .'",
                "responseMessage": "Status successfully gotten",
                "transactionReference": "'. $this->remoteReference .'",
                "transactionStatus": "'. PaymentStatusEnum::SUCCESSFUL->value .'"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'GET',
            'https://api.example/transactions/getStatus',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::QUERY => [
                    'transactionReference' => $this->remoteReference,
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn($this->token);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        /**
         * @var TransactionInterface $transaction
         */
        $requestResult = $api->getTransactionStatus($transaction);

        $this->assertInstanceOf(GetTransactionStatusResponse::class, $requestResult);
        $this->assertSame(ErrorCodeEnum::SUCCESS->value, $requestResult->responseCode);
        $this->assertSame("Status successfully gotten", $requestResult->responseMessage);
        $this->assertSame(PaymentStatusEnum::SUCCESSFUL->value, $requestResult->transactionStatus);
    }
}
