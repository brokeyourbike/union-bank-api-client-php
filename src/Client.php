<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\UnionBank\Models\NameEnquiryResponse;
use BrokeYourBike\UnionBank\Models\InitiateTransactionResponse;
use BrokeYourBike\UnionBank\Models\GetTransactionStatusResponse;
use BrokeYourBike\UnionBank\Models\FetchTokenResponse;
use BrokeYourBike\UnionBank\Interfaces\TransactionInterface;
use BrokeYourBike\UnionBank\Interfaces\ConfigInterface;
use BrokeYourBike\UnionBank\Enums\AccountTypeEnum;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;

    private ConfigInterface $config;
    private CacheInterface $cache;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient, CacheInterface $cache)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    public function authTokenCacheKey(): string
    {
        return get_class($this) . ':authToken:';
    }

    public function getToken(): string
    {
        if ($this->cache->has($this->authTokenCacheKey())) {
            $cachedToken = $this->cache->get($this->authTokenCacheKey());
            if (is_string($cachedToken)) {
                return $cachedToken;
            }
        }

        $response = $this->fetchToken();

        $this->cache->set(
            $this->authTokenCacheKey(),
            $response->accessToken,
            $response->expiresIn
        );

        return $response->accessToken;
    }

    public function fetchToken(): FetchTokenResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            \GuzzleHttp\RequestOptions::AUTH => [
                $this->config->getUsername(),
                $this->config->getPassword(),
            ],
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'client_credentials',
            ],
        ];

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $this->config->getAuthUrl());

        $response = $this->httpClient->request(
            HttpMethodEnum::POST->value,
            $uri,
            $options
        );

        return new FetchTokenResponse($response);
    }

    public function nameEnquiry(string $bankCode, string $accountNumber, ?AccountTypeEnum $accountType = null): NameEnquiryResponse
    {
        $data = [
            'accountNumber' => $accountNumber,
            'destinationBankCode' => $bankCode,
        ];

        if ($accountType !== null) {
            $data['accountType'] = $accountType->value;
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'enquiry/nameEnquiry', $data);
        return new NameEnquiryResponse($response);
    }

    public function getTransactionStatus(TransactionInterface $transaction): GetTransactionStatusResponse
    {
        $response = $this->performRequest(HttpMethodEnum::GET, 'transactions/getStatus', [
            'transactionReference' => $transaction->getRemoteReference(),
        ]);
        return new GetTransactionStatusResponse($response);
    }

    public function initiateTransaction(TransactionInterface $transaction): InitiateTransactionResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'transactions/initiate', [
            'pin' => $transaction->getReference(),
            'merchantCode' => $this->config->getMerchantCode(),
            'merchantTransactionDate' => $transaction->getDate()->format('Y-m-d'),
            'merchantTransactionTime' => $transaction->getDate()->format('H:i:s'),
            'beneficiaryCountry' => $transaction->getRecipient()?->getCountryCode(),
            'payerlocationCode' => '',
            'relationship' => '',
            'beneficiaryQuestion' => '',
            'beneficiaryAnswer' => '',
            'beneficiaryCurrency' => $transaction->getCurrencyCode(),
            'beneficiaryAmount' => $transaction->getAmount(),
            'deliveryMethod' => '',
            'originalAmount' => $transaction->getAmount(),
            'originalCurrency' => $transaction->getCurrencyCode(),
            'originalTransactionCharge' => '',
            'settlementTransactionCharge' => '',
            'settlementCurrency' => '',
            'settlementAmount' => '',
            'beneficiaryEmail' => $transaction->getRecipient()?->getEmail(),
            'beneficiaryAccountNumber' => $transaction->getRecipient()?->getBankAccount(),
            'beneficiaryAccountName' => $transaction->getRecipient()?->getName(),
            'beneficiaryType' => '',
            'beneficiaryAddressLine1' => '',
            'beneficiaryAddressLine2' => '',
            'beneficiaryAddressLine3' => '',
            'beneficiaryCity' => '',
            'beneficiaryState' => '',
            'beneficiaryZipCode' => '',
            'beneficiaryPhoneNo' => $transaction->getRecipient()?->getPhoneNumber(),
            'beneficiaryIdType' => '',
            'beneficiaryIdNo' => '',
            'beneficiaryIdIssueDate' => '',
            'beneficiaryIdExpirationDate' => '',
            'beneficiaryIdDOB' => '',
            'beneficiaryOccupation' => '',
            'beneficiaryMessage' => '',
            'beneficiaryBranchCode' => '',
            'beneficiaryBankCity' => '',
            'beneficiaryBankCode' => $transaction->getRecipient()?->getBankCode(),
            'beneficiaryBankRoutingCode' => '',
            'beneficiaryBICSwift' => '',
            'senderId' => '',
            'senderFirstName' => $transaction->getSender()?->getFirstName(),
            'senderMiddleName' => '',
            'senderLastName' => $transaction->getSender()?->getLastName(),
            'senderEmail' => '',
            'senderOccupation' => '',
            'senderCountry' => $transaction->getSender()?->getCountryCode(),
            'senderType' => '',
            'senderCity' => '',
            'senderState' => '',
            'senderAddressLine1' => '',
            'senderAddressLine2' => '',
            'senderAddressLine3' => '',
            'senderZipCode' => '',
            'senderPhoneNo' => $transaction->getSender()?->getPhoneNumber(),
            'senderBankBranchId' => '',
            'senderBankBranchName' => '',
            'senderBankBranchCode' => '',
            'senderNationality' => '',
            'senderDOB' => '',
            'senderSourceOfFunds' => '',
            'senderPaymentMethod' => '',
            'senderTaxId' => '',
            'senderTaxCountry' => '',
            'senderAccountNumber' => $transaction->getSender()?->getBankAccount(),
            'senderBankCode' => $transaction->getSender()?->getBankCode(),
            'rate' => '',
            'transferReason' => $transaction->getReference(),
        ]);
        return new InitiateTransactionResponse($response);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->getToken()}"
            ],
        ];

        $option = match ($method) {
            HttpMethodEnum::GET => \GuzzleHttp\RequestOptions::QUERY,
            default => \GuzzleHttp\RequestOptions::JSON,
        };

        $options[$option] = $data;

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }
}
