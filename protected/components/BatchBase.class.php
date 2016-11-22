<?php
/**
 * @see application.components.BatchBase
 *
 * PHPUnit実行時にYii::app()->end()されるとメッセージが表示されないため、
 * @link{application.components.BatchBase}から__destruct()を取り除いた。
 */

class BatchBase extends CConsoleCommand
{
    public $run_multiple = true;
    public $exit_code = 0;
    private $lock_file;
    public function init()
    {
        register_shutdown_function(function() {
            Yii::app()->end();
        });
        if (!$this->run_multiple) {
            $this->checkIsRunning();
        }
        Yii::log('Batch ' . $this->name . ' start', 'info');
    }

    private function checkIsRunning()
    {
        if (!isset(Yii::app()->params['batch_lock_dir'])) {
            echo "Lock Directory not found in configulation\n";
            Yii::app()->end();
        }
        $this->lock_file = Yii::app()->params['batch_lock_dir'] . '/batch_' . $this->name . '.pid';
        $pid = null;
        if (file_exists($this->lock_file)) {
            $pid = file_get_contents($this->lock_file);
        }
        if ($pid) {
            if (file_exists('/proc/' . $pid)) {
                Yii::log('batch ' . $this->name . ' is still running.(PID:' . $pid . ')', 'error', 'components.batchbase');
                $this->exit_code = 1;
                Yii::app()->end();
            } else {
                Yii::log('previous batch ' . $this->name . ' could stop by unknown reason', 'error', 'components.batchbase');
                unlink($this->lock_file);
            }
        }
        file_put_contents($this->lock_file, getmypid());
    }

    function __destruct()
    {
        if (file_exists($this->lock_file) && $this->exit_code === 0) {
            unlink($this->lock_file);
        }
        Yii::log('Batch ' . $this->name . ' end', 'info');
    }
}
