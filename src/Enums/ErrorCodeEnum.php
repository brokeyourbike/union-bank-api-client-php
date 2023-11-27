<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum ErrorCodeEnum: string
{
    case SUCCESS = '00';
    case PROCESSED = '06';
    case RECIPIENT_ACCOUNT_INVALID = '07';
    case BENEFICIARY_AND_ORIGINAL_AMOUNT_MUST_BE_SAME = '23';
    case FORMAT_ERROR = '30';
    case UNPROCESSIBLE_REQUEST = '92';
    case MISMATCHED_OR_NOT_TRANSFERABLE_CURRENCIES = '93';
    case CANNOT_TRANSFER_NGN_TO_A_NON_NGN_ACCOUNT = '94';
    case HASH_VALUE_INVALID = '95';
    case DUPLICATE_TRANSACTION_PIN = '96';
    case MERCHANT_CODE_INVALID = '97';
    case OTHERS_TYPES_OF_ERRORS = '99';
}
