<?php

class UpdateBankCommandTest extends CDbTestCase
{
    public $fixtures = array(
        'blank'  => 'Bank',
        'blank2' => 'BankBranch',
    );

    /**
     * @covers UpdateBankCommand
     */
    public function testRun()
    {
        $this->assertEquals(0, Bank::model()->count());
        $this->assertEquals(0, BankBranch::model()->count());

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'UpdateBank';
        $shell = new UpdateBankCommand($commandName, $CCRunner);
        $shell->run(array());

        $this->assertNotEquals(0, Bank::model()->count());
        $this->assertNotEquals(0, BankBranch::model()->count());
    }

    public function __destruct()
    {
        Yii::app()->end();
    }
}
