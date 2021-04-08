<?php

namespace Jamesking56\LaravelVisualDiff\Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Orchestra\Testbench\Dusk\TestCase;
use Jamesking56\LaravelVisualDiff\LaravelVisualDiffServiceProvider;
use PHPUnit\Framework\ExpectationFailedException;

class LaravelVisualDiffTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaravelVisualDiffServiceProvider::class];
    }

    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions())->addArguments([
            '--no-sandbox',
            '--disable-gpu',
            '--headless',
            '--window-size=1920, 1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    protected function tearDown(): void
    {
        rmdir(__DIR__ . '/visual-diff');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['router']->get('page', function() {
            return 'This is my page.';
        });
    }

    public function testFirstRunNewScreenshot(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('page')
                ->visualDiff();
        });

        $this->assertTrue(file_exists(__DIR__ . '/visual-diff/screenshots/1920_x_1080_testFirstRunNewScreenshot_page.png'));
    }

    public function testFirstRunNewScreenshotNamed(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('page')
                ->visualDiff('the_name_goes_here');
        });

        $this->assertTrue(file_exists(__DIR__ . '/visual-diff/screenshots/1920_x_1080_the_name_goes_here_page.png'));
    }

    public function testSecondRunScreenshotNoDifferenceDetected(): void
    {
        // First run to generate screenshot
        $this->browse(function (Browser $browser) {
            $browser->visit('page')
                ->visualDiff();
        });
        $this->assertTrue(file_exists(__DIR__ . '/visual-diff/screenshots/1920_x_1080_testSecondRunScreenshotDiff_page.png'));

        // Second run of same page
        $this->browse(function (Browser $browser) {
            $browser->visit('page')
                ->visualDiff();
        });

        // No diff file generated since we didn't hit the threshold
        $this->assertFalse(file_exists(__DIR__ . '/visual-diff/diffs/1920_x_1080_testSecondRunScreenshotDiff_page.png'));
    }

    public function testSecondRunScreenshotDifferenceDetected(): void
    {
        // Fake first run with a dummy png file which should then generate a massive difference
        copy(__DIR__ . '/dummy.png', __DIR__ . '/visual-diff/screenshots/1920_x_1080_testSecondRunScreenshotDifferenceDetected_page.png');

        // Second run of page, massive difference should be detected
        try {
            $this->browse(function (Browser $browser) {
                $browser->visit('page')
                    ->visualDiff();
            });
        } catch (ExpectationFailedException $e) {
            self::assertStringContainsString(
                "The visual diff for 'testSecondRunScreenshotDifferenceDetected' on page 'page' has detected a major difference.",
                $e->getMessage()
            );
        }

        // Diff file is generated so the developer can see the difference
        $this->assertFalse(file_exists(__DIR__ . '/visual-diff/diffs/1920_x_1080_testSecondRunScreenshotDifferenceDetected_page.png'));
    }
}
