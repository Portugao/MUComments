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

namespace MU\CommentsModule\ContentType\Form\Type\Base;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Content\AbstractContentFormType;
use Zikula\Common\Content\ContentTypeInterface;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * List content type form type base class.
 */
abstract class AbstractItemListType extends AbstractContentFormType
{
    /**
     * ItemListType constructor.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectTypeField($builder, $options);
        $this->addSortingField($builder, $options);
        $this->addAmountField($builder, $options);
        $this->addTemplateFields($builder, $options);
        $this->addFilterField($builder, $options);
    }

    /**
     * Adds an object type field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addObjectTypeField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('objectType', HiddenType::class, [
            'label' => $this->__('Object type', 'mucommentsmodule') . ':',
            'empty_data' => 'comment'
        ]);
    }

    /**
     * Adds a sorting field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSortingField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('sorting', ChoiceType::class, [
            'label' => $this->__('Sorting', 'mucommentsmodule') . ':',
            'empty_data' => 'default',
            'choices' => [
                $this->__('Random', 'mucommentsmodule') => 'random',
                $this->__('Newest', 'mucommentsmodule') => 'newest',
                $this->__('Updated', 'mucommentsmodule') => 'updated',
                $this->__('Default', 'mucommentsmodule') => 'default'
            ],
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds a page size field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addAmountField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('amount', IntegerType::class, [
            'label' => $this->__('Amount', 'mucommentsmodule') . ':',
            'attr' => [
                'maxlength' => 2,
                'title' => $this->__('The maximum amount of items to be shown.', 'mucommentsmodule') . ' ' . $this->__('Only digits are allowed.', 'mucommentsmodule')
            ],
            'help' => $this->__('The maximum amount of items to be shown.', 'mucommentsmodule') . ' ' . $this->__('Only digits are allowed.', 'mucommentsmodule'),
            'empty_data' => 5,
            'scale' => 0
        ]);
    }

    /**
     * Adds template fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addTemplateFields(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('template', ChoiceType::class, [
                'label' => $this->__('Template', 'mucommentsmodule') . ':',
                'empty_data' => 'itemlist_display.html.twig',
                'choices' => [
                    $this->__('Only item titles', 'mucommentsmodule') => 'itemlist_display.html.twig',
                    $this->__('With description', 'mucommentsmodule') => 'itemlist_display_description.html.twig',
                    $this->__('Custom template', 'mucommentsmodule') => 'custom'
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('customTemplate', TextType::class, [
                'label' => $this->__('Custom template', 'mucommentsmodule') . ':',
                'required' => false,
                'attr' => [
                    'maxlength' => 80,
                    'title' => $this->__('Example', 'mucommentsmodule') . ': itemlist_[objectType]_display.html.twig'
                ],
                'help' => $this->__('Example', 'mucommentsmodule') . ': <em>itemlist_[objectType]_display.html.twig</em>'
            ])
        ;
    }

    /**
     * Adds a filter field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addFilterField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('filter', TextType::class, [
            'label' => $this->__('Filter (expert option)', 'mucommentsmodule') . ':',
            'required' => false,
            'attr' => [
                'maxlength' => 255,
                'title' => $this->__('Example', 'mucommentsmodule') . ': tbl.age >= 18'
            ],
            'help' => $this->__('Example', 'mucommentsmodule') . ': tbl.age >= 18'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mucommentsmodule_contenttype_list';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'context' => ContentTypeInterface::CONTEXT_EDIT,
                'object_type' => 'comment'
            ])
            ->setRequired(['object_type'])
            ->setAllowedTypes('context', 'string')
            ->setAllowedTypes('object_type', 'string')
            ->setAllowedValues('context', [ContentTypeInterface::CONTEXT_EDIT, ContentTypeInterface::CONTEXT_TRANSLATION])
        ;
    }
}
