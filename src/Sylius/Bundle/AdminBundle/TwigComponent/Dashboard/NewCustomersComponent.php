<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\AdminBundle\TwigComponent\Dashboard;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

final class NewCustomersComponent
{
    public const DEFAULT_LIMIT = 5;

    public int $limit = self::DEFAULT_LIMIT;

    /**
     * @param CustomerRepositoryInterface<CustomerInterface> $customerRepository
     */
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {
    }

    /**
     * @return array<CustomerInterface>
     */
    #[ExposeInTemplate]
    public function getNewCustomers(): array
    {
        return $this->customerRepository->findLatest($this->limit);
    }
}
