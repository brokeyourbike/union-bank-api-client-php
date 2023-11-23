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
    /**
     * Completed Successfully
     */
    case SUCCESS = '00';

    /**
     * Format Error
     */
    case FORMAT_ERROR = '30';

    /**
     * System Malfunction
     */
    case SYSTEM_MALFUNCTION = '96';

    /**
     * Invalid Sender Account
     */
    case INVALID_SENDER_ACCOUNT = '07';
}
