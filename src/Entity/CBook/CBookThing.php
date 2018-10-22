<?php

namespace App\Entity\CBook;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member")
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="thing__thing")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"individual" = "CBookMember", "organisation" = "CBookOrganisation", "person"="CBookPerson"})
 * @ORM\HasLifecycleCallbacks
 */
abstract class CBookThing
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     * // Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
     */
    protected $id;

  public function __construct()
  {
  }

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;
    /**
     * @var bool
     * @ORM\Column(type="boolean", name="enabled", options={"default":false})
     */
    protected
        $enabled;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}