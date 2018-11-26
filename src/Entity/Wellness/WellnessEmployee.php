<?php
namespace App\Entity\Wellness;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mhs__employer__employee")
 */
class WellnessEmployee
{
    const STATE_DRAFT = 'DRAFT';
    const STATE_PUBLISHED = 'PUBLISHED';

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    function __construct()
    {
        $this->entitlement = -1;
        $this->state = self::STATE_DRAFT;
        $this->enabled = true;
        $this->reset = false;
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();
        $this->benefits = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $synchronisedAt;

    /**
     * @return string
     */
    public function getMemberIdWithPrefix()
    {
        if (empty($prefix = $this->employer->getPrefix())) {
            $prefix = '';
        } else {
            $prefix .= '.';
        }

        return $prefix . $this->memberId;
    }


    public function isMemberReady()
    {
        if (!$this->employer->isClub()) {
            return true;
        }

        return !(empty($this->firstname) || empty($this->emailAddress));
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        if ($this->enabled === true && $enabled === false) {
            $this->resignDate = new \DateTime();
        }
        if ($this->enabled === false && $enabled = true) {
            $this->resignDate = null;
        }
        $this->enabled = $enabled;
    }
 
    /**
     * @return string
     */
    public function getName()
    {
        if (!(empty($this->firstname) && empty($this->middlename) && empty($this->lastname))) {
            $this->name = $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
        }

        return $this->name;
    }

    public function initiatePinCode()
    {
        if (empty($this->pinCode)) {
            $this->pinCode = str_replace('O', '0', WellnessUser::generate4DigitCode());
        }
    }

    public function initiateEmployeeCode()
    {
        if (empty($this->employeeCode)) {
            $this->employeeCode = str_replace('O', '0', WellnessUser::generate4DigitCode() . '-' . WellnessUser::generateTimestampBasedCode());
        }
    }

    /**
     * @param int $entitlement
     */
    public function setEntitlement($entitlement)
    {
        $this->entitlement = $entitlement;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
        if ($state === self::STATE_DRAFT) {
            $this->enabled = false;
        } elseif ($state === self::STATE_PUBLISHED) {
            $this->enabled = true;
        }
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
//        if (empty($this->name) || (!empty($this->firstname) && ($this->firstname !== $firstname))) {
        $this->name = $firstname . ' ' . $this->middlename . ' ' . $this->lastname;
//        }
        $this->firstname = $firstname;
    }

    /**
     * @param string $middlename
     */
    public function setMiddlename($middlename)
    {
//        if (empty($this->name) || (!empty($this->middlename) && ($this->middlename !== $middlename))) {
        $this->name = $this->firstname . ' ' . $middlename . ' ' . $this->lastname;
//        }
        $this->middlename = $middlename;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
//        if (empty($this->name) || (!empty($this->lastname) && ($this->lastname !== $lastname))) {
        $this->name = $this->firstname . ' ' . $this->middlename . ' ' . $lastname;
//        }
        $this->lastname = $lastname;
    }

    /**
     * var ArrayCollection
     * ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction\Transaction", mappedBy="employee", cascade={"persist","merge"})
     */
    private $transactions;

    /**
     * @param $id
     *
     * @return Transaction
     */
    public function findTransaction($id)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("id", $id));

        return $this->transactions->matching($criteria)->first();
    }

    /**
     * @param Transaction $trans
     */
    public function addTransaction($trans)
    {
        if (empty($this->transactions)) {
            $this->transactions = new ArrayCollection();
        }
        $this->transactions->add($trans);
        $trans->setEmployee($this);
    }

    /**
     * @param Transaction $trans
     */
    public function removeTransaction($trans)
    {
        $this->transactions->removeElement($trans);
        $trans->setEmployee(null);
    }

    /**
     * var ArrayCollection
     * ORM\OneToMany(targetEntity="Application\Sylius\OrderBundle\Entity\BusinessOrder", mappedBy="employee", cascade={"persist","merge"})
     */
    private $orders;


    /**
     * var ArrayCollection
     * ORM\OneToMany(targetEntity="AppBundle\Entity\Employer\BusinessBenefit", mappedBy="employee", cascade={"all"}, orphanRemoval=true)
     */
    private $benefits;

    /**
     * @var WellnessEmployer
     * @ORM\ManyToOne(targetEntity="App\Entity\Wellness\WellnessEmployer",inversedBy="employees", fetch="EAGER")
     * @ORM\JoinColumn(name="id_employer", referencedColumnName="id")
     */
    private $employer;

    /**
     * var Position
     * ORM\OneToOne(targetEntity="Application\Bean\OrganisationBundle\Entity\Position", inversedBy="businessEmployee", cascade={"persist","merge"})
     * ORM\JoinColumn(name="id_position", referencedColumnName="id")
     */
    private $position;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cbookId;

    /**
     * @var \DateTime $createdDate
     * @ORM\Column(type="date",nullable=true)
     */
    private $dob;

    /**
     * @var \DateTime $createdDate
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime $updatedDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var \DateTime $resignDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resignDate;

    /**
     * Whether this employee's benefits are covered by the employer or
     * do they have to pay for themselves
     * @var boolean
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private $covered = true;

    /**
     * @var boolean
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $reset;

    /**
     * @var boolean
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private $enabled;

    /**
     * @var integer
     * @ORM\Column(type="integer",options={"default":-1})
     */
    private $entitlement;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cbookPin;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cbookEmployeeCode;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    private $memberId;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    private $department;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     * @ORM\Column(length=250, nullable=true)
     */
    protected $nationality;

    /**
     * @var string
     * @ORM\Column(length=4, nullable=true)
     */
    protected $pinCode;

    /**
     * @var string
     * @ORM\Column(length=14, nullable=true)
     */
    protected $employeeCode;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $emailAddress;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $middlename;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $idNumber;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $state;

    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    /**
     *
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEntitlement()
    {
        return $this->entitlement;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return ArrayCollection
     */
    public function getBenefits()
    {
        return $this->benefits;
    }

    /**
     * @param ArrayCollection $benefits
     */
    public function setBenefits($benefits)
    {
        $this->benefits = $benefits;
    }

    /**
     * @return WellnessEmployer
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * @param WellnessEmployer $employer
     */
    public function setEmployer($employer)
    {
        $this->employer = $employer;
    }


    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Position $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return bool
     */
    public function isReset()
    {
        return $this->reset;
    }

    /**
     * @param bool $reset
     */
    public function setReset($reset)
    {
        $this->reset = $reset;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * @param string $idNumber
     */
    public function setIdNumber($idNumber)
    {
        $this->idNumber = $idNumber;
    }

    /**
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param \DateTime $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getPinCode()
    {
        return $this->pinCode;
    }

    /**
     * @param string $pinCode
     */
    public function setPinCode($pinCode)
    {
        $this->pinCode = $pinCode;
    }

    /**
     * @return string
     */
    public function getEmployeeCode()
    {
        return $this->employeeCode;
    }

    /**
     * @param string $employeeCode
     */
    public function setEmployeeCode($employeeCode)
    {
        $this->employeeCode = $employeeCode;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param ArrayCollection $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @return \DateTime
     */
    public function getResignDate()
    {
        return $this->resignDate;
    }

    /**
     * @param \DateTime $resignDate
     */
    public function setResignDate($resignDate)
    {
        $this->resignDate = $resignDate;
    }

    /**
     * @return bool
     */
    public function isCovered()
    {
        return $this->covered;
    }

    /**
     * @param bool $covered
     */
    public function setCovered($covered)
    {
        $this->covered = $covered;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param ArrayCollection $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return string
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @param string $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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
    public function getCbookId(): int
    {
        return $this->cbookId;
    }

    /**
     * @param int $cbookId
     */
    public function setCbookId(int $cbookId): void
    {
        $this->cbookId = $cbookId;
    }

    /**
     * @return null|string
     */
    public function getCbookPin(): ?string
    {
        return $this->cbookPin;
    }

    /**
     * @param null|string $cbookPin
     */
    public function setCbookPin(?string $cbookPin): void
    {
        $this->cbookPin = $cbookPin;
    }

    /**
     * @return null|string
     */
    public function getCbookEmployeeCode(): ?string
    {
        return $this->cbookEmployeeCode;
    }

    /**
     * @param null|string $cbookEmployeeCode
     */
    public function setCbookEmployeeCode(?string $cbookEmployeeCode): void
    {
        $this->cbookEmployeeCode = $cbookEmployeeCode;
    }
}