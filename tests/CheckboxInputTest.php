<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class CheckboxInputTest extends TestCase
{
    public function testCheckboxChecked()
    {
        $forms = new Forms(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->verification();

        $this->assertEquals('testy', $params['test']);
    }

    public function testCheckboxUnchecked()
    {
        $forms = new Forms([]);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->verification();

        $this->assertEquals(false, $params['test']);
    }

    public function testCheckboxCheckedBool()
    {
        $forms = new Forms(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->verification();

        $this->assertEquals(true, $params['test']);
    }

    public function testCheckboxUncheckedBool()
    {
        $forms = new Forms([]);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->verification();

        $this->assertEquals(false, $params['test']);
    }

    public function testGetFormsCheckbox()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->checkbox('test')
            ->value('test')
            ->selected();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="checkbox" value="test" checked>', $content['test']['html']);
    }

    public function testUpdateValuesCheckbox()
    {
        $forms = new Forms(['fruits' => 'apples']);

        $forms
            ->checkbox('fruits')
            ->value('apples');

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples">', $content['fruits']['html']);

        $forms->verification();

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples" checked>', $content['fruits']['html']);
    }
}