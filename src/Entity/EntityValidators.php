<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

final class EntityValidators
{
    /**
     * @template T
     * @param class-string<T> $entityClass
     * @param array<string,mixed> $values
     */
    public function __construct(
        private string $entityClass,
        private array &$values
    ) {
    }

    /**
     * @param string $key
     * @return mixed[]|null
     */
    public function arrayNull(string $key): array|null
    {
        return is_array($this->values[$key] ?? null)
            ? $this->values[$key]
            : null;
    }

    /**
     * @param string $key
     * @return mixed[]
     */
    public function array(string $key): array
    {
        return $this->arrayNull($key)
            ?? throw new \UnexpectedValueException("{$this->entityClass}.{$key}");
    }

    /**
     * @param string $key
     * @return bool|null
     */
    public function boolNull(string $key): bool|null
    {
        return is_scalar($this->values[$key] ?? null)
            ? filter_var($this->values[$key], FILTER_VALIDATE_BOOLEAN) === true
            : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function bool(string $key): bool
    {
        return $this->boolNull($key)
            ?? throw new \UnexpectedValueException("{$this->entityClass}::{$key}");
    }

    /**
     * @param string $key
     * @return float|null
     */
    public function floatNull(string $key): float|null
    {
        return is_scalar($this->values[$key] ?? null)
            ? floatval($this->values[$key])
            : null;
    }

    /**
     * @param string $key
     * @return float
     */
    public function float(string $key): float
    {
        return $this->intNull($key)
            ?? throw new \UnexpectedValueException("{$this->entityClass}::{$key}");
    }

    /**
     * @param string $key
     * @return int|null
     */
    public function intNull(string $key): int|null
    {
        return is_scalar($this->values[$key] ?? null)
            ? intval($this->values[$key])
            : null;
    }

    /**
     * @param string $key
     * @return int
     */
    public function int(string $key): int
    {
        return $this->intNull($key)
            ?? throw new \UnexpectedValueException("{$this->entityClass}::{$key}");
    }

    /**
     * @template T of object
     * @param string $key
     * @param class-string<T> $className
     * @param (callable(mixed $v):(T|null))|null $factory
     * @return T|null
     */
    public function objectNull(
        string $key,
        string $className = \stdClass::class,
        callable|null $factory = null
    ): object|null {
        $value = $this->values[$key] ?? null;
        if (is_object($value) && is_a($value, $className)) {
            /** @var T $value */
            return $value;
        }
        return is_callable($factory)
            ? $factory($value)
            : null;
    }

    /**
     * @template T of object
     * @param string $key
     * @param class-string<T> $className
     * @param (callable(mixed $v):(T|null))|null $factory
     * @return T
     */
    public function obj(
        string $key,
        string $className = \stdClass::class,
        callable|null $factory = null
    ): object {
        return $this->objectNull($key, $className, $factory)
            ?? throw new \UnexpectedValueException("{$this->entityClass}::{$key}");
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function stringNull(string $key): string|null
    {
        return is_scalar($this->values[$key] ?? null) ? strval($this->values[$key]) : null;
    }

    /**
     * @param string $key
     * @return string
     */
    public function string(string $key): string
    {
        return $this->stringNull($key)
            ?? throw new \UnexpectedValueException("{$this->entityClass}::{$key}");
    }
}
