<?php

namespace App\Entity\CBook;

use Bean\Component\Organization\Model\OrganizationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member")
 */
class CBookMember extends CBookThing
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->groupIndividuals = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->enabled = true;
    }

    /**
     * @var CBookPerson|null
     * @ORM\ManyToOne(targetEntity="App\Entity\CBook\CBookPerson", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_person", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $person;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $synchronisedAt;
    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $wellnessId;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $wellnessPin;


    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $wellnessEmployeeCode;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=20,nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var string|null
     * @ORM\Column(type="string",nullable=true)
     */
    protected $pin;

    public function getBooksToRead()
    {
        $draftBooks = $this->organization->getDraftBooksHavingPreviousVersions();
        $books = [];
        /** @var Book $b */
        foreach ($draftBooks as $b) {
            if ($b->isAccessibleToIndividual($this)) {
                $books[] = $b;
            }
        }
        return $books;
    }

    public function initiatePin()
    {
        if (empty($this->pinCode)) {
            $this->pin = str_replace('O', '0', User::generate4DigitCode());
        }
        return $this;
    }

    public function initiateCode()
    {
        if (empty($this->employeeCode)) {
            $this->code = str_replace('O', '0', User::generate4DigitCode() . '-' . User::generateTimestampBasedCode());
        }
        return $this;
    }


    /**
     * @var CBookOrganisation
     * @ORM\ManyToOne(targetEntity="App\Entity\CBook\CBookOrganisation", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @return CBookOrganisation
     */
    public function getOrganization(): CBookOrganisation
    {
        return $this->organization;
    }

    /**
     * @param CBookOrganisation $organization
     */
    public function setOrganization(CBookOrganisation $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return CBookPerson|null
     */
    public function getPerson(): ?CBookPerson
    {
        return $this->person;
    }

    /**
     * @param CBookPerson|null $person
     */
    public function setPerson(?CBookPerson $person): void
    {
        $this->person = $person;
    }

    /**
     * @return \DateTime|null
     */
    public function getSynchronisedAt(): ?\DateTime
    {
        return $this->synchronisedAt;
    }

    /**
     * @param \DateTime|null $synchronisedAt
     */
    public function setSynchronisedAt(?\DateTime $synchronisedAt): void
    {
        $this->synchronisedAt = $synchronisedAt;
    }

    /**
     * @return int
     */
    public function getWellnessId(): ?int
    {
        return $this->wellnessId;
    }

    /**
     * @param int $wellnessId
     */
    public function setWellnessId(int $wellnessId): void
    {
        $this->wellnessId = $wellnessId;
    }

    /**
     * @return string
     */
    public function getWellnessPin(): ?string
    {
        return $this->wellnessPin;
    }

    /**
     * @param string $wellnessPin
     */
    public function setWellnessPin(?string $wellnessPin): void
    {
        $this->wellnessPin = $wellnessPin;
    }

    /**
     * @return string
     */
    public function getWellnessEmployeeCode(): ?string
    {
        return $this->wellnessEmployeeCode;
    }

    /**
     * @param string $wellnessEmployeeCode
     */
    public function setWellnessEmployeeCode(?string $wellnessEmployeeCode): void
    {
        $this->wellnessEmployeeCode = $wellnessEmployeeCode;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getPin(): ?string
    {
        return $this->pin;
    }

    /**
     * @param null|string $pin
     */
    public function setPin(?string $pin): void
    {
        $this->pin = $pin;
    }
}
