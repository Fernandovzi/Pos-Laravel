<?php

namespace Tests\Unit;

use App\Http\Controllers\EmpleadoController;
use App\Http\Requests\StoreEmpleadoRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class EmpleadoControllerTest extends TestCase
{
    #[DataProvider('validatedActions')]
    public function test_write_actions_use_the_existing_employee_request(string $action): void
    {
        $requestParameter = (new ReflectionMethod(EmpleadoController::class, $action))
            ->getParameters()[0];

        $this->assertSame(StoreEmpleadoRequest::class, $requestParameter->getType()?->getName());
        $this->assertTrue(class_exists($requestParameter->getType()?->getName()));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validatedActions(): array
    {
        return [
            'store' => ['store'],
            'update' => ['update'],
        ];
    }
}
