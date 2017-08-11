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

namespace MU\CommentsModule\Form\Type\Hook\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\IdentityTranslator;

/**
 * Delete comment form type base class.
 */
abstract class AbstractDeleteCommentType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['translator'];
        $builder
            ->add('dummyName', TextType::class, [
                'label' => $translator->__('Dummy comment text'),
                'required' => true
            ])
            ->add('dummmyChoice', ChoiceType::class, [
                'label' => $translator->__('Dummy comment choice'),
                'choices' => [
                    $translator->__('Option A') => 'A',
                    $translator->__('Option A') => 'B',
                    $translator->__('Option A') => 'C'
                ],
                'choices_as_values' => true,
                'required' => true,
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mucommentsmodule_hook_deletecomment';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translator' => new IdentityTranslator()
        ]);
    }
}
