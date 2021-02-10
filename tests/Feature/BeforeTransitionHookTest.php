<?php

namespace Asantibanez\LaravelEloquentStateMachines\Tests\Feature;

use Asantibanez\LaravelEloquentStateMachines\Tests\TestCase;
use Asantibanez\LaravelEloquentStateMachines\Tests\TestModels\SalesOrderWithBeforeTransitionHook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BeforeTransitionHookTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function should_call_before_transition_hooks()
    {
        //Arrange
        $salesOrder = SalesOrderWithBeforeTransitionHook::create();

        $this->assertNull($salesOrder->total);
        $this->assertNull($salesOrder->notes);

        //Act
        $salesOrder->status()->transitionTo('approved');

        //Assert
        $salesOrder->refresh();

        $this->assertEquals(100, $salesOrder->total);
        $this->assertEquals('Notes updated', $salesOrder->notes);
    }

    /** @test */
    public function should_not_call_before_transition_hooks_if_not_defined()
    {
        //Arrange
        $salesOrder = SalesOrderWithBeforeTransitionHook::create([
            'status' => 'approved'
        ]);

        $this->assertNull($salesOrder->total);
        $this->assertNull($salesOrder->notes);

        //Act
        $salesOrder->status()->transitionTo('processed');

        //Assert
        $salesOrder->refresh();

        $this->assertNull($salesOrder->total);
        $this->assertNull($salesOrder->notes);
    }
}
