<?php

class ImportJudgeStatusCommandTest extends CDbTestCase
{
    public $fixtures = array(
        'shop' => 'Shop',
    );

    private $test_files = array(
        'RSP_SHINSAJOKYO_001.CSV',
    );

    /**
     * @covers ImportJudgeStatusCommand
     */
    public function testRun()
    {
        $this->deleteTestFile();
        $this->prepareTestFile();

        $this->assertEquals(Shop::STATUS_CANCEL_REQUESTED, $this->shop('s_cancel_requested')->status);

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'ImportJudgeStatus';
        $shell = new ImportJudgeStatusCommand($commandName, $CCRunner);
        $shell->init();
        $shell->run(array());

        $dest_path = Yii::app()->params['cardjudge_path'];
        $dh = opendir($dest_path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $this->fail(sprintf("file '%s' is not deleted", $file));
        }

        $this->shop('s_cancel_requested')->refresh();
        $this->assertEquals(Shop::STATUS_CANCEL_ACCEPTED, $this->shop('s_cancel_requested')->status);
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
