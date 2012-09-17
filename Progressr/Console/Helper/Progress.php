<?php

namespace Progressr\Console\Helper;

use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class is an helper for rendering progress information during a console process.
 * You can output simple information as "10/100 10%"
 * Or a progress bar with information
 *
 * FLAG_INFO: 20/100 20%
 * FLAG_BAR: [====          ]
 * FLAG_TIME: 1h 25min 32sec
 * FLAG_RATIO: 25 objects/s
 * FLAG_ALL: [====================] 5sec (10/10) 100%, 2 objects/s
 */
class Progress
{
    const FLAG_INFO = 1;
    const FLAG_BAR = 2;
    const FLAG_TIMER = 4;
    const FLAG_RATIO = 8;
    const FLAG_ALL = 15;

    /**
     * @var OutputInterface $output
     */
    protected $output;

    /**
     * Total element for progression
     *
     * @var int $total
     */
    protected $total;

    /**
     * Last message outputed
     *
     * @var string $lastMessage
     */
    protected $lastMessage;

    /**
     * Current progress value
     *
     * @var int $current
     */
    protected $current;

    /**
     * Use tu display time elapse
     *
     * @var int $startTime
     */
    protected $startTime;

    /**
     *
     * @param OutputInterface $output
     * @param int             $total
     */
    public function __construct(OutputInterface $output, $total, $flags = self::FLAG_ALL)
    {
        $this->output = $output;
        $this->total = $total;
        $this->current = 0;

        if (!is_int($flags)) {
            throw new InvalidArgumentException('$flags must be an integer.');
        }

        $this->flags = $flags;
    }

    /**
     * Start internal timer
     */
    public function startTimer()
    {
        $this->startTime = microtime(true);
    }

    /**
     * Get time elapsed since start
     *
     * @return int
     */
    public function getElapsedTime()
    {
        return microtime(true) - $this->startTime;
    }

    /**
     * Set current value
     *
     * @param  int      $current
     * @return Progress
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current value
     *
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    public function display($message = '')
    {

        // Bar
        if (($this->flags & self::FLAG_BAR) !== 0) {
            $fill = floor($this->getPercentage() / 5);

            $message .= sprintf('[%s%s]', str_repeat('=', $fill), str_repeat(' ', 20 - $fill));
        }

        // Time
        if (($this->flags & self::FLAG_TIMER) !== 0) {
            $timeElapse = '';
            if (null !== $this->startTime) {
                $time = floor($this->getElapsedTime());
                foreach (array('h' => 3600, 'min' => 60, 'sec' => 1) as $key => $value) {
                    if ($periodValue = floor($time / $value)) {
                        $time -= $periodValue * $value;

                        $timeElapse .= sprintf(' %s%s', $periodValue, $key);
                    }
                }
                $message .= $timeElapse;
            }
        }

        // Info
        if (($this->flags & self::FLAG_INFO) !== 0) {
            $message .= ' '.$this->getStatusMessage();
        }

        // Ration
        if (($this->flags & self::FLAG_RATIO) !== 0) {
            $message .= sprintf(', %s objects/s', ceil($this->getCurrent() / $this->getElapsedTime()));
        }

        $this->output(trim($message));
    }

    /**
     * Check if progress is finished
     * e.g: current increment is equal to total
     *
     * @return boolean
     */
    public function isFinished()
    {
        return $this->current === $this->total;
    }

    /**
     * Show current status based on total and percentage
     *
     * @return string
     */
    protected function getStatusMessage()
    {
        return sprintf('(%s/%s) %s%%', $this->current, $this->total, $this->getPercentage());
    }

    /**
     * Output message, overwrite last message
     *
     * @param string $message
     */
    protected function output($message)
    {
        $lastSize = strlen(strip_tags($this->lastMessage));
        $currentSize = strlen(strip_tags($message));

        // Remove last message
        if (null !== $this->lastMessage) {
            $this->output->write(str_repeat("\x08", $lastSize));
        }

        $this->output->write(sprintf('<comment>%s</comment>', $message), $this->isFinished());

        if ($lastSize > $currentSize) {
            $diff = $lastSize - $currentSize;
            $this->output->write(str_repeat(' ', $diff));
            $this->output->write(str_repeat("\x08", $diff), $this->isFinished());
        }

        $this->lastMessage = $message;
    }

    /**
     * Get progression percentage
     *
     * @return int
     */
    protected function getPercentage()
    {
        return ceil(100 * $this->current / $this->total);
    }

    /**
     * Increment current value
     *
     * @return Progress
     */
    public function increment()
    {
        $this->current++;

        return $this;
    }

}
