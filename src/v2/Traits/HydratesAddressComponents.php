<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Traits;

use Illuminate\Support\Collection;
use KangBabi\PhZipCodes\v2\Components\Municipality;
use KangBabi\PhZipCodes\v2\Components\Province;
use KangBabi\PhZipCodes\v2\Enums\Address;

trait HydratesAddressComponents
{
    protected Address $key;

    protected Address $childKey;

    protected Collection $children;

    protected function hydrate(array $children): void
    {
        /** @var Province|Municipality $component */
        $component = $this->key->componentClass();

        $this->children = Collection::make($children)->map(function (array $child) use ($component): Municipality|Province {
            $components = [
                $child[$this->childKey->value],
                $child[$this->childKey->components() ?? $this->childKey->alternative()]
            ];

            return $component::make($components[0], [])->setParent($this);
        });
    }
}
