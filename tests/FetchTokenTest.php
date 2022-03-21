<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\UnionBank\Models\FetchTokenResponse;
use BrokeYourBike\UnionBank\Interfaces\ConfigInterface;
use BrokeYourBike\UnionBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchTokenTest extends TestCase
{
    private string $username = 'john';
    private string $password = 'secure-password';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "access_token": "super-secure-token",
                "scope": "read write",
                "token_type": "Bearer",
                "expires_in": 600000
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::AUTH => [
                    $this->username,
                    $this->password,
                ],
                \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'client_credentials',
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $requestResult = $api->fetchToken();

        $this->assertInstanceOf(FetchTokenResponse::class, $requestResult);
        $this->assertSame('super-secure-token', $requestResult->accessToken);
        $this->assertSame(600000, $requestResult->expiresIn);
    }
}
