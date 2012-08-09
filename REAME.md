README
======

Introduction
------------

Progressr is an helper to see evolution in commands that used Symfony2 Console component.
You can display evolution information or a progress bar

Installation
------------

The best way to install progressr is to use [composer][1].

Usage
-----

### Manually
Once installed you have an Helper and an Iterator, you can choose how you want to use progressr. You can display informations manually:


``` php
<?php

use Progressr\Console\Helper\Progress;

class FooCommand extends ContainerAwareCommand
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = new Progress($output, 100);
    
        foreach (range(1, 100) as $value) {
            $progress
                ->increment()
                ->info()
            ;
            
            // Will display "Current status: x/100 x%"
            
            sleep(0.1);
        }
    }
}
```

If your command his in multiple step you can manually set the current position.

``` php
<?php
$progress = new Progress($output, 4);
$progress->setCurrent(1)->info('Step1:'); // Display "Step1: 1/4 25%"
$progress->setCurrent(2)->info('Step2:'); // Display "Step1: 2/4 50%"
$progress->setCurrent(3)->info('Step3:'); // Display "Step1: 3/4 75%"
$progress->setCurrent(4)->info('Final step:'); // Display "Step1: 4/4 100%" and add new line
```

You can also display a progress bar.

``` php
<?php
$progress = new Progress($output, 4);
$progress->setCurrent(1)->bar(); // Display "[=====               ] 1/4 25%"
```

### Iterator
Progressr can be used with an iterator that will display informations automatically.

``` php
<?php
$array = range(1, 100);
$iterator = new ProgressIterator($output, $array);

foreach ($iterator as $value) {
    // Do stuff
    // Progressr: Automatic display
}
```


[1]: http://getcomposer.org/
