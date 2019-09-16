<?php
declare(strict_types=1);

namespace Aerticket\DataAnonymizer\Command;

/*
 * This file is part of the Aerticket.DataAnonymizer package.
 *
 * (c) Contributors to the package
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Aerticket\DataAnonymizer\Service\AnonymizationService;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Annotations as Flow;

/**
 * Class AnonymizeCommandController
 */
class AnonymizeCommandController extends CommandController
{

    /**
     * @var AnonymizationService
     * @Flow\Inject()
     */
    protected $anonymizationService;

    /**
     * Anonymize all entities that exceed their maximum age
     * @param string $className The name of a class to anonymize entities of
     * @throws \Aerticket\DataAnonymizer\AnonymizationException
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function runCommand($className = null)
    {
        $this->outputLine('Anonymizing all entities that exceed their maximum age. Please see log for details.');
        if ($className === null) {
            $classNames = $this->anonymizationService->getAnonymizableClassNames();

            foreach ($classNames as $singleClassName) {
                $this->executeForClassName($singleClassName);
            }
        } else {
            $this->executeForClassName($className);
        }

        $this->outputLine('Done.');
    }

    /**
     * @param string $className
     * @throws \Aerticket\DataAnonymizer\AnonymizationException
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    protected function executeForClassName($className)
    {
        $this->outputLine('Processing entities of type "%s"...', [$className]);
        $this->anonymizationService->anonymize($className);
    }
}
