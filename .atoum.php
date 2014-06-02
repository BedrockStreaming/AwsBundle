<?php

$runner->addTestsFromDirectory(__DIR__.'/src/M6Web/Bundle/AwsBundle/Tests');

$script->excludeDirectoriesFromCoverage([__DIR__.'/vendor']);