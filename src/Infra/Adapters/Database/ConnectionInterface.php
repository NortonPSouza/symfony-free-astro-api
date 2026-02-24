<?php

namespace App\Infra\Adapters\Database;

interface ConnectionInterface
{
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
    public function flush(): void;
}