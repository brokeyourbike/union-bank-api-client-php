<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use BrokeYourBike\UnionBank\Models\InitiateTransactionResponse;
use BrokeYourBike\UnionBank\Interfaces\TransactionInterface;
use BrokeYourBike\UnionBank\Interfaces\SenderInterface;
use BrokeYourBike\UnionBank\Interfaces\RecipientInterface;
use BrokeYourBike\UnionBank\Interfaces\ConfigInterface;
use BrokeYourBike\UnionBank\Enums\ErrorCodeEnum;
use BrokeYourBike\UnionBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class InitiateTransactionTest extends TestCase
{
    private string $token = 'secure-token';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $sender->method('getFirstName')->willReturn('John');
        $sender->method('getLastName')->willReturn('Doe');
        $sender->method('getPhoneNumber')->willReturn('+12345');
        $sender->method('getCountryCode')->willReturn('GBP');
        $sender->method('getBankAccount')->willReturn('234234');
        $sender->method('getBankCode')->willReturn('032');

        $recipient = $this->getMockBuilder(RecipientInterface::class)->getMock();
        $recipient->method('getName')->willReturn('Jane Doe');
        $recipient->method('getCountryCode')->willReturn('NGA');
        $recipient->method('getPhoneNumber')->willReturn('+789000');
        $recipient->method('getEmail')->willReturn('r@ex.com');
        $recipient->method('getBankAccount')->willReturn('789000');
        $recipient->method('getBankCode')->willReturn('033');

        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($sender);
        $transaction->method('getRecipient')->willReturn($recipient);
        $transaction->method('getReference')->willReturn('ref-1234');
        $transaction->method('getCurrencyCode')->willReturn('USD');
        $transaction->method('getAmount')->willReturn(100.03);
        $transaction->method('getDate')->willReturn(Carbon::create(2020, 1, 1, 15, 30, 45));

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getMerchantCode')->willReturn('MERCH');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "responseCode": "'. ErrorCodeEnum::SUCCESS->value .'",
                "responseMessage": "Transaction completed successfully",
                "transactionReference": "remote-ref-456",
                "errors": null
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/transactions/initiate',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'pin' => 'ref-1234',
                    'merchantCode' => 'MERCH',
                    'merchantTransactionDate' => '2020-01-01',
                    'merchantTransactionTime' => '15:30:45',
                    'beneficiaryCountry' => 'NGA',
                    'payerlocationCode' => '',
                    'relationship' => '',
                    'beneficiaryQuestion' => '',
                    'beneficiaryAnswer' => '',
                    'beneficiaryCurrency' => 'USD',
                    'beneficiaryAmount' => 100.03,
                    'deliveryMethod' => '',
                    'originalAmount' => 100.03,
                    'originalCurrency' => 'USD',
                    'originalTransactionCharge' => '',
                    'settlementTransactionCharge' => '',
                    'settlementCurrency' => '',
                    'settlementAmount' => '',
                    'beneficiaryEmail' => 'r@ex.com',
                    'beneficiaryAccountNumber' => '789000',
                    'beneficiaryAccountName' => 'Jane Doe',
                    'beneficiaryType' => '',
                    'beneficiaryAddressLine1' => '',
                    'beneficiaryAddressLine2' => '',
                    'beneficiaryAddressLine3' => '',
                    'beneficiaryCity' => '',
                    'beneficiaryState' => '',
                    'beneficiaryZipCode' => '',
                    'beneficiaryPhoneNo' => '+789000',
                    'beneficiaryIdType' => '',
                    'beneficiaryIdNo' => '',
                    'beneficiaryIdIssueDate' => '',
                    'beneficiaryIdExpirationDate' => '',
                    'beneficiaryIdDOB' => '',
                    'beneficiaryOccupation' => '',
                    'beneficiaryMessage' => '',
                    'beneficiaryBranchCode' => '',
                    'beneficiaryBankCity' => '',
                    'beneficiaryBankCode' => '033',
                    'beneficiaryBankRoutingCode' => '',
                    'beneficiaryBICSwift' => '',
                    'senderId' => '',
                    'senderFirstName' => 'John',
                    'senderMiddleName' => '',
                    'senderLastName' => 'Doe',
                    'senderEmail' => '',
                    'senderOccupation' => '',
                    'senderCountry' => 'GBP',
                    'senderType' => '',
                    'senderCity' => '',
                    'senderState' => '',
                    'senderAddressLine1' => '',
                    'senderAddressLine2' => '',
                    'senderAddressLine3' => '',
                    'senderZipCode' => '',
                    'senderPhoneNo' => '+12345',
                    'senderBankBranchId' => '',
                    'senderBankBranchName' => '',
                    'senderBankBranchCode' => '',
                    'senderNationality' => '',
                    'senderDOB' => '',
                    'senderSourceOfFunds' => '',
                    'senderPaymentMethod' => '',
                    'senderTaxId' => '',
                    'senderTaxCountry' => '',
                    'senderAccountNumber' => '234234',
                    'senderBankCode' => '032',
                    'rate' => '',
                    'transferReason' => 'ref-1234',
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
        $requestResult = $api->initiateTransaction($transaction);

        $this->assertInstanceOf(InitiateTransactionResponse::class, $requestResult);
        $this->assertSame(ErrorCodeEnum::SUCCESS->value, $requestResult->responseCode);
        $this->assertSame("Transaction completed successfully", $requestResult->responseMessage);
        $this->assertSame("remote-ref-456", $requestResult->transactionReference);
    }
}
