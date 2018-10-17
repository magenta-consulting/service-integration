<?php

namespace App\Entity\CBook;

use Bean\Component\Organization\Model\Organization as OrganizationModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\System;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__organisation")
 */
class CBookOrganisation extends CBookThing
{

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    function __construct()
    {
        parent::__construct();
        $this->members = new ArrayCollection();
    }

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\CBook\CBookMember", mappedBy="organization")
     */
    protected $individualMembers;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", name="linked_to_wellness", nullable=true)
     */
    protected $linkedToWellness;
    /**
     * @var string|null
     * @ORM\Column(length=150, name="reg_no", nullable=true)
     */
    protected
        $regNo;
    /**
     * @var integer|null
     * @ORM\Column(type="integer",name="wellness_id", nullable=true)
     */
    protected $wellnessId;

    /**
     * @var string|null
     * @ORM\Column(type="string",name="wellness_pin", nullable=true)
     */
    protected $wellnessPin;
    /**
     * @var string|null
     * @ORM\Column(type="string",name="wellness_employee_code", nullable=true)
     */
    protected $wellnessEmployeeCode;

    /**
     * @return Collection
     */
    public function getIndividualMembers(): Collection
    {
        return $this->individualMembers;
    }

    /**
     * @param Collection $individualMembers
     */
    public function setIndividualMembers(Collection $individualMembers): void
    {
        $this->individualMembers = $individualMembers;
    }

    /**
     * @return bool|null
     */
    public function getLinkedToWellness(): ?bool
    {
        return $this->linkedToWellness;
    }

    /**
     * @return bool
     */
    public function isLinkedToWellness(): bool
    {
        return !empty($this->linkedToWellness);
    }

    /**
     * @param bool|null $linkedToWellness
     */
    public function setLinkedToWellness(?bool $linkedToWellness): void
    {
        $this->linkedToWellness = $linkedToWellness;
    }

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
     * @return int|null
     */
    public function getWellnessId(): ?int
    {
        return $this->wellnessId;
    }

    /**
     * @param int|null $wellnessId
     */
    public function setWellnessId(?int $wellnessId): void
    {
        $this->wellnessId = $wellnessId;
    }

    /**
     * @return null|string
     */
    public function getWellnessPin(): ?string
    {
        return $this->wellnessPin;
    }

    /**
     * @param null|string $wellnessPin
     */
    public function setWellnessPin(?string $wellnessPin): void
    {
        $this->wellnessPin = $wellnessPin;
    }

    /**
     * @return null|string
     */
    public function getWellnessEmployeeCode(): ?string
    {
        return $this->wellnessEmployeeCode;
    }

    /**
     * @param null|string $wellnessEmployeeCode
     */
    public function setWellnessEmployeeCode(?string $wellnessEmployeeCode): void
    {
        $this->wellnessEmployeeCode = $wellnessEmployeeCode;
    }

    /**
     * @return null|string
     */
    public function getRegNo(): ?string
    {
        return $this->regNo;
    }

    /**
     * @param null|string $regNo
     */
    public function setRegNo(?string $regNo): void
    {
        $this->regNo = $regNo;
    }
}
