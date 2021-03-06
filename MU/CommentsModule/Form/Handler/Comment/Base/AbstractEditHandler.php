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

namespace MU\CommentsModule\Form\Handler\Comment\Base;

use MU\CommentsModule\Form\Handler\Common\EditHandler;
use MU\CommentsModule\Form\Type\CommentType;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use RuntimeException;

/**
 * This handler class handles the page events of editing forms.
 * It aims on the comment object type.
 */
abstract class AbstractEditHandler extends EditHandler
{
    /**
     * @inheritDoc
     */
    public function processForm(array $templateParameters = [])
    {
        $this->objectType = 'comment';
        $this->objectTypeCapital = 'Comment';
        $this->objectTypeLower = 'comment';
        
        $this->hasPageLockSupport = true;
    
        $result = parent::processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
    
        if ('create' == $this->templateParameters['mode']) {
            if (!$this->modelHelper->canBeCreated($this->objectType)) {
                $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('error', $this->__('Sorry, but you can not create the comment yet as other items are required which must be created before!'));
                $logArgs = ['app' => 'MUCommentsModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => $this->objectType];
                $this->logger->notice('{app}: User {user} tried to create a new {entity}, but failed as it other items are required which must be created before.', $logArgs);
    
                return new RedirectResponse($this->getRedirectUrl(['commandName' => '']), 302);
            }
        }
    
        $entityData = $this->entityRef->toArray();
    
        // assign data to template as array (for additions like standard fields)
        $this->templateParameters[$this->objectTypeLower] = $entityData;
        $this->templateParameters['supportsHookSubscribers'] = $this->entityRef->supportsHookSubscribers();
    
        return $result;
    }
    
    /**
     * @inheritDoc
     */
    protected function initRelationPresets()
    {
        $entity = $this->entityRef;
    
        
        // assign identifiers of predefined incoming relationships
        // editable relation, we store the id and assign it now to show it in UI
        $this->relationPresets['comment'] = $this->requestStack->getCurrentRequest()->get('comment', '');
        if (!empty($this->relationPresets['comment'])) {
            $relObj = $this->entityFactory->getRepository('comment')->selectById($this->relationPresets['comment']);
            if (null !== $relObj) {
                $relObj->addComments($entity);
            }
        }
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    }
    
    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        return $this->formFactory->create(CommentType::class, $this->entityRef, $this->getFormOptions());
    }
    
    /**
     * @inheritDoc
     */
    protected function getFormOptions()
    {
        $options = [
            'mode' => $this->templateParameters['mode'],
            'actions' => $this->templateParameters['actions'],
            'has_moderate_permission' => $this->permissionHelper->hasEntityPermission($this->entityRef, ACCESS_ADMIN),
            'allow_moderation_specific_creator' => $this->variableApi->get('MUCommentsModule', 'allowModerationSpecificCreatorFor' . $this->objectTypeCapital, false),
            'allow_moderation_specific_creation_date' => $this->variableApi->get('MUCommentsModule', 'allowModerationSpecificCreationDateFor' . $this->objectTypeCapital, false),
            'filter_by_ownership' => !$this->permissionHelper->hasEntityPermission($this->entityRef, ACCESS_ADD),
            'inline_usage' => $this->templateParameters['inlineUsage']
        ];
    
        $workflowRoles = $this->prepareWorkflowAdditions(false);
        $options = array_merge($options, $workflowRoles);
    
        return $options;
    }

    /**
     * @inheritDoc
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
    
        // user index page of comment area
        $codes[] = 'userIndex';
        // admin index page of comment area
        $codes[] = 'adminIndex';
        // user list of comments
        $codes[] = 'userView';
        // admin list of comments
        $codes[] = 'adminView';
        // user list of own comments
        $codes[] = 'userOwnView';
        // admin list of own comments
        $codes[] = 'adminOwnView';
        // user detail page of treated comment
        $codes[] = 'userDisplay';
        // admin detail page of treated comment
        $codes[] = 'adminDisplay';
    
    
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     *
     * @param array $args List of arguments
     *
     * @return string The default redirect url
     */
    protected function getDefaultReturnUrl(array $args = [])
    {
        $objectIsPersisted = $args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel');
        if (null !== $this->returnTo && $objectIsPersisted) {
            // return to referer
            return $this->returnTo;
        }
    
        $routeArea = array_key_exists('routeArea', $this->templateParameters) ? $this->templateParameters['routeArea'] : '';
        $routePrefix = 'mucommentsmodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // redirect to the list of comments
        $url = $this->router->generate($routePrefix . 'view');
    
        if ($objectIsPersisted) {
            // redirect to the detail page of treated comment
            $url = $this->router->generate($routePrefix . 'display', $this->entityRef->createUrlArgs());
        }
    
        return $url;
    }

    /**
     * @inheritDoc
     */
    public function handleCommand(array $args = [])
    {
        $result = parent::handleCommand($args);
        if (false === $result) {
            return $result;
        }
    
        // build $args for BC (e.g. used by redirect handling)
        foreach ($this->templateParameters['actions'] as $action) {
            if ($this->form->get($action['id'])->isClicked()) {
                $args['commandName'] = $action['id'];
            }
        }
        if ('create' == $this->templateParameters['mode'] && $this->form->has('submitrepeat') && $this->form->get('submitrepeat')->isClicked()) {
            $args['commandName'] = 'submit';
            $this->repeatCreateAction = true;
        }
    
        return new RedirectResponse($this->getRedirectUrl($args), 302);
    }
    
    /**
     * @inheritDoc
     */
    protected function getDefaultMessage(array $args = [], $success = false)
    {
        if (false === $success) {
            return parent::getDefaultMessage($args, $success);
        }
    
        $message = '';
        switch ($args['commandName']) {
            case 'submit':
                if ('create' == $this->templateParameters['mode']) {
                    $message = $this->__('Done! Comment created.');
                } else {
                    $message = $this->__('Done! Comment updated.');
                }
                break;
            case 'delete':
                $message = $this->__('Done! Comment deleted.');
                break;
            default:
                $message = $this->__('Done! Comment updated.');
                break;
        }
    
        return $message;
    }

    /**
     * @inheritDoc
     * @throws RuntimeException Thrown if concurrent editing is recognised or another error occurs
     */
    public function applyAction(array $args = [])
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $action = $args['commandName'];
    
        $success = false;
        $flashBag = $this->requestStack->getCurrentRequest()->getSession()->getFlashBag();
        try {
            // execute the workflow action
            $success = $this->workflowHelper->executeAction($entity, $action);
        } catch (\Exception $exception) {
            $flashBag->add('error', $this->__f('Sorry, but an error occured during the %action% action. Please apply the changes again!', ['%action%' => $action]) . ' ' . $exception->getMessage());
            $logArgs = ['app' => 'MUCommentsModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => 'comment', 'id' => $entity->getKey(), 'errorMessage' => $exception->getMessage()];
            $this->logger->error('{app}: User {user} tried to edit the {entity} with id {id}, but failed. Error details: {errorMessage}.', $logArgs);
        }
    
        $this->addDefaultMessage($args, $success);
    
        if ($success && 'create' == $this->templateParameters['mode']) {
            // store new identifier
            $this->idValue = $entity->getKey();
        }
    
        return $success;
    }

    /**
     * Get URL to redirect to.
     *
     * @param array $args List of arguments
     *
     * @return string The redirect url
     */
    protected function getRedirectUrl(array $args = [])
    {
        if (isset($this->templateParameters['inlineUsage']) && true === $this->templateParameters['inlineUsage']) {
            $commandName = substr($args['commandName'], 0, 6) == 'submit' ? 'create' : $args['commandName'];
            $urlArgs = [
                'idPrefix' => $this->idPrefix,
                'commandName' => $commandName,
                'id' => $this->idValue
            ];
    
            // inline usage, return to special function for closing the modal window instance
            return $this->router->generate('mucommentsmodule_' . $this->objectTypeLower . '_handleinlineredirect', $urlArgs);
        }
    
        if ($this->repeatCreateAction) {
            return $this->repeatReturnUrl;
        }
    
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if ($session->has('mucommentsmodule' . $this->objectTypeCapital . 'Referer')) {
            $this->returnTo = $session->get('mucommentsmodule' . $this->objectTypeCapital . 'Referer');
            $session->remove('mucommentsmodule' . $this->objectTypeCapital . 'Referer');
        }
    
        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args);
        }
    
        $routeArea = substr($this->returnTo, 0, 5) == 'admin' ? 'admin' : '';
        $routePrefix = 'mucommentsmodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'userIndex':
            case 'adminIndex':
                return $this->router->generate($routePrefix . 'index');
            case 'userView':
            case 'adminView':
                return $this->router->generate($routePrefix . 'view');
            case 'userOwnView':
            case 'adminOwnView':
                return $this->router->generate($routePrefix . 'view', [ 'own' => 1 ]);
            case 'userDisplay':
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel')) {
                    return $this->router->generate($routePrefix . 'display', $this->entityRef->createUrlArgs());
                }
    
                return $this->getDefaultReturnUrl($args);
            default:
                return $this->getDefaultReturnUrl($args);
        }
    }
}
