<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Program;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
#[Post]
#[Get]
#[ApiFilter(SearchFilter::class, properties: ['place' => 'exact'])] // Ajout du filtre pour la propriété place
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length:55)]
    private ?string $place = null;

    #[ORM\Column(length:255)]
    private ?string $name = null;

    #[ORM\Column(length:255)]
    private ?string $numberSpace = null;

    #[ORM\Column(type:"float")]
    private ?float $price = null;

    #[ORM\Column(length:255)]
    private ?string $description = null;

    #[ORM\Column(type:"time")]
    private ?\DateTimeInterface $duration = null;
    #[ORM\ManyToMany(targetEntity: Program::class, mappedBy: 'activities')]
    private Collection $programs;

    #[ORM\Column(length:255)]
    private ?string $period = null;

    #[ORM\ManyToMany(targetEntity: Program::class, mappedBy: "activities")]
    private Collection $idProgram;

    public function __construct()
    {
        $this->idProgram = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumberSpace(): ?string
    {
        return $this->numberSpace;
    }

    public function setNumberSpace(string $numberSpace): self
    {
        $this->numberSpace = $numberSpace;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return Collection<int, Program>
     */
    public function getIdProgram(): Collection
    {
        return $this->idProgram;
    }

    public function addIdProgram(Program $program): self
    {
        if (!$this->idProgram->contains($program)) {
            $this->idProgram[] = $program;
            $program->addActivity($this);
        }

        return $this;
    }

    public function removeIdProgram(Program $program): self
    {
        if ($this->idProgram->removeElement($program)) {
            $program->removeActivity($this);
        }

        return $this;
    }
}
