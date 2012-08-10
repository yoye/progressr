<?php

namespace Progressr\Console\Iterator;

use Iterator;
use Progressr\Console\Helper\Progress;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressIterator implements Iterator
{
    /**
     * Progress output helper
     * 
     * @var Progress
     */
    protected $progress;

    /**
     * Iterator elements
     * 
     * @var array
     */
    protected $elements;

    /**
     * Flag for output style
     * 
     * @var int
     */
    protected $flag;
    
    /**
     * Message to display
     * 
     * @var string $message
     */
    protected $message;

    /**
     *
     * @param OutputInterface $output
     * @param array $elements
     * @param string $message
     * @param int $flag 
     */
    public function __construct(OutputInterface $output, array $elements = array(), $flag = self::FLAG_INFO, $message = '')
    {
        $this->elements = $elements;
        $this->progress = new Progress($output, count($elements), $flag);
        $this->flag = $flag;
        $this->message = $message;
    }

    /**
     * Set output style flag
     * 
     * @param int $flag 
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->progress
            ->setCurrent($this->key() + 1)
            ->display($this->message);

        return next($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->elements[$this->key()]);
    }

}
