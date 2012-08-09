<?php

namespace Progressr\Console\Helper;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class is an helper for rendering progress information during a console process.
 * You can output simple information as "10/100 10%"
 * Or a progress bar with information
 */
class Progress
{

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
     *
     * @param OutputInterface $output
     * @param int $total 
     */
    public function __construct(OutputInterface $output, $total)
    {
        $this->output = $output;
        $this->total = $total;
        $this->current = 0;
    }
    
    /**
     * Set current value
     * 
     * @param int $current
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

    /**
     * Output information
     * exemple: my message 20/100 20%
     * 
     * @param string $text 
     */
    public function info($text = 'Current status:')
    {
        $this->increment();
        
        $message = sprintf('%s %s', $text, $this->getStatusMessage());
        
        $this->output($message);
    }
    
    /**
     * Output a progressbar
     * 
     * exemple: [====          ] 20/100 20%
     */
    public function bar()
    {
        $fill = floor($this->getPercentage() / 5);
        
        $message = sprintf('[%s%s] %s', str_repeat('=', $fill), str_repeat(' ', 20 - $fill), $this->getStatusMessage());
        
        $this->output($message);
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
        return sprintf('%s/%s %s%%', $this->current, $this->total, $this->getPercentage());
    }
    
    /**
     * Output message, overwrite last message
     * 
     * @param string $message 
     */
    protected function output($message)
    {
        if (null !== $this->lastMessage) {
            $this->output->write(str_repeat("\x08", strlen($this->lastMessage)));
        }

        $this->output->write(sprintf('<comment>%s</comment>', $message), $this->isFinished());
        
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
