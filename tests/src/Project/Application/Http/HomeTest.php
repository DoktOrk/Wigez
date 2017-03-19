<?php
namespace Project\Application\Http;

/**
 * Defines the home tests
 */
class HomeTest extends IntegrationTestCase
{
    /**
     * Tests that the 404 template is set up correctly
     */
    public function test404PageIsSetUpCorrectly()
    {
        $this->get('/doesNotExist')
            ->go()
            ->assertResponse
            ->isNotFound();
    }

    /**
     * Tests that the home template is set up correctly
     */
    public function testHomePageIsSetUpCorrectly()
    {
        $this->get('/')
            ->go()
            ->assertResponse
            ->isOK();

        $this->assertView->varEquals('title', 'Home')
            ->varEquals('metaKeywords', [])
            ->varEquals('metaDescription', '')
            ->varEquals('css', '/website/css/style.css');
    }
}
