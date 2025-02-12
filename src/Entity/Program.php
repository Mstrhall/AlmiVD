<?php
// src/Entity/Program.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ApiResource]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column]
    private ?int $numberPerson = null;

    #[ORM\Column(length: 255)]
    private ?string $focus = null;

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\Column(type: 'json')]
    private array $activityIds = [];

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'programs')]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Rental::class)]
    private Collection $rentals;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Flight::class)]
    private Collection $flights;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->rentals = new ArrayCollection();
        $this->flights = new ArrayCollection();
    }

    // Getters and setters for all properties...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;
        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function getNumberPerson(): ?int
    {
        return $this->numberPerson;
    }

    public function setNumberPerson(int $numberPerson): static
    {
        $this->numberPerson = $numberPerson;
        return $this;
    }

    public function getFocus(): ?string
    {
        return $this->focus;
    }

    public function setFocus(string $focus): static
    {
        $this->focus = $focus;
        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function getActivityIds(): array
    {
        return $this->activityIds;
    }

    public function setActivityIds(array $activityIds): static
    {
        $this->activityIds = $activityIds;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->addProgram($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            $activity->removeProgram($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rental>
     */
    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    public function addRental(Rental $rental): static
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals->add($rental);
            $rental->setProgram($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): static
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getProgram() === $this) {
                $rental->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Flight>
     */
    public function getFlights(): Collection
    {
        return $this->flights;
    }

    public function addFlight(Flight $flight): static
    {
        if (!$this->flights->contains($flight)) {
            $this->flights->add($flight);
            $flight->setProgram($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): static
    {
        if ($this->flights->removeElement($flight)) {
            // set the owning side to null (unless already changed)
            if ($flight->getProgram() === $this) {
                $flight->setProgram(null);
            }
        }

        return $this;
    }
}
