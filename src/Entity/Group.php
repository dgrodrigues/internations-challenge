<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @Table(schema="public")
 */
class Group {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId() {
        return $this->id;
    }

    public function setId(int $id): int {
        return $this->id = $id;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="group_users")
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection {
        return $this->users;
    }

    public function getUsersCount(): int {
        return count($this->users);
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function addUser(User $user): self {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    public function removeUser(User $user): self {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
        return $this;
    }
    
}

?>