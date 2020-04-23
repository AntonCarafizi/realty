<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    /**
     * @ORM\Column(type="smallint")
     */
    private $room_qty;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_for_rent;

    /**
     * @ORM\Column(type="float")
     */
    private $area;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="items")
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $created;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $location = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {

        $this->images = $images;

        return $this;
    }


    public function getRoomQty(): ?int
    {
        return $this->room_qty;
    }

    public function setRoomQty(int $room_qty): self
    {
        $this->room_qty = $room_qty;

        return $this;
    }

    public function getIsForRent(): ?bool
    {
        return $this->is_for_rent;
    }

    public function setIsForRent(bool $is_for_rent): self
    {
        $this->is_for_rent = $is_for_rent;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLocation(): ?array
    {
        return $this->location;
    }

    public function setLocation(?array $location): self
    {
        $this->location = $location;

        return $this;
    }

}
