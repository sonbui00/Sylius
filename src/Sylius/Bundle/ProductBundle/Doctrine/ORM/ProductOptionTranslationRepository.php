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

namespace Sylius\Bundle\ProductBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductOptionTranslationInterface;
use Sylius\Component\Product\Repository\ProductOptionTranslationRepositoryInterface;

/**
 * @template T of ProductOptionTranslationInterface
 *
 * @implements ProductOptionTranslationRepositoryInterface<T>
 */
class ProductOptionTranslationRepository extends EntityRepository implements ProductOptionTranslationRepositoryInterface
{
}