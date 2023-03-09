<?php

declare(strict_types=1);

namespace tbclla\Revolut\Console\Commands;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Console\Command;
use tbclla\Revolut\Auth\ClientAssertion;
use tbclla\Revolut\Exceptions\ConfigurationException;
use Throwable;

class JWTCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revolut:jwt {--public= : The path to your public key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate a JSON Web Token for Revolut's Oauth process.";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // build the JWT
        try {
            $jwt = $this->buildJWT();
        } catch (ConfigurationException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->info('Your JSON web token was created successfully:');
        $this->info('<fg=black;bg=yellow>' . $jwt . '</>');

        // optionally, verify the key
        $key = $this->checkPublicKey($this->option('public') ?? null);

        try {
            $decoded = JWT::decode($jwt, new Key($key, ClientAssertion::ALGORYTHM));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $headers = ['parameter', 'value'];
        $data = [
            ['issuer', $decoded->iss],
            ['subject', $decoded->sub],
            ['expiry', $decoded->exp],
            ['audience', $decoded->aud],
        ];

        $this->info('Your JWT has been verified and is valid.');
        $this->table($headers, $data);
    }

    /**
     * @return string
     * @throws ConfigurationException
     */
    private function buildJWT(): string
    {
        /** @var ClientAssertion $clientAssertion */
        $clientAssertion = resolve(ClientAssertion::class);

        return $clientAssertion->build();
    }

    /**
     * @return string
     */
    private function checkPublicKey($key = null): string
    {
        try {
            return file_get_contents($key ?? $this->ask('If you want to validate this JWT, enter the path to your public key'));
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return $this->checkPublicKey();
        }
    }
}
