<?php
namespace App\Model\Class;

use DateTime;

class Article extends AbstractClass {
    private int $id;
    private string $title;
    private string $teaser;
    private string $content;
    private string $cover;
    private int $authorId;
    private DateTime $createdAt;
    private DateTime|null $updatedAt;

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return array(
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'teaser' => $this->getTeaser(),
            'content' => $this->getContent(),
            'cover' => $this->getCover(),
            'author' => $this->getAuthorId(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt()
        );
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getTeaser(): string {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): void {
        $this->teaser = $teaser;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getCover(): string {
        return $this->cover;
    }

    public function setCover(string $cover): void {
        $this->cover = $cover;
    }

    public function getAuthorId(): int {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void {
        $this->authorId = $authorId;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime|null {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime|null $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
}