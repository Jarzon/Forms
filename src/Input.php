<?php
namespace Jarzon;

class Input extends Tag
{
    /** @var Form */
    protected $form;

    public $name = '';
    protected $value = null;
    protected $label = null;
    protected $isRequired = false;
    protected $labelHtml = null;
    public $class = null;

    protected $min = null;
    protected $max = null;

    protected $pattern = null;

    protected $isLabelGenerated = false;

    public function __construct(string $name, $form)
    {
        $this->form = $form;

        $this->setTag('input');
        $this->setName($name);
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if(method_exists($this, $method)) {
            return $this->$method();
        }
    }

    public function setName(string $name)
    {
        $this->setAttribute('name', $name);

        if($this->form->repeat) {
            $name = str_replace('[]', '', $name);
        }

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function class(?string $classes = null)
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->class = $classes;

        $this->setAttribute('class', $classes);

        return $this;
    }

    public function id(?string $id = null)
    {
        if($id === null) $id = $this->name;

        $this->setAttribute('id', $id);

        return $this;
    }

    public function generateLabel()
    {
        $label = '';
        if($this->label !== null) {
            $this->labelHtml = $this->generateTag('label', ['for' => $this->name], $this->label);
        }

        $this->isLabelGenerated = true;

        return $label;
    }

    public function getRow()
    {
        return $this->getLabel().$this->getHtml();
    }

    public function getLabel()
    {
        if(!$this->isLabelGenerated()) $this->generateLabel();
        return $this->labelHtml;
    }

    public function label($label = null)
    {
        if($label !== null) {
            $this->id();
        }

        $this->label = $label;

        $this->resetIsLabelGenerated();

        return $this;
    }

    public function isLabelGenerated()
    {
        return $this->isLabelGenerated;
    }

    public function resetIsLabelGenerated()
    {
        $this->isLabelGenerated = false;
    }

    public function value($value = '')
    {
        $this->value = $value;

        $this->setAttribute('value', htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE));

        return $this;
    }

    public function placeholder(?string $placeholder = null)
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function spellcheck(?bool $placeholder = null)
    {
        $this->setAttribute('spellcheck', ($placeholder) ? 'true': 'false');

        return $this;
    }

    public function autocomplete(?string $value = null)
    {
        $this->setAttribute('autocomplete', $value);

        return $this;
    }

    public function tabindex(?int $index = null)
    {
        $this->setAttribute('tabindex', $index);

        return $this;
    }

    public function pattern(?string $pattern = null)
    {
        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);

        return $this;
    }

    public function required(bool $required = true)
    {
        $this->setAttribute('required', null);
        $this->isRequired = true;

        return $this;
    }

    public function passValidation($value) : bool
    {
        return true;
    }

    public function isUpdated($value) : bool
    {
        $updated = false;

        if($value !== $this->value || ($this->value !== null && !$this->form->update)) {
            $updated = true;
        }

        return $updated;
    }

    public function getPostValue()
    {
        return $this->form->post[$this->name]?? null;
    }

    public function validation()
    {
        $update = $this->form->update;
        $value = $this->getPostValue();

        if($this->form->repeat) {

            $values = [];
            // Iterate over the column for the current $input
            $n = 0;
            foreach($value as $v) {
                if(!isset($v[$n])) {
                    $values[] = [];
                }

                if($v == '' && $this->isRequired) {
                    throw new ValidationException("{$this->name} is required");
                }
                else if($v !== '') {
                    $this->passValidation($v);
                }

                $values[$n] = $v;

                $n++;
            }

            return $values;
        }

        if($value == '' && $this->isRequired) {
            throw new ValidationException("{$this->name} is required");
        }
        else if($value !== '') {
            $this->passValidation($value);
        }

        $updated = $this->isUpdated($value);

        if($updated) {
            $this->value($value);
        }

        if($updated || ($this->isRequired && !$update)) {
            return $value;
        }

        return null;
    }

    public function __call($name, $arguments)
    {
        throw new \Exception("Illegal $name attribute on $this->name");
    }
}