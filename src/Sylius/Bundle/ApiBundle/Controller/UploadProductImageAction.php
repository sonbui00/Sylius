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

namespace Sylius\Bundle\ApiBundle\Controller;

use Sylius\Bundle\ApiBundle\Creator\ImageCreatorInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\Request;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    UploadProductImageAction::class,
);

/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
final class UploadProductImageAction
{
    public function __construct(private ImageCreatorInterface $productImageCreator)
    {
    }

    public function __invoke(Request $request): ImageInterface
    {
        return $this->productImageCreator->create(
            $request->attributes->get('code', ''),
            $request->files->get('file'),
            $request->request->get('type'),
            ['productVariants' => $request->request->all('productVariants')],
        );
    }
}