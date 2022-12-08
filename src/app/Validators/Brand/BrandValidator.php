<?php

namespace App\Validators\Brand;

use App\Exceptions\NonUniqueNameException;
use App\Exceptions\TooLongNameException;
use App\Repositories\Brand\BrandRepositoryInterface;

class BrandValidator
{
    private const MAX_NAME_LENGTH = 255;

    private BrandRepositoryInterface $repository;

    public function __construct(BrandRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $name
     *
     * @return void
     *
     * @throws NonUniqueNameException
     * @throws TooLongNameException
     */
    public function validate(string $name): void
    {
        $nameLength = strlen($name);
        if ($nameLength > self::MAX_NAME_LENGTH) {
            $message = sprintf(
                'Max length of the brand name is %d, gave %d.',
                self::MAX_NAME_LENGTH,
                $nameLength
            );

            throw new TooLongNameException($message);
        }

        if ($this->repository->isUniqueName($name) === false) {
            throw new NonUniqueNameException('The brand already exists.');
        }
    }
}
