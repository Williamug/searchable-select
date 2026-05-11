<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r', 'var_export'])
    ->each->not->toBeUsed();
