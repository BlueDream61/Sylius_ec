<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Admin\Customer;

use Sylius\Behat\Behaviour\Toggles;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use Toggles;

    /**
     * {@inheritdoc}
     */
    public function getFullName()
    {
        $firstNameElement = $this->getElement('first name')->getValue();
        $lastNameElement = $this->getElement('last name')->getValue();

        return sprintf('%s %s', $firstNameElement, $lastNameElement);
    }

    /**
     * {@inheritdoc}
     */
    public function changeFirstName($firstName)
    {
        $this->getDocument()->fillField('First name', $firstName);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->getElement('first name')->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function changeLastName($lastName)
    {
        $this->getDocument()->fillField('Last name', $lastName);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->getElement('last name')->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function changeEmail($email)
    {
        $this->getDocument()->fillField('Email', $email);
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword($password)
    {
        $this->getDocument()->fillField('Password', $password);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->getElement('password');
    }

    /**
     * {@inheritdoc}
     */
    protected function getToggleableElement()
    {
        return $this->getElement('enabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'email' => '#sylius_customer_email',
            'first name' => '#sylius_customer_firstName',
            'last name' => '#sylius_customer_lastName',
            'enabled' => '#sylius_customer_user_enabled',
            'password' => '#sylius_customer_user_password',
        ]);
    }
}
