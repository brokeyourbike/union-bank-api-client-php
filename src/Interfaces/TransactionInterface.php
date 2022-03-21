<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Interfaces;

use BrokeYourBike\UnionBank\Enums\PaymentTypeEnum;
use BrokeYourBike\UnionBank\Enums\PaymentMethodEnum;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface TransactionInterface
{
    public function getReference(): string;
    public function getRemoteReference(): ?string;
    public function getSender(): ?SenderInterface;
    public function getRecipient(): ?RecipientInterface;
    public function getAmount(): float;
    public function getCurrencyCode(): string;
    public function getDate(): \DateTimeInterface;
}
