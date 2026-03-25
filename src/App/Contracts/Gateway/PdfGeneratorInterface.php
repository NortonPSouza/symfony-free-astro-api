<?php

namespace App\App\Contracts\Gateway;

use App\Domain\Entity\Report;
use App\Domain\Entity\User;
use App\Domain\Exceptions\PdfGenerationException;

interface PdfGeneratorInterface
{
    /**
     * @param User $user
     * @param Report $report
     * @return string
     * @throws PdfGenerationException
     */
    public function generate(User $user, Report $report): string;
}
