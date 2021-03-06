<?php

namespace Nish\PHPStan\Type;

use PHPStan\Broker\Broker;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\ErrorType;
use PHPStan\Type\VerbosityLevel;
use PHPStan\Type\CompoundType;
use PHPStan\Type\StringType;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Reflection\ClassMemberAccessAnswerer;

class SafeHtmlType extends StringType
{
	public function describe(VerbosityLevel $level): string
	{
		return 'safehtml-string';
	}

	public function accepts(Type $type, bool $strictTypes): TrinaryLogic
	{
		if ($type instanceof CompoundType) {
			return $type->isAcceptedBy($this, $strictTypes);
		}

		if ($type instanceof StringType) {
			return TrinaryLogic::createYes();
		}

		return TrinaryLogic::createNo();
	}

	public function isSuperTypeOf(Type $type): TrinaryLogic
	{
		if ($type instanceof self) {
			return TrinaryLogic::createYes();
		}

		if ($type instanceof ConstantStringType) {
			return TrinaryLogic::createYes();
		}

		if ($type instanceof ClassStringType) {
			return TrinaryLogic::createYes();
		}

		if ($type instanceof CompoundType) {
			return $type->isSubTypeOf($this);
		}

		return TrinaryLogic::createNo();
	}


	public function isCallable(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function getCallableParametersAcceptors(ClassMemberAccessAnswerer $scope): array
	{
		throw new \PHPStan\ShouldNotHappenException();
	}

	public function toInteger(): Type
	{
		return new ErrorType();
	}
	public function toFloat(): Type
	{
		return new ErrorType();
	}
	public function toArray(): Type
	{
		return new ErrorType();
    }

	/**
	 * @param mixed[] $properties
	 * @return Type
	 */
	public static function __set_state(array $properties): Type
	{
		return new self();
	}
}
