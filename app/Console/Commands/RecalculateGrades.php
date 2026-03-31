<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalculateGrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grades:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate Total Score and GPA for all grades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $grades = \App\Models\Grade::all();
        $bar = $this->output->createProgressBar(count($grades));

        $bar->start();

        foreach ($grades as $grade) {
            $grade->calculateTotal();
            $grade->save();
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAll grades have been recalculated.");
    }
}
