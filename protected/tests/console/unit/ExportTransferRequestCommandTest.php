<?php
/**
 * ExportTransferRequestCommandTest
 *
 * @see ExportTransferRequestCommmand
 * @author KURIHARA Hikonobu (Hymena & Co.)
 */

class ExportTransferRequestCommandTest extends CDbTestCase
{
    /**
     * @var array Fixtures
     */
    public $fixtures = array(
        'transfer_request' => 'TransferRequest',
    );

    /**
     * test for run()
     */
    public function testRun_1()
    {
        $this->resetDirectory();

        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportTransferRequest';
        $shell = new ExportTransferRequestCommand($commandName, $CCRunner);

        $shell->run(array('1'));

        $list = $this->getFileList();

        $tests = array();
        $tests[] = sprintf("%s,%s\n", $this->transfer_request('prev_234500')->target_shop_code, $this->transfer_request('prev_234500')->requested);
        $tests[] = sprintf("%s,%s\n", $this->transfer_request('tod_175959')->target_shop_code,  $this->transfer_request('tod_175959')->requested);
        $tests = array_unique($tests);

        $file = $list[0];
        $fp = fopen($file, 'r');
        $count = 0;
        $lines = array();
        while (($line=fgets($fp))!==false) {
            $lines[] = $line;
        }

        $this->assertEquals($tests, $lines);
    }

    /**
     * test for run()
     */
    public function testRun_2()
    {
        $this->resetDirectory();

        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportTransferRequest';
        $shell = new ExportTransferRequestCommand($commandName, $CCRunner);

        $shell->run(array('2'));

        $list = $this->getFileList();

        $tests = array();
        $tests[] = sprintf("%s,%s\n", $this->transfer_request('tod_180000')->target_shop_code, $this->transfer_request('tod_180000')->requested);
        $tests[] = sprintf("%s,%s\n", $this->transfer_request('tod_234459')->target_shop_code,  $this->transfer_request('tod_234459')->requested);
        $tests = array_unique($tests);

        $file = $list[0];
        $fp = fopen($file, 'r');
        $count = 0;
        $lines = array();
        while (($line=fgets($fp))!==false) {
            $lines[] = $line;
        }

        $this->assertEquals($tests, $lines);
    }

    public function testGetStart()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportTransferRequest';
        $shell = new ExportTransferRequestCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'getStart');
        $method->setAccessible(true);
        $start = $method->invokeArgs($shell, array(1));
        $test = date('Y-m-d', strtotime('-1 day')) . ' 23:45:00';
        $this->assertEquals($test, $start);

        $start = $method->invokeArgs($shell, array(2));
        $test = date('Y-m-d') . ' 18:00:00';
        $this->assertEquals($test, $start);
    } 

    public function testGetEnd()
    {
        $CCRunner = new CConsoleCommandRunner();

        $commandName = 'ExportTransferRequest';
        $shell = new ExportTransferRequestCommand($commandName, $CCRunner);

        $method = new ReflectionMethod($shell, 'getEnd');
        $method->setAccessible(true);
        $start = $method->invokeArgs($shell, array(1));
        $test = date('Y-m-d') . ' 17:59:59';
        $this->assertEquals($test, $start);

        $start = $method->invokeArgs($shell, array(2));
        $test = date('Y-m-d') . ' 23:44:59';
        $this->assertEquals($test, $start);
    } 

    /**
     * テスト出力ディレクトリを空にする
     */
    private function resetDirectory()
    {
        $path = Yii::app()->params['transfer_request_path'];
        if ($path=='/' || !$path) {
            $this->fail('Test directory not found.');
        }

        $dh = opendir($path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $file = $path . $file;
            unlink($file);
        }
        closedir($dh);
    }

    /**
     * テスト出力ディレクトリ内のファイル一覧を取得する
     */
    private function getFileList()
    {
        $path = Yii::app()->params['transfer_request_path'];
        if ($path=='/' || !$path) {
            $this->fail('Test directory not found.');
        }

        $dh = opendir($path);
        $files = array();
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $file = $path . $file;
            $files[] = $file;
        }
        closedir($dh);
        return $files;
    }

    public function __destruct()
    {
        Yii::app()->end();
    }
}
