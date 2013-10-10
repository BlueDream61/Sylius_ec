<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\WebBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DateTime;

/**
 * Backend dashboard controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class DashboardController extends Controller
{
    /**
     * Backend dashboard display action.
     *
     * @return Response
     */
    public function mainAction()
    {
        $orderRepository = $this->get('sylius.repository.order');
        $userRepository = $this->get('sylius.repository.user');

        return $this->render('SyliusWebBundle:Backend/Dashboard:main.html.twig', array(
            'orders_count' => $orderRepository->countBetweenDates(new DateTime('1 week ago'), new DateTime()),
            'orders'       => $orderRepository->findBy(array(), array('updatedAt' => 'desc'), 5),
            'users'        => $userRepository->findBy(array(), array('id' => 'desc'), 5),
        ));
    }
}
