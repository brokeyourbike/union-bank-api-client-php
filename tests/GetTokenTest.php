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
class GetTokenTest extends TestCase
{
    private string $tokenValue = 'super-secure-token';

    /** @test */
    public function it_can_cache_and_return_auth_token()
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        $mockedResponse = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "access_token": "'. $this->tokenValue .'",
                "scope": "read write",
                "token_type": "Bearer",
                "expires_in": 600000
            }');

        /** @var \GuzzleHttp\Client $mockedClient */
        $mockedClient = $this->createPartialMock(\GuzzleHttp\Client::class, ['request']);
        $mockedClient->method('request')->willReturn($mockedResponse);

        /** @var \Mockery\MockInterface $mockedCache */
        $mockedCache = \Mockery::spy(CacheInterface::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $requestResult = $api->getToken();

        $this->assertSame($this->tokenValue, $requestResult);

        /** @var \Mockery\MockInterface $mockedCache */
        $mockedCache->shouldHaveReceived('set')
            ->once()
            ->with(
                $api->authTokenCacheKey(),
                $this->tokenValue,
                60
            );
    }

    /** @test */
    public function it_can_return_cached_value()
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldNotReceive('request');

        /** @var \Mockery\MockInterface $mockedCache */
        $mockedCache = \Mockery::mock(CacheInterface::class);
        $mockedCache->shouldReceive('has')
            ->once()
            ->andReturn(true);
        $mockedCache->shouldReceive('get')
            ->once()
            ->andReturn($this->tokenValue);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $requestResult = $api->getToken();

        $this->assertSame($this->tokenValue, $requestResult);
    }
}
