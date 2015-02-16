<?php

namespace FollowMe\Bundle\ApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DoorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('id', null, array(
            'description' => "Door's id",
            'required' => true,
        ));

        $builder->add('id_sensor_1', null, array(
            'description' => 'Sensor1 id',
            'required' => true,
        ));

        $builder->add('id_sensor_2', null, array(
            'description' => 'Sensor2 id',
            'required' => true,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FollowMe\Bundle\ModelBundle\Entity\Door',
            'csrf_protection'   => false,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'door';
    }
}
