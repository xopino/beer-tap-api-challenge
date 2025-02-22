<?php

namespace App\Dispenser\Application\UseCase\Create;

class CreateDispenserUseCaseResponse
{
    public function __construct(
        public readonly string  $message,
        private readonly bool   $isValid,
        public readonly ?string $dispenserId = null,
        public readonly ?float  $flowVolume = null
    )
    {
    }

    public static function createValidResponse(string $dispenserId, float $flowVolume): static
    {
        $message = 'Dispenser created successfully';

        return new self($message, true, $dispenserId, $flowVolume);
    }

    public static function createInvalidResponse(string $errorMessage): static
    {
        return new self($errorMessage, false);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}
