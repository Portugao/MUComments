<?php
/**
 * Comments.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio 1.0.1 (https://modulestudio.de).
 */

namespace MU\CommentsModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use MU\CommentsModule\Entity\CommentEntity;

/**
 * Entity collection filter helper base class.
 */
abstract class AbstractCollectionFilterHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;

    /**
     * @var bool Fallback value to determine whether only own entries should be selected or not
     */
    protected $showOnlyOwnEntries = false;

    /**
     * CollectionFilterHelper constructor.
     *
     * @param RequestStack $requestStack RequestStack service instance
     * @param CurrentUserApiInterface $currentUserApi CurrentUserApi service instance
     * @param bool           $showOnlyOwnEntries  Fallback value to determine whether only own entries should be selected or not
     */
    public function __construct(
        RequestStack $requestStack,
        CurrentUserApiInterface $currentUserApi,
        $showOnlyOwnEntries
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->currentUserApi = $currentUserApi;
        $this->showOnlyOwnEntries = $showOnlyOwnEntries;
    }

    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $objectType Name of treated entity type
     * @param string $context    Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args       Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    public function getViewQuickNavParameters($objectType = '', $context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        if ($objectType == 'comment') {
            return $this->getViewQuickNavParametersForComment($context, $args);
        }
    
        return [];
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCommonViewFilters($objectType, QueryBuilder $qb)
    {
        if ($objectType == 'comment') {
            return $this->addCommonViewFiltersForComment($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function applyDefaultFilters($objectType, QueryBuilder $qb, $parameters = [])
    {
        if ($objectType == 'comment') {
            return $this->applyDefaultFiltersForComment($qb, $parameters);
        }
    
        return $qb;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForComment($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['q'] = $this->request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForComment(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForComment();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('comment', $qb, $v);
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForComment($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForComment(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'mucommentsmodule_comment_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if (!in_array('workflowState', array_keys($parameters)) || empty($parameters['workflowState'])) {
            // per default we show approved comments only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds a where clause for search query.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param string       $fragment   The fragment to search for
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addSearchFilter($objectType, QueryBuilder $qb, $fragment = '')
    {
        if ($fragment == '') {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
        if ($objectType == 'comment') {
            $filters[] = 'tbl.workflowState = :searchWorkflowState';
            $parameters['searchWorkflowState'] = $fragment;
            $filters[] = 'tbl.title LIKE :searchTitle';
            $parameters['searchTitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.name LIKE :searchName';
            $parameters['searchName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.yourMailAddress = :searchYourMailAddress';
            $parameters['searchYourMailAddress'] = $fragment;
            $filters[] = 'tbl.homepage = :searchHomepage';
            $parameters['searchHomepage'] = $fragment;
            $filters[] = 'tbl.text LIKE :searchText';
            $parameters['searchText'] = '%' . $fragment . '%';
            $filters[] = 'tbl.parentid = :searchParentid';
            $parameters['searchParentid'] = $fragment;
            $filters[] = 'tbl.mainId = :searchMainId';
            $parameters['searchMainId'] = $fragment;
        }
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')');
    
        foreach ($parameters as $parameterName => $parameterValue) {
            $qb->setParameter($parameterName, $parameterValue);
        }
    
        return $qb;
    }
    
    /**
     * Adds a filter for the createdBy field.
     *
     * @param QueryBuilder $qb     Query builder to be enhanced
     * @param integer      $userId The user identifier used for filtering
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCreatorFilter(QueryBuilder $qb, $userId = null)
    {
        if (null === $userId) {
            $userId = $this->currentUserApi->isLoggedIn() ? $this->currentUserApi->get('uid') : UsersConstant::USER_ID_ANONYMOUS;
        }
    
        if (is_array($userId)) {
            $qb->andWhere('tbl.createdBy IN (:userIds)')
               ->setParameter('userIds', $userId);
        } else {
            $qb->andWhere('tbl.createdBy = :userId')
               ->setParameter('userId', $userId);
        }
    
        return $qb;
    }
}
