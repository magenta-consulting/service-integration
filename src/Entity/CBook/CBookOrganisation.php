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
class CBookOrganisation
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
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", mappedBy="organization")
     */
    protected $individualMembers;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

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
}
