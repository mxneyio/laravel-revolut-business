<?php

namespace tbclla\Revolut\Tests;

use tbclla\Revolut\Auth\AccessToken;
use tbclla\Revolut\Auth\TokenManager;
use tbclla\Revolut\Client;
use tbclla\Revolut\GuzzleHttpClient;
use tbclla\Revolut\Resources\Transfer;

class TransferTest extends TestCase
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->artisan('migrate')->run();

		// Create a mock Http Client
		$this->mockHttpClient = $this->mock(GuzzleHttpClient::class);
		// instantiante a Revolut client with the mock Http client
		$this->revolutClient = new Client(resolve(TokenManager::class), $this->mockHttpClient);
		// make sure we have an access token available
		$this->accessToken = AccessToken::create(['value' => 'sample_access_token']);
	}

	/** @test */
	public function a_transfer_can_be_created()
	{
		$data = [
			'request_id' => 'e0cbf84637264ee082a848b',
			'source_account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'target_account_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Expenses funding'
		];

		$this->mockHttpClient->shouldReceive('post')->withArgs([
			$this->revolutClient->buildUri(Transfer::ENDPOINT),
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $this->accessToken->value
				],
				'json' => $data,
			]
		]);

		$this->revolutClient->transfer()->create($data);
	}

	/** @test */
	public function a_transfer_can_be_built()
	{
		$transfer = $this->revolutClient->transfer()->build()
			->sourceAccount('bdab1c20-8d8c-430d-b967-87ac01af060c')
			->targetAccount('5138z40d1-05bb-49c0-b130-75e8cf2f7693')
			->amount(123.11)
			->currency('EUR')
			->reference('Expenses funding');

		$this->assertEquals([
			'request_id' => 'e0cbf84637264ee082a848b',
			'source_account_id' => 'bdab1c20-8d8c-430d-b967-87ac01af060c',
			'target_account_id' => '5138z40d1-05bb-49c0-b130-75e8cf2f7693',
			'amount' => 123.11,
			'currency' => 'EUR',
			'reference' => 'Expenses funding',
			'request_id' => $transfer->request_id,
		], $transfer->toArray());
	}
}
