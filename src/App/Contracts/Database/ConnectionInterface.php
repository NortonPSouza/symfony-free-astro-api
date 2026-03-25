<?php

namespace App\App\Contracts\Database;

interface ConnectionInterface
{
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
    public function flush(): void;
}