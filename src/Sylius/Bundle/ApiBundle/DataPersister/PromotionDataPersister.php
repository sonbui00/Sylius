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

namespace Sylius\Bundle\ApiBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sylius\Bundle\ApiBundle\Exception\PromotionCannotBeRemoved;
use Sylius\Component\Core\Model\PromotionInterface;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    PromotionDataPersister::class,
);

/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
final class PromotionDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private ContextAwareDataPersisterInterface $decoratedDataPersister)
    {
    }

    /** @param array<string, mixed> $context */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof PromotionInterface;
    }

    /** @param array<string, mixed> $context */
    public function persist($data, array $context = [])
    {
        return $this->decoratedDataPersister->persist($data, $context);
    }

    /** @param array<string, mixed> $context */
    public function remove($data, array $context = []): void
    {
        try {
            $this->decoratedDataPersister->remove($data, $context);
        } catch (ForeignKeyConstraintViolationException) {
            throw new PromotionCannotBeRemoved();
        }
    }
}