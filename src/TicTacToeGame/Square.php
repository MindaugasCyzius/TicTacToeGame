<?php

namespace App\TicTacToeGame;

class Square
{
    private $value;

    /**
     * Square constructor.
     */
    public function __construct()
    {
        $this->value = null;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @throws TicTacToeException
     */
    public function setValue($value): void
    {
        if (!$this->isAvailable()) {
            throw new TicTacToeException('Failed to set value.');
        }

        $this->value = $value;
    }

    public function serialize(): ?string
    {
        return $this->value;
    }

    public function isAvailable(): bool
    {
        return $this->value == null;
    }
}
