<?php
/**
 * Comments.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\CommentsModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use MU\CommentsModule\Entity\CommentEntity;

/**
 * Event base class for filtering comment processing.
 */
class AbstractFilterCommentEvent extends Event
{
    /**
     * @var CommentEntity Reference to treated entity instance.
     */
    protected $comment;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    /**
     * FilterCommentEvent constructor.
     *
     * @param CommentEntity $comment Processed entity
     * @param array $entityChangeSet Change set for preUpdate events
     */
    public function __construct(CommentEntity $comment, $entityChangeSet = [])
    {
        $this->comment = $comment;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * Returns the entity.
     *
     * @return CommentEntity
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Returns the change set.
     *
     * @return array
     */
    public function getEntityChangeSet()
    {
        return $this->entityChangeSet;
    }
}
