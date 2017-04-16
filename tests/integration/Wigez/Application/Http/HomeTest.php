<?php

namespace Integration\Wigez\Application\Http;

require_once __DIR__ . '/IntegrationTestCase.php';

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

        $this->assertView
            ->varEquals('title', 'Ecomp.co.hu - Ügyvitel, Import-Export, Tanácsadás, Szoftver')
            ->varEquals('metaKeywords', [])
            ->varEquals('metaDescription', '')
            ->varEquals('css', '/website/css/style.css');
    }
}
