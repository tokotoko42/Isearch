<?php

class ImportTransferDetailCommandTest extends CDbTestCase
{
    public $fixtures = array(
        'blank' => 'TransferDetail',
    );

    private $test_files = array(
        'TRANSACTION_DETAIL_20120820.CSV.sample'
    );

    private $test_file_content = array(
        array('transaction_id'=>'transaction1', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000001','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction2', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000002','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction3', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000003','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction4', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000004','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction5', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000005','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction6', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000006','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction7', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000007','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction8', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000008','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction9', 'target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000009','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
        array('transaction_id'=>'transaction10','target_shop_code'=>'1','closing_day'=>'2012-08-01','target_entry_code'=>'100000000001','shop_code'=>'1','entry_code'=>'100000000001','transferred'=>'2012-08-10','order_key'=>'90000010','order_day'=>'2012-07-03','card_type'=>'1','card_brand'=>'1','transaction_type'=>'10','payment_method'=>'10','number_of_payments'=>'1','amount_of_sales'=>'10000','tariff'=>'0.05'),
    );

    /**
     * @covers ImportTransferDetailCommand
     */
    public function testRun()
    {
        $this->deleteTestFile();
        $this->prepareTestFile();

        $CCRunner = new CConsoleCommandRunner();
        $commandName = 'ImportTransferDetail';
        $shell = new ImportTransferDetailCommand($commandName, $CCRunner);
        $shell->run(array());

        $dest_path = Yii::app()->params['transfer_path'];
        $dh = opendir($dest_path);
        while (($file=readdir($dh))!==false) {
            if (preg_match('|^\.+$|', $file)) {
                continue;
            }
            $this->fail(sprintf("file '%s' is not deleted", $file));
        }
        $com = Yii::app()->db->createCommand('select * from transfer_detail order by order_key');

        array_walk($this->test_file_content, function(&$a){
            ksort($a);
        });
        $tds = $com->queryAll();
        array_walk($tds, function(&$a){
            ksort($a);
        });
        $this->assertEquals($this->test_file_content, $tds);
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
