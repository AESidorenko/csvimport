<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rec_time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timeRFC3339;

    /**
     * @ORM\Column(type="integer")
     */
    private $filesize;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $useragent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $responseHttpStatus;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $requestHttpMethod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contentType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecTime(): ?\DateTimeInterface
    {
        return $this->rec_time;
    }

    public function setRecTime(\DateTimeInterface $rec_time): self
    {
        $this->rec_time = $rec_time;

        return $this;
    }

    public function getTimeRFC3339(): ?string
    {
        return $this->timeRFC3339;
    }

    public function setTimeRFC3339(string $timeRFC3339): self
    {
        $this->timeRFC3339 = $timeRFC3339;

        return $this;
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function setFilesize(int $filesize): self
    {
        $this->filesize = $filesize;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getUseragent(): ?string
    {
        return $this->useragent;
    }

    public function setUseragent(string $useragent): self
    {
        $this->useragent = $useragent;

        return $this;
    }

    public function getResponseHttpStatus(): ?int
    {
        return $this->responseHttpStatus;
    }

    public function setResponseHttpStatus(?int $responseHttpStatus): self
    {
        $this->responseHttpStatus = $responseHttpStatus;

        return $this;
    }

    public function getRequestHttpMethod(): ?string
    {
        return $this->requestHttpMethod;
    }

    public function setRequestHttpMethod(string $requestHttpMethod): self
    {
        $this->requestHttpMethod = $requestHttpMethod;

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }
}
