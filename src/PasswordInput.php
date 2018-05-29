<?php
namespace Jarzon;

class PasswordInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'password');
    }
}