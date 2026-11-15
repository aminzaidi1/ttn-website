<?php

namespace Tests\Feature;

use Symfony\Component\Finder\Finder;
use Tests\TestCase;

/** Guards the design system rules that are easy to break by accident. */
class DesignRulesTest extends TestCase
{
    public function test_no_colour_is_hardcoded_outside_the_tokens(): void
    {
        $files = Finder::create()->files()->in(resource_path('css'))->name('*.css')->notPath('tokens');
        $files = iterator_to_array($files) + iterator_to_array(Finder::create()->files()->in(resource_path('views'))->name('*.blade.php'));

        foreach ($files as $file) {
            $body = preg_replace('#/\*.*?\*/|\{\{--.*?--\}\}#s', '', $file->getContents());
            $body = preg_replace('/<meta name="theme-color"[^>]*>/', '', $body);
            $this->assertDoesNotMatchRegularExpression('/#[0-9a-fA-F]{6}\b|#[0-9a-fA-F]{3}\b(?![0-9a-zA-Z_-])|rgba?\(/', $body,
                $file->getRelativePathname().' hardcodes a colour. Use a token from resources/css/tokens.');
            $this->assertDoesNotMatchRegularExpression('/box-shadow|backdrop-filter/', $body,
                $file->getRelativePathname().' uses a shadow or blur. The brand uses borders only.');
        }
    }

    public function test_tokens_are_the_design_system_tokens_unchanged(): void
    {
        foreach (['colors', 'typography', 'spacing', 'layout'] as $name) {
            $this->assertFileEquals(base_path("design-system/tokens/$name.css"), resource_path("css/tokens/$name.css"));
        }
    }

    public function test_site_copy_has_no_em_dashes(): void
    {
        $files = iterator_to_array(Finder::create()->files()->in(resource_path('views'))->name('*.blade.php'))
            + iterator_to_array(Finder::create()->files()->in(database_path('seeders'))->name('*.php'));

        foreach ($files as $file) {
            $body = preg_replace('#\{\{--.*?--\}\}|/\*.*?\*/|^\s*//.*$#sm', '', $file->getContents());
            $this->assertStringNotContainsString('—', $body, $file->getRelativePathname().' contains an em dash.');
        }
    }
}
