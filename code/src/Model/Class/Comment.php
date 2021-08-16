<?php
namespace App\Model\Class;

use App\Model\Class\AbstractClass;
use DateTime;

class Comment extends AbstractClass {
    private int $id;
    private string $comment;
    private DateTime $createdAt;
    private int $userId;
    private int $articleId;
    private bool $isActive;

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    public function jsonSerialize(): array {
        return array(
            'id' => $this->getId(),
            'comment' => $this->getComment(),
            'createdAt' => $this->getCreatedAt(),
            'user' => $this->getUserId(),
            'article' => $this->getArticleId(),
            'isActive' => $this->getIsActive()
        );
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getComment(): string {
        return $this->comment;
    }

    public function setComment(string $comment): void {
        $this->comment = $comment;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function getArticleId(): int {
        return $this->articleId;
    }

    public function setArticleId(int $articleId): void {
        $this->articleId = $articleId;
    }

    public function getIsActive(): bool {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void {
        $this->isActive = $isActive;
    }
}