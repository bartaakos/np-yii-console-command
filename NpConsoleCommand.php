<?php

class NpConsoleCommand extends CConsoleCommand
{

    public $verbose = false;

    /**
     * @param $msg
     * @param string $level
     * @param string $category
     */
    function log($msg, $level = CLogger::LEVEL_INFO, $category = 'console.application')
    {
        static::consoleLog($msg, $level, $category, $this->verbose);
    }

    public static function consoleLog($msg, $level = CLogger::LEVEL_INFO, $category = 'console.application', $verbose = false) {
        if ($verbose) {
            echo date("Y-m-d H:i:s") . " {$category} ";
            echo " {$msg}\n";
        }

        Yii::log($msg, $level, $category);
    }

    static $cycleStart;
    static $elapsed;

    public function initTimers()
    {
        self::$cycleStart = microtime(true);
        self::$elapsed = 0;
    }

    public function showProgress($current, $total, $reset = false)
    {
        if (!self::$cycleStart || $reset) {
            $this->initTimers();
        }
        $percent = round(($current / $total) * 100, 2);
        $cEnd = microtime(true);
        self::$elapsed += ($cEnd - self::$cycleStart);
        $seconds = (self::$elapsed / $current) * ($total - $current);
        echo "Done: " . str_pad($percent . "%", 7, " ") . " (" . str_pad(
                $current . " / " . $total . ( $percent == 100 ? "" : ")"),
                15,
                " "
            ) . " Time remaining: " . gmdate('H:i:s', $seconds) . "\n";

        self::$cycleStart = microtime(true);
    }
}