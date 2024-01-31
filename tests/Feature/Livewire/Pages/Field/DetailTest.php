<?php

namespace Tests\Feature\Livewire\Pages\Field;

use Livewire\Volt\Volt;
use Tests\TestCase;

class DetailTest extends TestCase
{
    public function test_it_can_render(): void
    {
        $component = Volt::test('pages.field.detail');

        $component->assertSee('');
    }
}
