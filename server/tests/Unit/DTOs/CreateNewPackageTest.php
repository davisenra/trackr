<?php

namespace Tests\Unit\DTOs;

use App\DTOs\CreateNewPackage;
use App\Http\Requests\StorePackageRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

class CreateNewPackageTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanBeCreatedWithAllParameters(): void
    {
        $user = new User();
        $dto = new CreateNewPackage(
            $user,
            'Package',
            'NA123456789BR',
            'A brief description',
        );

        $this->assertInstanceOf(CreateNewPackage::class, $dto);
        $this->assertEquals($user, $dto->user);
        $this->assertEquals('Package', $dto->name);
        $this->assertEquals('NA123456789BR', $dto->trackingCode);
        $this->assertEquals('A brief description', $dto->description);
    }

    public function testItCanBeCreatedWithoutOptionalParameters(): void
    {
        $user = new User();
        $dto = new CreateNewPackage(
            $user,
            'Package',
            'NA123456789BR',
        );

        $this->assertInstanceOf(CreateNewPackage::class, $dto);
        $this->assertEquals($user, $dto->user);
        $this->assertEquals('Package', $dto->name);
        $this->assertEquals('NA123456789BR', $dto->trackingCode);
    }

    public function testItCanBeCreatedFromRequestObject(): void
    {
        $request = StorePackageRequest::createFromBase(
            SymfonyRequest::create('', 'POST', [
                'name' => 'Package',
                'trackingCode' => 'NA123456789BR',
                'description' => 'A brief description',
            ])
        );

        $user = new User();
        $dto = CreateNewPackage::fromRequest($user, $request);

        $this->assertInstanceOf(CreateNewPackage::class, $dto);
        $this->assertEquals($user, $dto->user);
        $this->assertEquals('Package', $dto->name);
        $this->assertEquals('NA123456789BR', $dto->trackingCode);
        $this->assertEquals('A brief description', $dto->description);
    }
}
