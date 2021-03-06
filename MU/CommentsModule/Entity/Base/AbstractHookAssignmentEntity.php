<?php
/**
 * Comments.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\CommentsModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity base class for hooked object assignments.
 *
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractHookAssignmentEntity extends EntityAccess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $subscriberOwner
     */
    protected $subscriberOwner = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $subscriberAreaId
     */
    protected $subscriberAreaId = '';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $subscriberObjectId
     */
    protected $subscriberObjectId = 0;
    
    /**
     * @ORM\Column(type="array")
     * @Assert\NotNull()
     * @Assert\Type(type="array")
     * @var array $subscriberUrl
     */
    protected $subscriberUrl = [];
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $assignedEntity
     */
    protected $assignedEntity = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $assignedId
     */
    protected $assignedId = '';
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var DateTime $updatedDate
     */
    protected $updatedDate;
    

    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the subscriber owner.
     *
     * @return string
     */
    public function getSubscriberOwner()
    {
        return $this->subscriberOwner;
    }
    
    /**
     * Sets the subscriber owner.
     *
     * @param string $subscriberOwner
     *
     * @return void
     */
    public function setSubscriberOwner($subscriberOwner)
    {
        if ($this->subscriberOwner !== $subscriberOwner) {
            $this->subscriberOwner = isset($subscriberOwner) ? $subscriberOwner : '';
        }
    }
    
    /**
     * Returns the subscriber area id.
     *
     * @return string
     */
    public function getSubscriberAreaId()
    {
        return $this->subscriberAreaId;
    }
    
    /**
     * Sets the subscriber area id.
     *
     * @param string $subscriberAreaId
     *
     * @return void
     */
    public function setSubscriberAreaId($subscriberAreaId)
    {
        if ($this->subscriberAreaId !== $subscriberAreaId) {
            $this->subscriberAreaId = isset($subscriberAreaId) ? $subscriberAreaId : '';
        }
    }
    
    /**
     * Returns the subscriber object id.
     *
     * @return integer
     */
    public function getSubscriberObjectId()
    {
        return $this->subscriberObjectId;
    }
    
    /**
     * Sets the subscriber object id.
     *
     * @param integer $subscriberObjectId
     *
     * @return void
     */
    public function setSubscriberObjectId($subscriberObjectId)
    {
        if (intval($this->subscriberObjectId) !== intval($subscriberObjectId)) {
            $this->subscriberObjectId = intval($subscriberObjectId);
        }
    }
    
    /**
     * Returns the subscriber url.
     *
     * @return array
     */
    public function getSubscriberUrl()
    {
        return $this->subscriberUrl;
    }
    
    /**
     * Sets the subscriber url.
     *
     * @param array $subscriberUrl
     *
     * @return void
     */
    public function setSubscriberUrl($subscriberUrl)
    {
        if ($this->subscriberUrl !== $subscriberUrl) {
            $this->subscriberUrl = isset($subscriberUrl) ? $subscriberUrl : '';
        }
    }
    
    /**
     * Returns the assigned entity.
     *
     * @return string
     */
    public function getAssignedEntity()
    {
        return $this->assignedEntity;
    }
    
    /**
     * Sets the assigned entity.
     *
     * @param string $assignedEntity
     *
     * @return void
     */
    public function setAssignedEntity($assignedEntity)
    {
        if ($this->assignedEntity !== $assignedEntity) {
            $this->assignedEntity = isset($assignedEntity) ? $assignedEntity : '';
        }
    }
    
    /**
     * Returns the assigned id.
     *
     * @return string
     */
    public function getAssignedId()
    {
        return $this->assignedId;
    }
    
    /**
     * Sets the assigned id.
     *
     * @param string $assignedId
     *
     * @return void
     */
    public function setAssignedId($assignedId)
    {
        if ($this->assignedId !== $assignedId) {
            $this->assignedId = isset($assignedId) ? $assignedId : '';
        }
    }
    
    /**
     * Returns the updated date.
     *
     * @return DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    
    /**
     * Sets the updated date.
     *
     * @param DateTime $updatedDate
     *
     * @return void
     */
    public function setUpdatedDate($updatedDate)
    {
        if ($this->updatedDate !== $updatedDate) {
            if (!(null == $updatedDate && empty($updatedDate)) && !(is_object($updatedDate) && $updatedDate instanceOf \DateTimeInterface)) {
                $updatedDate = new \DateTime($updatedDate);
            }
            
            if (null === $updatedDate || empty($updatedDate)) {
                $updatedDate = new \DateTime();
            }
            
            if ($this->updatedDate != $updatedDate) {
                $this->updatedDate = $updatedDate;
            }
        }
    }
    
}
