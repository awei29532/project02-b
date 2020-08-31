<?php

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;

class BaseSeeder extends Seeder
{
    /**
     * @var ConsoleOutput
     */
    protected $console;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * 任務開始時間
     * @var
     */
    private $start;

    /**
     * BaseSeeder constructor.
     */
    public function __construct()
    {
        $this->console = app(ConsoleOutput::class);

        $this->faker = Faker\Factory::create('zh_TW');
    }

    /**
     * @param $task
     */
    protected function taskStart($task)
    {
        $this->console->writeln("<fg=green>執行「{$task}」任務</>");
        // 每個任務的開始時間戳記
        $this->start = microtime(true);
    }

    protected function taskEnd($message = '')
    {
        // 計算任務花費時間
        $taskCost = number_format((microtime(true) - $this->start), '2', '.', '');

        // 顯示任務花費時間
        $useMemory = $this->bytesToHuman(memory_get_usage());
        $this->console->writeln("<info>{$message}共花費了 {$taskCost} 秒，目前記憶體使用 {$useMemory}</info>");
    }

    /**
     * @param $message
     */
    protected function showMessage($message)
    {
        $this->console->writeln($message);
    }

    /**
     * 將 bytes 轉成可讀
     * @param $bytes
     * @return string
     */
    private function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
