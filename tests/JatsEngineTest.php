<?php

declare(strict_types=1);

namespace Wizdam\JatsEngine\Tests;

use PHPUnit\Framework\TestCase;
use Wizdam\JatsEngine\JatsEngine;
use Wizdam\JatsEngine\Builders\MetadataBuilder;
use Wizdam\JatsEngine\Builders\BodyBuilder;

/**
 * Test suite untuk JatsEngine
 */
class JatsEngineTest extends TestCase
{
    /**
     * Test bahwa class JatsEngine dapat di-instantiate
     */
    public function testJatsEngineCanBeInstantiated(): void
    {
        $engine = new JatsEngine(1);
        $this->assertInstanceOf(JatsEngine::class, $engine);
    }

    /**
     * Test bahwa MetadataBuilder dapat di-instantiate
     */
    public function testMetadataBuilderCanBeInstantiated(): void
    {
        $builder = new MetadataBuilder(1);
        $this->assertInstanceOf(MetadataBuilder::class, $builder);
    }

    /**
     * Test bahwa BodyBuilder dapat di-instantiate
     */
    public function testBodyBuilderCanBeInstantiated(): void
    {
        $builder = new BodyBuilder(1);
        $this->assertInstanceOf(BodyBuilder::class, $builder);
    }

    /**
     * Test bahwa setSourceFile melempar exception jika file tidak ada
     */
    public function testSetSourceFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File sumber DOCX tidak ditemukan');

        $engine = new JatsEngine(1);
        $engine->setSourceFile('/non/existent/path.docx');
    }

    /**
     * Test bahwa generate melempar exception jika source file belum diset
     */
    public function testGenerateThrowsExceptionIfSourceFileNotSet(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Source file belum diset');

        $engine = new JatsEngine(1);
        $engine->generate();
    }
}
