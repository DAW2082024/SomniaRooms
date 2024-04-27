<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new ('username');
        yield TextField::new ('username')->onlyWhenUpdating()->setDisabled(true);

        $availableRoles = ['User' => 'ROLE_USER', 'Admin' => 'ROLE_ADMIN', 'Test' => 'ROLE_TEST', 'SuperAdmin' => 'ROLE_SUPERADMIN'];
        yield ChoiceField::new ('roles')
            ->setChoices($availableRoles)
            ->allowMultipleChoices(true);


        yield FormField::addPanel('Set password')->setIcon('fa fa-key');

        $fieldPassword = Field::new ('plainPassword', 'New password')
            ->hideOnIndex()
            ->setRequired(false)
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'New password'],
                'second_options' => ['label' => 'Repeat password'],
                'error_bubbling' => true,
                'invalid_message' => 'The password fields do not match.',
            ]);
        if($pageName == Crud::PAGE_NEW) {
            $fieldPassword->setRequired(true);
        }

        yield $fieldPassword;
    }


    /**
     * @param User $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //Hash password on creation.
        $plaintextPassword = $entityInstance->getPlainPassword();
        
        $hashedPassword = $this->userPasswordHasher->hashPassword($entityInstance, $plaintextPassword);
        $entityInstance->setPassword($hashedPassword);
        
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * @param User $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $plaintextPassword = $entityInstance->getPlainPassword();

        if($plaintextPassword && $plaintextPassword != "") {
            //New password set. Hash password.
            $hashedPassword = $this->userPasswordHasher->hashPassword($entityInstance, $plaintextPassword);
            $entityInstance->setPassword($hashedPassword);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
