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
use Sylius\Bundle\ApiBundle\Exception\ProvinceCannotBeRemoved;
use Sylius\Component\Addressing\Checker\CountryProvincesDeletionCheckerInterface;
use Sylius\Component\Addressing\Model\CountryInterface;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    CountryDataPersister::class,
);

/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
final class CountryDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decoratedDataPersister,
        private CountryProvincesDeletionCheckerInterface $countryProvincesDeletionChecker,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof CountryInterface;
    }

    public function persist($data, array $context = [])
    {
        if (!$this->countryProvincesDeletionChecker->isDeletable($data)) {
            throw new ProvinceCannotBeRemoved();
        }

        return $this->decoratedDataPersister->persist($data, $context);
    }

    public function remove($data, array $context = [])
    {
        return $this->decoratedDataPersister->remove($data, $context);
    }
}