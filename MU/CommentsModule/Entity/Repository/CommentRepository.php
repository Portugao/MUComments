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

namespace MU\CommentsModule\Entity\Repository;

use MU\CommentsModule\Entity\Repository\Base\AbstractCommentRepository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the concrete repository class for comment entities.
 */
class CommentRepository extends AbstractCommentRepository
{    
	/**
	 * @var string The default sorting field/expression
	 */
	protected $defaultSortingField = 'createdDate';
	
	/**
	 * @var VariableApiInterface
	 */
	protected $variableApi;
	
    /**
     * Adds an array of id filters to given query instance.
     *
     * @param array        $idList List of identifiers to use to retrieve the object
     * @param QueryBuilder $qb     Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    protected function addIdListFilter(array $idList, QueryBuilder $qb)
    {
    	$orX = $qb->expr()->orX();
    
    	foreach ($idList as $id) {
    		// check id parameter
    		if ($id == 0) {
    			throw new InvalidArgumentException('Invalid identifier received.');
    		}
    
    		$orX->add($qb->expr()->eq('tbl.id', $id));
    	}
    	
    	//$sortDir = $this->variableApi->get('MUCommentsModule', 'orderComments');
    
    	$qb->andWhere($orX);
    	$qb->andWhere('tbl.comment is NULL');
    	$qb->add('orderBy', 'tbl.createdDate DESC');
    
    	return $qb;
    }
}
