<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Models;

use Spatie\DataTransferObject\Attributes\MapFrom;
use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class NameEnquiryResponse extends JsonResponse
{
    #[MapFrom('code')]
    public string $responseCode;

    #[MapFrom('message')]
    public string $responseMessage;

    public ?string $accountNumber;
    public ?string $accountType;
    public ?string $accountName;
    public ?string $accountBranchCode;
    public ?string $customerNumber;
    public ?string $accountClass;
    public ?string $accountCurrency;
    public ?string $reference;
    public ?string $bankVerificationNumber;
    public ?string $kycLevel;
    public ?string $sessionId;
}
