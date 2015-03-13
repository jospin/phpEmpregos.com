<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
            CREATE TABLE job
            (
              id serial NOT NULL,
              title character varying NOT NULL,
              location character varying,
              description text NOT NULL,
              status character varying NOT NULL,
              email character varying,
              email_subject_prefix character varying,
              advertiser_name character varying,
              advertiser_url character varying,
              contact_email character varying NOT NULL,
              question_1 text,
              question_2 character varying,
              question_3 character varying,
              external_id character varying,
              published_at timestamp without time zone,
              updated_at timestamp without time zone,
              created_at timestamp without time zone NOT NULL,
              CONSTRAINT job_id_pk PRIMARY KEY (id),
              CONSTRAINT job_external_id_uk UNIQUE (external_id),
              CONSTRAINT job_status_ck CHECK (status::text = 'DRAFT'::text OR status::text = 'PENDING'::text OR status::text = 'PUBLISHED'::text OR status::text = 'REJECTED'::text OR status::text = 'FILLED'::text)
            );
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("drop table job;");
	}

}
