<?php

namespace Amp\ParallelFunctions\Test;

use Amp\Parallel\Worker\Pool;
use Amp\ParallelFunctions\Test\Fixture\TestCallables;
use Amp\PHPUnit\TestCase;
use Amp\Promise;
use Amp\Success;
use function Amp\ParallelFunctions\parallel;

class UnserializableClass {
    public function __invoke() {
    }

    public function instanceMethod() {
    }

    public static function staticMethod() {
    }
}

class ParallelTest extends TestCase {
    /**
     * @expectedException \Error
     * @expectedExceptionMessage Unsupported callable: Serialization of 'class@anonymous' is not allowed
     */
    public function testUnserializableClosure() {
        $unserializable = new class {
        };
        parallel(function () use ($unserializable) {
            return 1;
        });
    }

    public function testCustomPool() {
        $mock = $this->createMock(Pool::class);
        $mock->expects($this->once())
            ->method("enqueue")
            ->willReturn(new Success(1));

        $callable = parallel(function () {
            return 0;
        }, $mock);

        $this->assertSame(1, Promise\wait($callable()));
    }

    public function testClassStaticMethod() {
        $callable = [TestCallables::class, 'staticMethod'];
        $result = $callable(1);
        $callable = parallel($callable);

        $this->assertSame($result, Promise\wait($callable(1)));
    }

    public function testClassInstanceMethod() {
        $instance = new TestCallables;

        $callable = [$instance, 'instanceMethod'];
        $result = $callable(1);
        $callable = parallel($callable);

        $this->assertSame($result, Promise\wait($callable(1)));
    }

    public function testCallableClass() {
        $callable = new TestCallables;
        $result = $callable(1);
        $callable = parallel($callable);

        $this->assertSame($result, Promise\wait($callable(1)));
    }

    public function testUnserializableCallable() {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("Unsupported callable: Serialization of 'class@anonymous' is not allowed");

        $callable = new class {
            public function __invoke() {
            }
        };

        $callable = parallel($callable);
    }

    public function testUnserializableClassInstance() {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Uncaught Error in worker with message "When using a class instance as a callable, the class must be autoloadable"');

        $callable = new UnserializableClass;

        $callable = parallel($callable);

        Promise\wait($callable());
    }

    public function testUnserializableClassInstanceMethod() {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Uncaught Error in worker with message "When using a class instance method as a callable, the class must be autoloadable"');

        $callable = [new UnserializableClass, 'instanceMethod'];

        $callable = parallel($callable);

        Promise\wait($callable());
    }

    public function testUnserializableClassStaticMethod() {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Uncaught Error in worker with message "Class \'Amp\\ParallelFunctions\\Test\\UnserializableClass\' not found"');

        $callable = [UnserializableClass::class, 'staticMethod'];

        $callable = parallel($callable);

        Promise\wait($callable());
    }
}
