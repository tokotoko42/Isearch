<?php
/**
 * ExportEntryCommandTest
 *
 * @see ExportEntryCommmand
 * @author KURIHARA Hikonobu (Hymena & Co.)
 */

class ExportEntryCommandTest extends CDbTestCase
{
    const ENTRY_CODE_INDEX = 1;
    const STATUS_INDEX = 2;
    const HEAD_SHOP_CODE_INDEX = 54;
    const EMAIL_PC_INDEX = 39;
    const EMAIL_MOBILE_INDEX = 40;

    const STATUS_ENTRY = 1;
    const STATUS_RE_ENTRY = 5;
    const STATUS_UPDATE = 2;
    const STATUS_CANCEL = 4;

    /**
     * @var array
     * Fixtureのentry_code一覧
     */
    private $entry_codes = array(
        'se_corporate'=>'0000000010',
        'se_personal'=>'1000000020',
        'se_re_corporate'=>'0000000011',
        'se_re_personal'=>'1000000021',
        'se_reject'=>'1000009000',
        'se_wait_image'=>'1000009001',
        'se_export'=>'1000009002',
        'se_re_wait_image'=>'1000009003',
        'se_re_export'=>'1000009004',
        'se_member'=>'1000009005',
        'se_member_s_update'=>'1000009006',
        'se_member_s_wait_cancel'=>'1000009007',
        'se_member_s_cancel_requested'=>'1000009008',
        'se_member_s_cancel_accepted'=>'1000009010',
        'se_member_s_canceled'=>'1000009011',
        's_member'=>'1000009005',
        's_update'=>'1000009006',
        's_wait_cancel'=>'1000009007',
        's_cancel_requested'=>'1000009008',
        's_cancel_accepted'=>'1000009010',
        's_canceled'=>'1000009011',
        'se_member_head'=>'1000009012',
        's_member_head'=>'1000009012',
        'se_member_branch'=>'1000009012001',
        's_member_branch'=>'1000009012001',
        'se_member_branch_wait'=>'1000009012002',
        'se_member_branch_update'=>'1000009012003',
        's_member_branch_update'=>'1000009012003',
        'se_member_branch_cancel'=>'1000009012004',
        's_member_branch_cancel'=>'1000009012004',
        'se_invalid_bank'=>'1000009013',
        'se_invalid_bank_registered'=>'1000009014',
        's_invalid_bank_registered'=>'1000009014',
    );

    /**
     * @var array Fixtures
     */
    public $fixtures = array(
        'shop_entry' => 'ShopEntry',
        'shop' => 'Shop',
    );

    /**
     * test for shop_entry()
     */
    public function testShop_entry()
    {
        $this->assertEquals(ShopEntry::STATUS_WAIT_EXPORT,    $this->shop_entry('se_corporate')->status);
        $this->assertEquals(ShopEntry::STATUS_WAIT_EXPORT,    $this->shop_entry('se_personal')->status);
        $this->assertEquals(ShopEntry::STATUS_WAIT_EXPORT,    $this->shop_entry('se_member_branch_wait')->status);
        $this->assertEquals(ShopEntry::STATUS_WAIT_EXPORT,    $this->shop_entry('se_invalid_bank')->status);
        $this->assertEquals(ShopEntry::STATUS_RE_WAIT_EXPORT, $this->shop_entry('se_re_corporate')->status);
        $this->assertEquals(ShopEntry::STATUS_RE_WAIT_EXPORT, $this->shop_entry('se_re_personal')->status);

        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'shop_entry');
        $method->setAccessible(true);
        $lines = $method->invoke($shell);

        $entry_codes = array(
            $this->entry_codes['se_corporate'],
            $this->entry_codes['se_personal'],
            $this->entry_codes['se_re_corporate'],
            $this->entry_codes['se_re_personal'],
            $this->entry_codes['se_member_branch_wait'],
        );

        $this->assertEquals(count($lines), count($entry_codes));

        foreach ($lines as $line) {
            $this->assertContains($line[self::ENTRY_CODE_INDEX], $entry_codes, sprintf('entry_code: %s', $line[self::ENTRY_CODE_INDEX]));
        }

        $this->shop_entry('se_corporate')->refresh();
        $this->shop_entry('se_personal')->refresh();
        $this->shop_entry('se_member_branch_wait')->refresh();
        $this->shop_entry('se_re_corporate')->refresh();
        $this->shop_entry('se_re_personal')->refresh();
        $this->assertEquals(ShopEntry::STATUS_EXPORT,    $this->shop_entry('se_corporate')->status);
        $this->assertEquals(ShopEntry::STATUS_EXPORT,    $this->shop_entry('se_personal')->status);
        $this->assertEquals(ShopEntry::STATUS_EXPORT,    $this->shop_entry('se_member_branch_wait')->status);
        $this->assertEquals(ShopEntry::STATUS_RE_EXPORT, $this->shop_entry('se_re_corporate')->status);
        $this->assertEquals(ShopEntry::STATUS_RE_EXPORT, $this->shop_entry('se_re_personal')->status);
    }

    /**
     * test for shop()
     */
    public function testShop()
    {
        $this->assertEquals(Shop::STATUS_UPDATE,      $this->shop('s_update')->status);
        $this->assertEquals(Shop::STATUS_WAIT_CANCEL, $this->shop('s_wait_cancel')->status);
        $this->assertEquals(Shop::STATUS_UPDATE,      $this->shop('s_invalid_bank_registered')->status);
        $this->assertEquals(Shop::STATUS_UPDATE,      $this->shop('s_member_branch_update')->status);
        $this->assertEquals(Shop::STATUS_WAIT_CANCEL, $this->shop('s_member_branch_cancel')->status);

        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'shop');
        $method->setAccessible(true);
        $lines = $method->invoke($shell);

        $entry_codes = array(
            $this->entry_codes['s_update'],
            $this->entry_codes['s_wait_cancel'],
            $this->entry_codes['s_member_branch_update'],
            $this->entry_codes['s_member_branch_cancel'],
        );

        $this->assertEquals(count($entry_codes), count($lines));

        foreach ($lines as $line) {
            $this->assertContains($line[self::ENTRY_CODE_INDEX], $entry_codes, sprintf('entry_code: %s', $line[self::ENTRY_CODE_INDEX]));
        }

        $this->shop('s_update')->refresh();
        $this->shop('s_wait_cancel')->refresh();
        $this->shop('s_member_branch_update')->refresh();
        $this->shop('s_member_branch_cancel')->refresh();
        $this->assertEquals(Shop::STATUS_MEMBER,           $this->shop('s_update')->status);
        $this->assertEquals(Shop::STATUS_CANCEL_REQUESTED, $this->shop('s_wait_cancel')->status);
        $this->assertEquals(Shop::STATUS_MEMBER,           $this->shop('s_member_branch_update')->status);
        $this->assertEquals(Shop::STATUS_CANCEL_REQUESTED, $this->shop('s_member_branch_cancel')->status);
    }

    /**
     * test for createLineHead()
     * personal entry
     */
    public function testCreateLineHead_personal()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'createLineHead');
        $method->setAccessible(true);

        $line_personal     = $method->invokeArgs($shell, array($this->shop_entry('se_personal')));
        $line_re_personal  = $method->invokeArgs($shell, array($this->shop_entry('se_re_personal')));

        // 初回申請・再申請のステータスが正しく変換されているか
        $this->assertEquals(self::STATUS_ENTRY,    $line_personal[self::STATUS_INDEX]);
        $this->assertEquals(self::STATUS_RE_ENTRY, $line_re_personal[self::STATUS_INDEX]);
    }

    /**
     * test for createLineHead()
     * corporate entry
     */
    public function testCreateLineHead_corporate()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'createLineHead');
        $method->setAccessible(true);

        $line_corporate    = $method->invokeArgs($shell, array($this->shop_entry('se_corporate')));
        $line_re_corporate = $method->invokeArgs($shell, array($this->shop_entry('se_re_corporate')));

        // 初回申請・再申請のステータスが正しく変換されているか
        $this->assertEquals(self::STATUS_ENTRY,    $line_corporate[self::STATUS_INDEX]);
        $this->assertEquals(self::STATUS_RE_ENTRY, $line_re_corporate[self::STATUS_INDEX]);
    }

    /**
     * test for createLineBranch()
     */
    public function testCreateLineBranch()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'createLineBranch');
        $method->setAccessible(true);

        $line_branch = $method->invokeArgs($shell, array($this->shop_entry('se_member_branch_wait')));

        // 申請のステータスが正しく変換されているか
        $this->assertEquals(self::STATUS_ENTRY, $line_branch[self::STATUS_INDEX]);

        // 本店のショップコードがセットされている
        $this->assertEquals($this->shop('s_member_head')->shop_code, $line_branch[self::HEAD_SHOP_CODE_INDEX]);

        // 本店のメールアドレスがセットされている
        $this->assertEquals($this->shop('s_member_head')->email_pc,     $line_branch[self::EMAIL_PC_INDEX]);
        $this->assertEquals($this->shop('s_member_head')->email_mobile, $line_branch[self::EMAIL_MOBILE_INDEX]);
    }

    public function testCreateUpdateLineHead()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'createUpdateLineHead');
        $method->setAccessible(true);

        $line_update = $method->invokeArgs($shell, array($this->shop('s_update')));
        $line_cancel = $method->invokeArgs($shell, array($this->shop('s_wait_cancel')));

        // 申請のステータスが正しく変換されているか
        $this->assertEquals(self::STATUS_UPDATE, $line_update[self::STATUS_INDEX]);
        $this->assertEquals(self::STATUS_CANCEL, $line_cancel[self::STATUS_INDEX]);
    }

    /**
     * test for createLineBranch()
     */
    public function testCreateUpdateLineBranch()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'createUpdateLineBranch');
        $method->setAccessible(true);


        $line_update = $method->invokeArgs($shell, array($this->shop('s_member_branch_update')));
        $line_cancel = $method->invokeArgs($shell, array($this->shop('s_member_branch_cancel')));

        // 申請のステータスが正しく変換されているか
        $this->assertEquals(self::STATUS_UPDATE, $line_update[self::STATUS_INDEX]);
        $this->assertEquals(self::STATUS_CANCEL, $line_cancel[self::STATUS_INDEX]);

        // 本店のショップコードがセットされている
        $this->assertEquals($this->shop('s_member_head')->shop_code, $line_update[self::HEAD_SHOP_CODE_INDEX]);
        $this->assertEquals($this->shop('s_member_head')->shop_code, $line_cancel[self::HEAD_SHOP_CODE_INDEX]);

        // 本店のメールアドレスがセットされている
        $this->assertEquals($this->shop('s_member_head')->email_pc,     $line_update[self::EMAIL_PC_INDEX]);
        $this->assertEquals($this->shop('s_member_head')->email_mobile, $line_update[self::EMAIL_MOBILE_INDEX]);
        $this->assertEquals($this->shop('s_member_head')->email_pc,     $line_cancel[self::EMAIL_PC_INDEX]);
        $this->assertEquals($this->shop('s_member_head')->email_mobile, $line_cancel[self::EMAIL_MOBILE_INDEX]);
    }

    /**
     * test for run()
     */
    public function testRun()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportEntry';
        $shell = new ExportEntryCommand($commandName, $CCRunner);

        try {
            $shell->run(array());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function __destruct()
    {
        Yii::app()->end();
    }
}
