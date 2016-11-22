<?php
/**
 * ExportCertImagesCommandInvalidSourceTest
 *
 * @see ExportCertImagesCommand
 * @author KURIHARA Hikonobu (Hymena & Co.)
 */

class ExportCertImagesCommandInvalidSourceTest extends CDbTestCase
{
    /**
     * @var array Fixtures
     */
    public $fixtures = array(
        'file_transfer'=>'FileTransfer'
    );

    /**
     * test for run()
     */
    public function testRun_1()
    {
        $this->deleteTestFiles();
        $this->createTestFiles();

        Yii::app()->params['certificate_image_path'] = 'not_existing_dir/';

        $fids = array('testfile0', 'testfile2', 'testfile4', 'testfile6', 'testfile8', 'testfile1', 'testfile3', 'testfile5', 'testfile7', 'testfile9',);

        foreach ($fids as $fid) {
            $file_path_from = Yii::app()->params['certificate_image_path'] . $this->file_transfer($fid)->filename;
            $this->assertFileNotExists($file_path_from);
        }

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'ExportCertImages';
        $shell = new ExportCertImagesCommand($commandName, $CCRunner);
        $shell->run(array());

        $fids   = array('testfile0', 'testfile2', 'testfile4', 'testfile6', 'testfile8',);
        $fids_n = array('testfile1', 'testfile3', 'testfile5', 'testfile7', 'testfile9',);

        foreach ($fids as $fid) {
            $this->file_transfer($fid)->refresh();
            $this->assertEquals(2, $this->file_transfer($fid)->status);
        }

        foreach ($fids_n as $fid_n) {
            $this->file_transfer($fid_n)->refresh();
            $this->assertEquals(1, $this->file_transfer($fid_n)->status);
        }
    }

    private function deleteTestFiles()
    {
        $dir = Yii::app()->params['certificate_image_path'];
        foreach (range(0,9) as $num) {
            $file_name = $this->file_transfer('testfile'.$num)->filename;
            $file_path = $dir . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $dir = Yii::app()->params['file_transfer_cert_path'];
        foreach (range(0,9) as $num) {
            $file_name = $this->file_transfer('testfile'.$num)->filename;
            $file_path = $dir . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    private function createTestFiles()
    {
        $dir = Yii::app()->params['certificate_image_path'];
        foreach (range(0,9) as $num) {
            $file_name = $this->file_transfer('testfile'.$num)->filename;
            $file_path = $dir . $file_name;
            file_put_contents($file_path, file_get_contents('/dev/urandom', false, null, 0, mt_rand(500*1024, 1024*1024)));
        }
    }

    public function __destruct()
    {
        Yii::app()->end();
    }
}
