<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class FortisGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Keys here become Payum gateway config keys (persisted on GatewayConfig)
        $builder
            ->add('developer_id', TextType::class, [
                'label' => 'Developer ID',
                'required' => true,
            ])
            ->add('user_id', TextType::class, [
                'label' => 'User ID',
                'required' => true,
            ])
            ->add('user_api_key', TextType::class, [
                'label' => 'User API Key',
                'required' => true,
            ])
            ->add('location_id', TextType::class, [
                'label' => 'Default Location ID (optional)',
                'required' => false,
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'Use Sandbox',
                'required' => false,
            ]);
    }

    public function getBlockPrefix(): string
    {
        // Any unique prefix is fine
        return 'vendor_fortis_gateway_configuration';
    }
}
