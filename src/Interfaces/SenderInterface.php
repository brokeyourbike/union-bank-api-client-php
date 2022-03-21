<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\UnionBank\Interfaces;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface SenderInterface
{
    public function getFirstName(): string;
    public function getLastName(): string;
    public function getPhoneNumber(): string;
    public function getCountryCode(): string;
    public function getBankAccount(): string;
    public function getBankCode(): string;
}
