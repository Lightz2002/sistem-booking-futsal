<?php

namespace Tests\Feature\Livewire\Pages\Package;

use Livewire\Volt\Volt;
use Tests\TestCase;

class DetailTest extends TestCase
{
    public function test_it_can_render(): void
    {
        $component = Volt::test('pages.package.detail');

        $component->assertSee('');
    }
}
