<?php

namespace App\Entity\CBook;

use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Organization\Model\OrganizationInterface;
use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member")
 */
class CBookMember
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
     * @var Organisation
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @return Organisation
     */
    public function getOrganization(): Organisation
    {
        return $this->organization;
    }

    /**
     * @param Organisation $organization
     */
    public function setOrganization(Organisation $organization): void
    {
        $this->organization = $organization;
    }
}
