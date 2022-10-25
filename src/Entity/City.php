<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $commune_id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommuneId(): ?string
    {
        return $this->commune_id;
    }

    public function setCommuneId(string $commune_id): self
    {
        $this->commune_id = $commune_id;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
