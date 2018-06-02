<?php
require 'bootstrap.php';
define('MASSIVE_FILE', '/data/files/largefile.txt');

use App\Utilities\LargeFile;

try {
    $largeFile = new LargeFile(__DIR__ . MASSIVE_FILE);
    $iterator = $largeFile->getIterator('ByLine');
    // NOTE: this comes back as an instance of Generator, which implements Iterator
    // echo get_class($iterator);
    // iterate through large file; count number of words per line and record average
    $words = 0;
    foreach ($iterator as $line) {
        echo $line;
        echo '<br><br>';
        $words += str_word_count($line);
    }
    echo str_repeat('-', 52) . PHP_EOL;
    printf("%-40s : %8d\n", 'Total Words', $words);
    printf("%-40s : %8d\n", 'Average Words Per Line', ($words / $iterator->getReturn()));
    echo str_repeat('-', 52) . PHP_EOL;
} catch (Throwable $e) {
    echo $e->getMessage();
}

