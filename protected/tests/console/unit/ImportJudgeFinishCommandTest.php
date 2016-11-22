<?php

class ImportJudgeFinishCommandTest extends CDbTestCase
{
    public $fixtures = array(
        'shop_entry' => 'ShopEntry',
        'shop'       => 'Shop',
    );

    private $test_files = array(
        'KC_RSP_CARDJUDGE_20120903143403.CSV.sample'
    );

    /**
     * @covers ImportJudgeFinishCommand
     */
    public function testRun()
    {
        $this->deleteTestFile();
        $this->prepareTestFile();

        $this->assertNull(Shop::model()->find('entry_code=?', array('1000009002')));

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'ImportJudgeFinish';
        $shell = new ImportJudgeFinishCommand($commandName, $CCRunner);
        $shell->run(array());

        $dest_path = Yii::app()->params['cardjudge_path'];
        $dh = opendir($dest_path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $this->fail(sprintf("file '%s' is not deleted", $file));
        }

        $this->assertNotNull(Shop::model()->find('entry_code=?', array('1000009002')));
    }

    private function deleteTestFile()
    {
        $dest_path = Yii::app()->params['cardjudge_path'];
        $dh = opendir($dest_path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $file = realpath($dest_path . $file);
            if (!$file || !is_file($file)) {
                continue;
            }
            Yii::log(sprintf('delete: %s', $file), 'test', 'prepare');
            unlink($file);
        }
    }

    private function prepareTestFile()
    {
        $source_path = Yii::app()->params['test_file_path'];
        $dest_path = Yii::app()->params['cardjudge_path'];

        foreach ($this->test_files as $file) {
            $source_file = realpath($source_path . $file);
            $dest_file   = $dest_path . preg_replace('|\.sample$|', '', $file);
            if (!is_dir($dest_path) || !is_writable($dest_path)) {
                $this->fail(sprintf("test dir '%s' is not writable."));
            }
            if (!$source_file || !is_file($source_file)) {
                continue;
            }
            Yii::log(sprintf('copy: %s => %s', $source_file, $dest_file), 'test', 'prepare');
            copy($source_file, $dest_file);
        }
    }

    public function __destruct()
    {
        Yii::app()->end();
    }
}
