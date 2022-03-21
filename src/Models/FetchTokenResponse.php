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
class FetchTokenResponse extends JsonResponse
{
    #[MapFrom('access_token')]
    public string $accessToken;

    public ?string $scope;

    #[MapFrom('token_type')]
    public ?string $tokenType;

    #[MapFrom('expires_in')]
    public int $expiresIn;
}
