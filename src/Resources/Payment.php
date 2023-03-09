<?php

declare(strict_types=1);

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Builders\PaymentBuilder;
use tbclla\Revolut\Interfaces\Buildable;

class Payment extends Resource implements Buildable
{
    /**
     * The enpoint for payment requests
     * 
     * @var string
     */
    const ENDPOINT = '/pay';

    /**
     * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-create-payment Official API documentation
     */
    public function create(array $json)
    {
        return $this->client->post(self::ENDPOINT, ['json' => $json]);
    }

    /**
     * Schedule a payment for up to 30 days
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-schedule-payment Official API documentation
     * @param array $json The request parameters
     * @param string $date a future ISO date (Up to 30 days)
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function schedule(array $json, string $date)
    {
        return $this->create(array_merge($json, ['schedule_for' => $date]));
    }

    /**
     * Cancel a scheduled payment
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#payments-cancel-payment Official API documentation
     * @param string $id The ID of the payment in UUID format
     * @return void
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function cancel(string $id) : void
    {
        $this->client->delete('/transaction/' . $id);
    }
    
    /**
     * @return \tbclla\Revolut\Builders\PaymentBuilder
     */
    public function build()
    {
        return new PaymentBuilder($this, $this->client->generateRequestId());
    }
}
