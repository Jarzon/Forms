<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class CurrencyInput extends DigitBasedInput
{
    public function __construct(string $name, string $inputType, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', $inputType);
        if($inputType === 'number') {
            $this->setAttribute('step', 0.01);
        }
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->value || $this->value !== null;
    }

    public function validation()
    {
        $value = parent::validation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = (float)$v;
            }

            return $value;
        }

        return (float)$value;
    }
}
