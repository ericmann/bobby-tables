<?php

if (-1 === version_compare(phpversion(), '7.1')) {
    echo "This project requires at least PHP 7.1. Please upgrade to continue...\r\n";
    exit(1);
} else {
    echo sprintf("Running PHP %s. GREAT!\r\n", phpversion());
}

if (-1 === version_compare(phpversion('sodium'), '7.1')) {
    echo "This project requires Libsodium support. Please install it to continue...\r\n";
    exit(1);
} else {
    echo "Libsodium is available. Great!\r\n";
}

if (-1 === version_compare(phpversion('sqlite3'), '7.1')) {
    echo "This project requires SQLite support. Please install it to continue...\r\n";
    exit(1);
} else {
    echo "SQLite is available. Great!\r\n";
}