<?php

class ImportTransferCommandTest extends CDbTestCase
{
    public $fixtures = array(
        'blank' => 'Transfer',
    );

    /**
     * @var array $test_files
     */
    private $test_files = array(
        'PAYMENT_DETAIL_20120820.CSV.sample',
        'PAYMENT_DETAIL_20120921.CSV.sample',
    );

    private $test_file_content = array(
        array('target_shop_code'=>'9000','closing_day'=>'2012-06-15','target_entry_code'=>'1000009005','term_from'=>'2012-05-01','term_to'=>'2012-05-31','transferred'=>'2012-08-20','amount_of_sales'=>'200000','number_of_transactions'=>'10','fee'=>'2000','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9000','closing_day'=>'2012-07-15','target_entry_code'=>'1000009005','term_from'=>'2012-06-01','term_to'=>'2012-06-30','transferred'=>'2012-08-20','amount_of_sales'=>'300000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9000','closing_day'=>'2012-08-15','target_entry_code'=>'1000009005','term_from'=>'2012-07-01','term_to'=>'2012-07-31','transferred'=>'2012-08-20','amount_of_sales'=>'250000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9000','closing_day'=>'2012-09-15','target_entry_code'=>'1000009005','term_from'=>'2012-08-01','term_to'=>'2012-08-31','transferred'=>'2012-09-20','amount_of_sales'=>'250000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9001','closing_day'=>'2012-06-15','target_entry_code'=>'1000009006','term_from'=>'2012-05-01','term_to'=>'2012-05-31','transferred'=>'2012-09-20','amount_of_sales'=>'200000','number_of_transactions'=>'10','fee'=>'2000','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9001','closing_day'=>'2012-07-15','target_entry_code'=>'1000009006','term_from'=>'2012-06-01','term_to'=>'2012-06-30','transferred'=>'2012-09-20','amount_of_sales'=>'300000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9001','closing_day'=>'2012-08-15','target_entry_code'=>'1000009006','term_from'=>'2012-07-01','term_to'=>'2012-07-31','transferred'=>'2012-09-20','amount_of_sales'=>'250000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
        array('target_shop_code'=>'9001','closing_day'=>'2012-09-15','target_entry_code'=>'1000009006','term_from'=>'2012-08-01','term_to'=>'2012-08-31','transferred'=>'2012-09-20','amount_of_sales'=>'250000','number_of_transactions'=>'15','fee'=>'2500','adjust'=>'300','total'=>'200000','carry_over'=>'500','brought_foward'=>'200','carried_foward'=>'500'),
    );

    /**
     * @covers ImportTransferCommand
     */
    public function testRun()
    {
        $this->deleteTestFile();
        $this->prepareTestFile();

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'ImportTransfer';
        $shell = new ImportTransferCommand($commandName, $CCRunner);
        $shell->run(array());

        $dest_path = Yii::app()->params['transfer_path'];
        $dh = opendir($dest_path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $this->fail(sprintf("file '%s' is not deleted", $file));
        }

        $com = Yii::app()->db->createCommand('select * from transfer order by target_shop_code, closing_day');
        $trs = $com->queryAll();
        $this->assertEquals($this->test_file_content, $trs);
    }

    private function deleteTestFile()
    {
        $dest_path = Yii::app()->params['transfer_path'];
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
        $dest_path = Yii::app()->params['transfer_path'];

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
