<?php

declare(strict_types=1);

namespace PhpLightning\Invoice\Domain\CallbackUrl;

use PhpLightning\Shared\Value\SendableRange;

final class CallbackUrl implements CallbackUrlInterface
{
    private const TAG_PAY_REQUEST = 'payRequest';

    public function __construct(
        private SendableRange $sendableRange,
        private LnAddressGeneratorInterface $lnAddressGenerator,
        private string $callback,
    ) {
    }

    public function getCallbackUrl(string $username): array
    {
        $lnAddress = $this->lnAddressGenerator->generate($username);
        // Modify the description if you want to custom it
        // This will be the description on the wallet that pays your ln address
        // TODO: Make this customizable from some external configuration file
        $description = 'Pay to ' . $lnAddress;

        // TODO: images not implemented yet; `',["image/jpeg;base64","' . base64_encode($response) . '"]';`
        $imageMetadata = '';
        $metadata = '[["text/plain","' . $description . '"],["text/identifier","' . $lnAddress . '"]' . $imageMetadata . ']';

        // payRequest json data, spec : https://github.com/lnurl/luds/blob/luds/06.md
        return [
            'callback' => $this->callback,
            'maxSendable' => $this->sendableRange->max(),
            'minSendable' => $this->sendableRange->min(),
            'metadata' => $metadata,
            'tag' => self::TAG_PAY_REQUEST,
            'commentAllowed' => false, // TODO: Not implemented yet
        ];
    }
}
