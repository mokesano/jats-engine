<?php

declare(strict_types=1);

namespace Wizdam\JatsEngine\Tests;

use PHPUnit\Framework\TestCase;
use Wizdam\JatsEngine\JatsEngine;
use Wizdam\JatsEngine\Builders\BodyBuilder;

/**
 * Test suite untuk JatsEngine
 *
 * Catatan: Test ini hanya menguji class yang tidak bergantung pada OJS globals.
 * MetadataBuilder memerlukan OJS bootstrap (DAORegistry) dan tidak dapat diuji
 * secara terpisah dalam lingkungan PHPUnit standar tanpa OJS.
 */
class JatsEngineTest extends TestCase
{
    /**
     * Test bahwa class JatsEngine dapat di-instantiate
     * Catatan: Test ini akan skip jika DAORegistry tidak tersedia (lingkungan non-OJS)
     * Karena JatsEngine::__construct() memanggil MetadataBuilder yang memerlukan DAORegistry
     */
    public function testJatsEngineCanBeInstantiated(): void
    {
        if (!class_exists('DAORegistry')) {
            $this->markTestSkipped(
                'DAORegistry tidak tersedia. Test ini memerlukan environment OJS. ' .
                'JatsEngine membutuhkan MetadataBuilder yang bergantung pada OJS DAORegistry.'
            );
        }

        $engine = new JatsEngine(1);
        $this->assertInstanceOf(JatsEngine::class, $engine);
    }

    /**
     * Test bahwa BodyBuilder dapat di-instantiate (tidak memerlukan OJS)
     */
    public function testBodyBuilderCanBeInstantiated(): void
    {
        $builder = new BodyBuilder(1);
        $this->assertInstanceOf(BodyBuilder::class, $builder);
    }

    /**
     * Test bahwa setSourceFile melempar exception jika file tidak ada
     * Catatan: Test ini akan skip jika DAORegistry tidak tersedia (lingkungan non-OJS)
     */
    public function testSetSourceFileThrowsExceptionForNonExistentFile(): void
    {
        if (!class_exists('DAORegistry')) {
            $this->markTestSkipped('DAORegistry tidak tersedia. Test ini memerlukan environment OJS.');
        }

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File sumber DOCX tidak ditemukan');

        $engine = new JatsEngine(1);
        $engine->setSourceFile('/non/existent/path.docx');
    }

    /**
     * Test bahwa generate melempar exception jika source file belum diset
     * Catatan: Test ini akan skip jika DAORegistry tidak tersedia (lingkungan non-OJS)
     */
    public function testGenerateThrowsExceptionIfSourceFileNotSet(): void
    {
        if (!class_exists('DAORegistry')) {
            $this->markTestSkipped('DAORegistry tidak tersedia. Test ini memerlukan environment OJS.');
        }

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Source file belum diset');

        $engine = new JatsEngine(1);
        $engine->generate();
    }

    /**
     * Test bahwa BodyBuilder dapat memproses data citation
     */
    public function testBodyBuilderSetCitationData(): void
    {
        $builder = new BodyBuilder(1);
        $rawCitations = "Smith, J. (2020). Example Reference.\nDoe, A. (2021). Another Reference.";

        // Method setCitationData tidak mengembalikan nilai, jadi kita test bahwa tidak error
        $builder->setCitationData($rawCitations);
        $this->assertTrue(true, 'setCitationData berjalan tanpa error');
    }

    /**
     * Test bahwa MetadataBuilder memerlukan DAORegistry
     * Test ini memverifikasi bahwa MetadataBuilder throw exception yang jelas
     * ketika digunakan di luar environment OJS
     */
    public function testMetadataBuilderRequiresDAORegistry(): void
    {
        if (class_exists('DAORegistry')) {
            $this->markTestSkipped('DAORegistry tersedia. Test ini hanya untuk environment non-OJS.');
        }

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DAORegistry tidak ditemukan');

        // Menggunakan Reflection untuk instantiate class yang throw exception di constructor
        $reflectionClass = new \ReflectionClass(\Wizdam\JatsEngine\Builders\MetadataBuilder::class);
        $reflectionClass->newInstance(1);
    }
}
