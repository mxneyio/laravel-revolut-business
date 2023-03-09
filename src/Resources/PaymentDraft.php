<?php

declare(strict_types=1);

namespace tbclla\Revolut\Resources;

use tbclla\Revolut\Builders\PaymentDraftBuilder;
use tbclla\Revolut\Interfaces\Buildable;

class PaymentDraft extends Resource implements Buildable
{
    /**
     * The enpoint for payment draft requests
     * 
     * @var string
     */
    const ENDPOINT = '/payment-drafts';

    /**
     * @see https://revolut-engineering.github.io/api-docs/business-api/#payment-drafts-create-a-payment-draft Official API documentation
     */
    public function create(array $json)
    {
        return $this->client->post(self::ENDPOINT, ['json' => $json]);
    }

    /**
     * Get all payment drafts
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#get-payment-drafts Official API documentation
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function all()
    {
        return $this->client->get(self::ENDPOINT);
    }

    /**
     * Get a payment draft by its ID
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#get-payment-drafts-get-payment-draft-by-id Official API documentation
     * @param string $id The ID of the payment draft in UUID format
     * @return array The response body
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function get(string $id)
    {
        return $this->client->get(self::ENDPOINT . '/' . $id);
    }

    /**
     * Delete a payment draft by its ID
     * 
     * @see https://revolut-engineering.github.io/api-docs/business-api/#get-payment-drafts-delete-payment-draft Official API documentation
     * @param string $id The ID of the payment draft in UUID format
     * @return void
     * @throws \tbclla\Revolut\Exceptions\ApiException if the client responded with a 4xx-5xx response
     * @throws \tbclla\Revolut\Exceptions\AppUnauthorizedException if the app needs to be re-authorized
     */
    public function delete(string $id) : void
    {
        $this->client->delete(self::ENDPOINT . '/' . $id);
    }

    /**
     * @return \tbclla\Revolut\Builders\PaymentDraftBuilder
     */
    public function build()
    {
        return new PaymentDraftBuilder($this);
    }
}
