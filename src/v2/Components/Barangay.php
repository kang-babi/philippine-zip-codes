<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Components;

class Barangay
{
    protected Municipality $parent;

    public function __construct(
        protected string $name,
    ) {
        //
    }

    public function setParent(Municipality $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): Municipality
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
