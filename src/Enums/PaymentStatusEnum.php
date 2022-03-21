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
enum PaymentStatusEnum: string
{
    /**
     * Reversal Retry
     */
    case REVERSAL_RETRY = '-13';

    /**
     * Account Verification Retry
     */
    case ACCOUNT_VERIFICATION_RETRY = '-01';

    /**
     * Posting Retry
     */
    case POSTING_RETRY = '-03';

    /**
     * Name Enquiry Retry
     */
    case NAME_ENQUIRY_RETRY = '-02';

    /**
     * Interbank Transfer Retry
     */
    case INTERBANK_TRANSFER_RETRY = '-04';

    /**
     * Transaction Reversal Failed
     */
    case TRANSACTION_REVERSAL_FAILED = '12';

    /**
     * Download Acknowlegement Completed and Awaiting Processing
     */
    case DOWNLOAD_ACKNOWLEGEMENT_COMPLETED_AND_AWAITING_PROCESSING = '-2';

    /**
     * Pending Account Verification
     */
    case PENDING_ACCOUNT_VERIFICATION = '01';

    /**
     * Pending Name Enquiry
     */
    case PENDING_NAME_ENQUIRY = '02';

    /**
     * Pending Posting
     */
    case PENDING_POSTING = '03';

    /**
     * Pending Interbank Transfer
     */
    case PENDING_INTERBANK_TRANSFER = '04';

    /**
     * Pending Notification Back to smallworld
     */
    case PENDING_NOTIFICATION_BACK_TO_SMALLWORLD = '05';

    /**
     * Processing Completed and Successful
     */
    case SUCCESSFUL = '06';

    /**
     * Processing Completed and Failed
     */
    case FAILED = '07';

    /**
     * Awaiting Manual Posting
     */
    case AWAITING_MANUAL_POSTING = '08';

    /**
     * Awaiting Manual InterBank Name Enquiry
     */
    case AWAITING_MANUAL_INTERBANK_NAME_ENQUIRY = '09';

    /**
     * Awaiting Manual InterBank Fund Transfer
     */
    case AWAITING_MANUAL_INTERBANK_FUND_TRANSFER = '10';

    /**
     * Awaiting Posting Reversal
     */
    case AWAITING_POSTING_REVERSAL = '13';

    /**
     * Fund Transfer Timeout and awaiting manual confirmation
     */
    case FUND_TRANSFER_TIMEOUT = '14';

    /**
     * Awaiting Download StatusFeedback
     */
    case AWAITING_DOWNLOAD_STATUSFEEDBACK = '-1';

    /**
     * Order Cancelled
     */
    case ORDER_CANCELLED = '11';

    /**
     * Account Validation Failed and Awaiting Manual Confirmation
     */
    case ACCOUNT_VALIDATION_FAILED = '15';

    /**
     * Payment Successful but Smallworld Rejected Feedback.
     * Please follow up with RIA for Settlement.
     */
    case PAYMENT_SUCCESSFUL_BUT_SMALLWORLD_REJECTED_FEEDBACK = '100';
}
