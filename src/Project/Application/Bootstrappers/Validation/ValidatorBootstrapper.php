<?php
namespace Project\Application\Bootstrappers\Validation;

use InvalidArgumentException;
use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Validation\Bootstrappers\ValidatorBootstrapper as BaseBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Validation\Rules\Errors\ErrorTemplateRegistry;
use Opulence\Validation\Rules\RuleExtensionRegistry;
use Project\Application\Constant\Env;
use Project\Application\Validation\Factory\Category;
use Project\Application\Validation\Factory\Customer;
use Project\Application\Validation\Factory\File;
use Project\Application\Validation\Factory\Page;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends BaseBootstrapper
{
    protected $validatorFactories = [
        Category::class,
        Customer::class,
        File::class,
        Page::class,
    ];

    /**
     * Registers the error templates
     *
     * @param ErrorTemplateRegistry $errorTemplateRegistry The registry to register to
     * @throws InvalidArgumentException Thrown if the config was invalid
     */
    protected function registerErrorTemplates(ErrorTemplateRegistry $errorTemplateRegistry)
    {
        $config = require sprintf(
            '%s/%s/validation.php',
            Config::get('paths', 'resources.lang'),
            getenv(Env::DEFAULT_LANGUAGE)
        );

        $errorTemplateRegistry->registerErrorTemplatesFromConfig($config);
    }

    /**
     * Registers any custom rule extensions
     *
     * @param RuleExtensionRegistry $ruleExtensionRegistry The registry to register rules to
     */
    protected function registerRuleExtensions(RuleExtensionRegistry $ruleExtensionRegistry)
    {
        // Register any custom rules here
    }

    /**
     * @inheritdoc
     */
    public function getBindings() : array
    {
        $bindings = array_merge(
            parent::getBindings(),
            $this->validatorFactories
        );

        return $bindings;
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        parent::registerBindings($container);


    }
}
