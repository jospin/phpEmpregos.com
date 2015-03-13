<?php
use phpEmpregos\Job\Job;
use Carbon\Carbon;

class JobSeeder extends DatabaseSeeder
{
    public function run()
    {
        $faker = Faker\Factory::create();

        DB::table('job')->truncate();

        $statuses = [
            'DRAFT',
            'PENDING',
            'REJECTED',
            'PUBLISHED',
            'FILLED'
        ];

        $total       = env('SEED_JOB_COUNT', 1000);
        $progressBar = $this->command->getHelperSet()->get('progress');
        $progressBar->start($this->command->getOutput(), $total);

        for ($i=0; $i<$total; $i++) {
            $progressBar->advance();

            $id           = $i + 1;
            $statusIdx    = rand(0, 4);
            $published    = ($statusIdx > 2);
            $createdAt    = $faker->dateTimeBetween('2009-01-01', 'now');
            $createdAtStr = $createdAt->format('Y-m-d H:i:s');

            if ($published) {
                $publishedAt = $faker->dateTimeBetween($createdAt, "{$createdAtStr} +" . rand(1,3600) . ' minutes');
                $updatedAt   = $faker->dateTimeBetween($createdAt, $publishedAt);
            } else {
                $publishedAt = null;
                $updatedAt   = $faker->dateTimeBetween($createdAt, 'now');
            }

            $description = '';
            foreach (range(1, 20) as $index) {
                $description .= $faker->text(rand(5, 50)) . str_repeat("\n", rand(1, 4));
            }

            Job::create([
//                'id'                   => $id,
                'title'                => implode(' ', array_map('ucfirst', $faker->words(rand(2, 5)))),
                'location'             => rand(0, 1) ? "{$faker->city}, {$faker->state}, {$faker->country}" : null,
                'description'          => $description,
                'status'               => $statuses[$statusIdx],
                'email'                => rand(0, 1) ? $faker->email : null,
                'email_subject_prefix' => rand(0, 1) ? "[{$faker->sentence(rand(2,4))}] - " : "[phpEmpregos.com - {$id}] - ",
                'advertiser_name'      => rand(0, 1) ? implode(' ', array_map('ucfirst', $faker->words(rand(1, 3)))) : null,
                'advertiser_url'       => rand(0, 1) ? $faker->url : null,
                'contact_email'        => $faker->email,
                'question_1'           => rand(0, 1) ? $faker->sentence(rand(5, 10)) : null,
                'question_2'           => rand(0, 1) ? $faker->sentence(rand(5, 10)) : null,
                'question_3'           => rand(0, 1) ? $faker->sentence(rand(5, 10)) : null,
                'external_id'          => rand(0, 1) ? $faker->unique()->url : null,
                'published_at'         => $publishedAt,
                'updated_at'           => $updatedAt,
                'created_at'           => $createdAt
            ]);
        }

        $progressBar->finish();

    }

}