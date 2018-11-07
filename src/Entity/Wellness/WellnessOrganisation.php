<?php
namespace App\Entity\Wellness;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="organisation__organisation", uniqueConstraints={@ORM\UniqueConstraint(name="org_code_idx", columns={"code"})})
 */
class WellnessOrganisation
{
    const TYPE_BUSINESS_SALES_PARTNER = 'BUSINESS_SALES_PARTNER_TPYE';
    const TYPE_CONSUMER_SALES_PARTNER = 'CONSUMER_SALES_PARTNER_TPYE';

    const TYPE_CONSUMER_CHANNEL_PARTNER = 'CONSUMER_CHANNEL_PARTNER_TYPE';
    const TYPE_BUSINESS_CHANNEL_PARTNER = 'BUSINESS_CHANNEL_PARTNER_TYPE';
    const TYPE_EMPLOYER = 'EMPLOYER_TYPE';
    const TYPE_CLINIC = 'CLINIC_TYPE';
    const TYPE_PRINCIPAL = 'PRINCIPAL_TYPE';

    function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->positions = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->enabled = true;
        $this->typeConsumerChannelPartner = false;
        $this->typeBusinessChannelPartner = false;
        $this->typeConsumerSalesPartner = false;
        $this->typeBusinessSalesPartner = false;
        $this->typeEmployer = false;
        $this->typePrincipal = false;
        $this->typeSalesPartner = false;
        $this->typeClinic = false;
    }
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected
        $id;

    /**
     * @var WellnessEmployer
     * @ORM\OneToOne(targetEntity="App\Entity\Wellness\WellnessEmployer", mappedBy="organisation", cascade={"all"}, orphanRemoval=true)
     */
    private
        $employer;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime", name="created_at")
     */
    private
        $createdAt;


    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $synchronisedAt;
    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private
        $updatedAt;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", name="cbook_id", nullable=true)
     */
    protected $cbookId;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", name="linked_to_cbook", nullable=true)
     */
    protected $linkedToCBook;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="enabled", options={"default":false})
     */
    protected
        $enabled;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_clinic", options={"default":false})
     */
    protected
        $typeClinic;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_sales_partner", options={"default":false})
     */
    protected
        $typeSalesPartner;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_sales_partner_consumer", options={"default":false})
     */
    protected
        $typeConsumerSalesPartner;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_sales_partner_business", options={"default":false})
     */
    protected
        $typeBusinessSalesPartner;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_channel_partner_consumer", options={"default":false})
     */
    protected
        $typeConsumerChannelPartner;
    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_business_channel_partner", options={"default":false})
     */
    protected
        $typeBusinessChannelPartner;
    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_employer", options={"default":false})
     */
    protected
        $typeEmployer;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="type_principal", options={"default":false})
     */
    protected
        $typePrincipal;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $subdomain;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $hotline;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $postalCode;


    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $country;


    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $region;


    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $area;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $oh26;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $ohSat;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $ohSun;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $ohPublicHolidays;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $ohRemarks;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $officeNo;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $address;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $fax;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected
        $doctorNames;

    /**
     * @var string
     * @ORM\Column(length=150, name="reg_no", nullable=true)
     */
    protected
        $regNo;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminName;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminFirstname;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminMiddlename;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminLastname;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminPhone;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $adminEmail;

    /**
     * @var string
     * @ORM\Column(length=150)
     */
    protected
        $slug;

    /**
     * @var string
     * @ORM\Column(length=150)
     */
    protected
        $name;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected
        $code;

//////////////////////////////    //////////////////////////////
//////////////////////////////    //////////////////////////////
//////////////////////////////    //////////////////////////////

    /**
     * @return int
     */
    public
    function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public
    function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param ArrayCollection $locations
     */
    public
    function setLocations(
        $locations
    )
    {
        $this->locations = $locations;
    }

    /**
     * @return ArrayCollection
     */
    public
    function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param ArrayCollection $positions
     */
    public
    function setPositions(
        $positions
    )
    {
        $this->positions = $positions;
    }

    /**
     * @return Media
     */
    public
    function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param Media $logo
     */
    public
    function setLogo(
        $logo
    )
    {
        $this->logo = $logo;
    }

    /**
     * @return ChannelPartner
     */
    public
    function getChannelPartner()
    {
        return $this->channelPartner;
    }

    /**
     * @param ChannelPartner $channelPartner
     */
    public
    function setChannelPartner(
        $channelPartner
    )
    {
        $this->channelPartner = $channelPartner;
    }

    /**
     * @return WellnessEmployer
     */
    public
    function getEmployer()
    {
        return $this->employer;
    }

    /**
     * @param WellnessEmployer $employer
     */
    public
    function setEmployer(
        $employer
    )
    {
        $this->employer = $employer;
    }

    /**
     * @return Principal
     */
    public
    function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * @param Principal $principal
     */
    public
    function setPrincipal(
        $principal
    )
    {
        $this->principal = $principal;
    }

    /**
     * @return SalesPartner
     */
    public
    function getSalesPartner()
    {
        return $this->salesPartner;
    }

    /**
     * @param SalesPartner $salesPartner
     */
    public
    function setSalesPartner(
        $salesPartner
    )
    {
        $this->salesPartner = $salesPartner;
    }

    /**
     * @return Clinic
     */
    public
    function getClinic()
    {
        return $this->clinic;
    }

    /**
     * @param Clinic $clinic
     */
    public
    function setClinic(
        $clinic
    )
    {
        $this->clinic = $clinic;
    }

    /**
     * @return \DateTime
     */
    public
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public
    function setCreatedAt(
        $createdAt
    )
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public
    function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public
    function setEnabled(
        $enabled
    )
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public
    function isTypeConsumerChannelPartner()
    {
        return $this->typeConsumerChannelPartner;
    }


    /**
     * @return bool
     */
    public
    function isTypeBusinessChannelPartner()
    {
        return $this->typeBusinessChannelPartner;
    }

    /**
     * @return bool
     */
    public
    function isTypeEmployer()
    {
        return $this->typeEmployer;
    }

    /**
     * @return string
     */
    public
    function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public
    function setPostalCode(
        $postalCode
    )
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public
    function getOh26()
    {
        return $this->oh26;
    }

    /**
     * @param string $oh26
     */
    public
    function setOh26(
        $oh26
    )
    {
        $this->oh26 = $oh26;
    }

    /**
     * @return string
     */
    public
    function getOhSat()
    {
        return $this->ohSat;
    }

    /**
     * @param string $ohSat
     */
    public
    function setOhSat(
        $ohSat
    )
    {
        $this->ohSat = $ohSat;
    }

    /**
     * @return string
     */
    public
    function getOhSun()
    {
        return $this->ohSun;
    }

    /**
     * @param string $ohSun
     */
    public
    function setOhSun(
        $ohSun
    )
    {
        $this->ohSun = $ohSun;
    }

    /**
     * @return string
     */
    public
    function getOhPublicHolidays()
    {
        return $this->ohPublicHolidays;
    }

    /**
     * @param string $ohPublicHolidays
     */
    public
    function setOhPublicHolidays(
        $ohPublicHolidays
    )
    {
        $this->ohPublicHolidays = $ohPublicHolidays;
    }

    /**
     * @return string
     */
    public
    function getOhRemarks()
    {
        return $this->ohRemarks;
    }

    /**
     * @param string $ohRemarks
     */
    public
    function setOhRemarks(
        $ohRemarks
    )
    {
        $this->ohRemarks = $ohRemarks;
    }

    /**
     * @return string
     */
    public
    function getOfficeNo()
    {
        return $this->officeNo;
    }

    /**
     * @param string $officeNo
     */
    public
    function setOfficeNo(
        $officeNo
    )
    {
        $this->officeNo = $officeNo;
    }

    /**
     * @return string
     */
    public
    function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public
    function setAddress(
        $address
    )
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public
    function getRegNo()
    {
        return $this->regNo;
    }

    /**
     * @param string $regNo
     */
    public
    function setRegNo(
        $regNo
    )
    {
        $this->regNo = $regNo;
    }

    /**
     * @return string
     */
    public
    function getAdminPhone()
    {
        return $this->adminPhone;
    }

    /**
     * @param string $adminPhone
     */
    public
    function setAdminPhone(
        $adminPhone
    )
    {
        $this->adminPhone = $adminPhone;
    }

    /**
     * @return string
     */
    public
    function getAdminEmail()
    {
        return $this->adminEmail;
    }

    /**
     * @param string $adminEmail
     */
    public
    function setAdminEmail(
        $adminEmail
    )
    {
        $this->adminEmail = $adminEmail;
    }

    /**
     * @return string
     */
    public
    function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public
    function setSlug(
        $slug
    )
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public
    function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public
    function setName(
        $name
    )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public
    function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public
    function setCode(
        $code
    )
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public
    function isTypeClinic()
    {
        return $this->typeClinic;
    }

    /**
     * @return bool
     */
    public
    function isTypePrincipal()
    {
        return $this->typePrincipal;
    }

    /**
     * @return string
     */
    public
    function getAdminFirstname()
    {
        return $this->adminFirstname;
    }

    /**
     * @return string
     */
    public
    function getAdminMiddlename()
    {
        return $this->adminMiddlename;
    }

    /**
     * @return string
     */
    public
    function getAdminLastname()
    {
        return $this->adminLastname;
    }

    /**
     * @return ArrayCollection
     */
    public
    function getSenderPayments()
    {
        return $this->senderPayments;
    }

    /**
     * @param ArrayCollection $senderPayments
     */
    public
    function setSenderPayments(
        $senderPayments
    )
    {
        $this->senderPayments = $senderPayments;
    }

    /**
     * @return ArrayCollection
     */
    public
    function getReceiverPayments()
    {
        return $this->receiverPayments;
    }

    /**
     * @param ArrayCollection $receiverPayments
     */
    public
    function setReceiverPayments(
        $receiverPayments
    )
    {
        $this->receiverPayments = $receiverPayments;
    }

    /**
     * @return string
     */
    public
    function getHotline()
    {
        return $this->hotline;
    }

    /**
     * @param string $hotline
     */
    public
    function setHotline(
        $hotline
    )
    {
        $this->hotline = $hotline;
    }

    /**
     * @return bool
     */
    public
    function isTypeConsumerSalesPartner()
    {
        return $this->typeConsumerSalesPartner;
    }

    /**
     * @return bool
     */
    public
    function isTypeBusinessSalesPartner()
    {
        return $this->typeBusinessSalesPartner;
    }

    /**
     * @return OrganisationSetting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getDoctorNames()
    {
        return $this->doctorNames;
    }

    /**
     * @param string $doctorNames
     */
    public function setDoctorNames($doctorNames)
    {
        $this->doctorNames = $doctorNames;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * @return int|null
     */
    public function getCbookId(): ?int
    {
        return $this->cbookId;
    }

    /**
     * @param int|null $cbookId
     */
    public function setCbookId(?int $cbookId): void
    {
        $this->cbookId = $cbookId;
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
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isTypeSalesPartner(): bool
    {
        return $this->typeSalesPartner;
    }

    /**
     * @param bool $typeSalesPartner
     */
    public function setTypeSalesPartner(bool $typeSalesPartner): void
    {
        $this->typeSalesPartner = $typeSalesPartner;
    }

    /**
     * @return string
     */
    public function getAdminName(): string
    {
        return $this->adminName;
    }

    /**
     * @param string $adminName
     */
    public function setAdminName(string $adminName): void
    {
        $this->adminName = $adminName;
    }

    /**
     * @return bool
     */
    public function isLinkedToCBook(): bool
    {
        if ($this->linkedToCBook === null) {
            return false;
        }
        return $this->linkedToCBook;
    }

    /**
     * @return bool|null
     */
    public function getLinkedToCBook(): ?bool
    {
        return $this->linkedToCBook;
    }

    /**
     * @param bool|null $linkedToCBook
     */
    public function setLinkedToCBook(?bool $linkedToCBook): void
    {
        $this->linkedToCBook = $linkedToCBook;
    }

}