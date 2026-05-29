<?php

namespace App\Application\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
