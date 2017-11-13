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

namespace MU\CommentsModule\Form\Type\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use MU\CommentsModule\Form\Type\Field\MultiListType;
use MU\CommentsModule\AppSettings;
use MU\CommentsModule\Helper\ListEntriesHelper;

/**
 * Configuration form type base class.
 */
abstract class AbstractConfigType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * ConfigType constructor.
     *
     * @param TranslatorInterface $translator Translator service instance
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        ListEntriesHelper $listHelper
    ) {
        $this->setTranslator($translator);
        $this->listHelper = $listHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addGeneralSettingFields($builder, $options);
        $this->addSpamhandlingFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addListViewsFields($builder, $options);
        $this->addIntegrationFields($builder, $options);

        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds fields for general setting fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addGeneralSettingFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('logIp', CheckboxType::class, [
            'label' => $this->__('Log ip') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('The log ip option')
            ],
            'required' => false,
        ]);
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'orderComments');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('orderComments', ChoiceType::class, [
            'label' => $this->__('Order comments') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the order comments.')
            ],
            'required' => true,
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'levelsOfComments');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('levelsOfComments', ChoiceType::class, [
            'label' => $this->__('Levels of comments') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the levels of comments.')
            ],
            'required' => true,
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'positionOfForm');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfForm', ChoiceType::class, [
            'label' => $this->__('Position of form') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of form.')
            ],
            'required' => true,
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds fields for spamhandling fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSpamhandlingFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'spamProtector');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('spamProtector', ChoiceType::class, [
            'label' => $this->__('Spam protector') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the spam protector.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds fields for moderation fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('moderationGroupForComments', EntityType::class, [
            'label' => $this->__('Moderation group for comments') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Used to determine moderator user accounts for sending email notifications.')
            ],
            'help' => $this->__('Used to determine moderator user accounts for sending email notifications.'),
            'empty_data' => '2',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Choose the moderation group for comments')
            ],
            'required' => true,
            // Zikula core should provide a form type for this to hide entity details
            'class' => 'ZikulaGroupsModule:GroupEntity',
            'choice_label' => 'name',
            'choice_value' => 'gid'
        ]);
    }

    /**
     * Adds fields for list views fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('commentEntriesPerPage', IntegerType::class, [
            'label' => $this->__('Comment entries per page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('The amount of comments shown per page')
            ],
            'help' => $this->__('The amount of comments shown per page'),
            'empty_data' => '10',
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => $this->__('Enter the comment entries per page.') . ' ' . $this->__('Only digits are allowed.')
            ],
            'required' => true,
            'scale' => 0
        ]);
        
        $builder->add('linkOwnCommentsOnAccountPage', CheckboxType::class, [
            'label' => $this->__('Link own comments on account page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to add a link to comments of the current user on his account page')
            ],
            'help' => $this->__('Whether to add a link to comments of the current user on his account page'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The link own comments on account page option')
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for integration fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIntegrationFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'enabledFinderTypes');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('enabledFinderTypes', MultiListType::class, [
            'label' => $this->__('Enabled finder types') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Which sections are supported in the Finder component (used by Scribite plug-ins).')
            ],
            'help' => $this->__('Which sections are supported in the Finder component (used by Scribite plug-ins).'),
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the enabled finder types.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('save', SubmitType::class, [
            'label' => $this->__('Update configuration'),
            'icon' => 'fa-check',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);
        $builder->add('reset', ResetType::class, [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mucommentsmodule_config';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data
                'data_class' => AppSettings::class,
            ]);
    }
}
