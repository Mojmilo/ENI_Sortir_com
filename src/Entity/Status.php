<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, Output>
     */
    #[ORM\OneToMany(targetEntity: Output::class, mappedBy: 'status', orphanRemoval: true)]
    private Collection $outputs;

    public function __construct()
    {
        $this->outputs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Output>
     */
    public function getOutputs(): Collection
    {
        return $this->outputs;
    }

    public function addOutput(Output $output): static
    {
        if (!$this->outputs->contains($output)) {
            $this->outputs->add($output);
            $output->setStatus($this);
        }

        return $this;
    }

    public function removeOutput(Output $output): static
    {
        if ($this->outputs->removeElement($output)) {
            // set the owning side to null (unless already changed)
            if ($output->getStatus() === $this) {
                $output->setStatus(null);
            }
        }

        return $this;
    }
}
