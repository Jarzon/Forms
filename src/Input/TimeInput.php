<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class TimeInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'time');
    }

    public function pattern(?string $pattern = null)
    {
        if($pattern === null) {
            $pattern = '[0-9]{2}:[0-9]{2}';
        }

        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if($this->pattern !== null) {
            $format = str_replace('/', '\/', $this->pattern);
            if(preg_match("/$format/", $value) == 0) {
                throw new \Exception("{$this->name} is not a valid time");
            }
        }

        return true;
    }
}