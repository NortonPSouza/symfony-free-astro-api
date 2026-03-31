<?php

namespace App\Infra\Mappers;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_request_log')]
#[ORM\Index(name: 'idx_created_at', columns: ['created_at'])]
class AppRequestLog
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'method', type: 'string', length: 10, nullable: false)]
    private string $method;

    #[ORM\Column(name: 'endpoint', type: 'string', length: 255, nullable: false)]
    private string $endpoint;

    #[ORM\Column(name: 'status_code', type: 'integer', nullable: false)]
    private int $statusCode;

    #[ORM\Column(name: 'response_time_ms', type: 'integer', nullable: false)]
    private int $responseTimeMs;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setResponseTimeMs(int $responseTimeMs): self
    {
        $this->responseTimeMs = $responseTimeMs;
        return $this;
    }
}
