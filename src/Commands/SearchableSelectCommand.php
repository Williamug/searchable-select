<?php

namespace Williamug\SearchableSelect\Commands;

use Illuminate\Console\Command;

class SearchableSelectCommand extends Command
{
    public $signature = 'searchable-select';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
