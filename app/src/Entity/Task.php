<?php

namespace App\Entity;

use App\Concern\Entity\Interface\CreatedInterface;
use App\Concern\Entity\Trait\CreatedTrait;
use App\Enum\Task\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'tasks')]
#[ORM\Index(name: 'idx_task_title_description_gin', columns: ['title', 'description'], flags: ['gin'])]
#[ORM\Index(name: 'idx_task_created_at_completed_at_priority', columns: ['created_at', 'completed_at', 'priority'])]
class Task implements CreatedInterface
{
    use CreatedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'status', nullable: false, enumType: TaskStatus::class)]
    private TaskStatus $status = TaskStatus::TODO;

    #[ORM\Column(name: 'priority', type: 'integer', nullable: false)]
    private int $priority;

    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: false)]
    private string $description;

    #[ORM\Column(name: 'completed_at', type: 'datetime', nullable: true)]
    private ?\DateTime $completedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Task::class, cascade: ['persist'], inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private ?Task $parent = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'parent', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    public function complete(): void
    {
        $this->completedAt = new \DateTime();
    }

    public function isCompleted(): bool
    {
        return null !== $this->completedAt;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setParent(?Task $task): static
    {
        $this->parent = $task;

        return $this;
    }

    public function getParent(): ?Task
    {
        return $this->parent;
    }

    public function addChildren(Task $task): static
    {
        if (!$this->children->contains($task)) {
            $this->children->add($task);
        }

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }
}
