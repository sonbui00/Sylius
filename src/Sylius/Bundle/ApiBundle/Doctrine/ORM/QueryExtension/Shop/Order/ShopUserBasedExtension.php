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

namespace Sylius\Bundle\ApiBundle\Doctrine\ORM\QueryExtension\Shop\Order;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Bundle\ApiBundle\SectionResolver\ShopApiSection;
use Sylius\Bundle\ApiBundle\Serializer\ContextKeys;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class ShopUserBasedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(
        private SectionProviderInterface $sectionProvider,
        private UserContextInterface $userContext,
    ) {
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        $this->filterOutOrders($queryBuilder, $queryNameGenerator, $resourceClass);
    }

    /**
     * @param array<array-key, mixed> $identifiers
     * @param array<array-key, mixed> $context
     */
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        $this->filterOutOrders($queryBuilder, $queryNameGenerator, $resourceClass);
    }

    private function filterOutOrders(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
    ): void {
        if (!is_a($resourceClass, OrderInterface::class, true)) {
            return;
        }

        if (!$this->sectionProvider->getSection() instanceof ShopApiSection) {
            return;
        }

        $user = $this->userContext->getUser();

        if (!$user instanceof ShopUserInterface) {
            return;
        }

        $customerParameterName = $queryNameGenerator->generateParameterName('customer');
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq(sprintf('%s.customer', $rootAlias), sprintf(':%s', $customerParameterName)))
            ->setParameter($customerParameterName, $user->getCustomer())
        ;
    }
}
