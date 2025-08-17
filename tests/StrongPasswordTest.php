
<?php

use Umii\AdvancedValidator\Rules\StrongPassword;
use PHPUnit\Framework\TestCase;

class StrongPasswordTest extends TestCase
{
    public function test_strong_password_rule()
    {
        $rule = (new StrongPassword())->setParams([8]);
        $this->assertTrue($rule->passes('password', 'Aa1!aaaa'));
        $this->assertFalse($rule->passes('password', 'weakpass'));
    }
}
