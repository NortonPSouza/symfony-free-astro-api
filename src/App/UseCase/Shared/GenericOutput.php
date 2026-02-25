<?php

namespace App\App\UseCase\Shared;

abstract class GenericOutput implements \JsonSerializable
{
    private readonly \DateTime $time;

    public function __construct(
        private readonly string $title,
        private readonly string $path,
        private readonly array $data,
        private readonly int $code,
    )
    {
        $this->time = new \DateTime();
        $this->time->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'path' => $this->getPath(),
            'data' => $this->getData(),
            'code' => $this->getCode(),
            'time' => $this->getTime()->format('d/m/Y H:i:s')
        ];
    }

}