<?php

namespace App\Entity;

use App\Repository\OutputRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutputRepository::class)]
class Output
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDatetime = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDeadline = null;

    #[ORM\Column]
    private ?int $maxNumberRegistration = null;

    #[ORM\Column(length: 255)]
    private ?string $exitInfos = null;

    #[ORM\ManyToOne(inversedBy: 'outputs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?status $status = null;

    #[ORM\ManyToOne(inversedBy: 'outputs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?member $organisator = null;

    /**
     * @var Collection<int, member>
     */
    #[ORM\ManyToMany(targetEntity: member::class, inversedBy: 'outputs')]
    private Collection $members;

    #[ORM\ManyToOne(inversedBy: 'outputs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?site $site = null;

    #[ORM\ManyToOne(inversedBy: 'outputs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?location $location = null;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(\DateTimeInterface $startDatetime): static
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(\DateTimeInterface $registrationDeadline): static
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    public function getMaxNumberRegistration(): ?int
    {
        return $this->maxNumberRegistration;
    }

    public function setMaxNumberRegistration(int $maxNumberRegistration): static
    {
        $this->maxNumberRegistration = $maxNumberRegistration;

        return $this;
    }

    public function getExitInfos(): ?string
    {
        return $this->exitInfos;
    }

    public function setExitInfos(string $exitInfos): static
    {
        $this->exitInfos = $exitInfos;

        return $this;
    }

    public function getStatus(): ?status
    {
        return $this->status;
    }

    public function setStatus(?status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOrganisator(): ?member
    {
        return $this->organisator;
    }

    public function setOrganisator(?member $organisator): static
    {
        $this->organisator = $organisator;

        return $this;
    }

    /**
     * @return Collection<int, member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(member $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getSite(): ?site
    {
        return $this->site;
    }

    public function setSite(?site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getLocation(): ?location
    {
        return $this->location;
    }

    public function setLocation(?location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
