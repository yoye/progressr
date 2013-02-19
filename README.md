README
======

Since Symfony 2.2 Console component implement his own progress status: http://symfony.com/blog/new-in-symfony-2-2-better-interaction-from-the-console

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
        $progress = new Progress($output, 10);
    
        foreach (range(1, 10) as $value) {
            $progress
                ->increment()
                ->display()
            ;
            
            // Will display "Current status: x/100 x%"
            
            sleep(1);
        }
    }
}
```

If your command is in multiple step you can manually set the current position.

``` php
<?php
$progress = new Progress($output, 4);
$progress->setCurrent(1)->display('Step1:'); // Display "Step1: 1/4 25%"
$progress->setCurrent(2)->display('Step2:'); // Display "Step2: 2/4 50%"
$progress->setCurrent(3)->display('Step3:'); // Display "Step3: 3/4 75%"
$progress->setCurrent(4)->display('Final step:'); // Display "Final step: 4/4 100%" and add new line
```

You can also display a progress bar.

``` php
<?php
$progress = new Progress($output, 4, Progress::FLAG_BAR);
$progress->setCurrent(1)->display(); // Display "[=====               ] 1/4 25%"
```

You can also display a timer.

``` php
<?php
$progress = new Progress($output, 4, Progress::FLAG_TIMER);
$progress->setCurrent(1)->display(); // Display "15min 25sec"
```

Of course you can mix all together like this:

``` php
<?php
$progress = new Progress($output, 4, Progress::FLAG_TIMER | Progress::FLAG_BAR | Progress::FLAG_INFO);
$progress->setCurrent(1)->display('my message:'); // Display "my message: [=====               ] 15min 25sec 1/4 25%"
```

You can't redefine display order message always first, then progress bar, time elapsed and info. All of this are only informative stuff,
I consider that one does not care for display order.

### Iterator
Progressr can be used with an iterator that will display informations automatically.

``` php
<?php
$array = range(1, 100);
$iterator = new ProgressIterator($output, $array, Progress::FLAG_BAR | Progress::FLAG_INFO, 'my message:');

foreach ($iterator as $value) {
    // Do stuff
    // Progressr: Automatic display
}
```


[1]: http://getcomposer.org/
