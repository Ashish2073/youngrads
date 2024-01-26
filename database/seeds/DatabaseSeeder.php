<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        DB::unprepared($this->defaultDataQuery());
    }

    public function defaultDataQuery()
    {
        return "-- Adminer 4.7.7 MySQL dump

        SET NAMES utf8;
        SET time_zone = '+00:00';
        SET foreign_key_checks = 0;
        SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
        
        INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `rate`, `status`, `default`) VALUES
        (1,	'US Dollar',	'USD',	'&#36;',	1.00,	'Active',	'0'),
        (2,	'Pound Sterling',	'GBP',	'&pound;',	0.65,	'Active',	'0'),
        (3,	'Europe',	'EUR',	'&euro;',	0.88,	'Active',	'0'),
        (4,	'Australian Dollar',	'AUD',	'&#36;',	1.41,	'Active',	'1'),
        (5,	'Singapore',	'SGD',	'&#36;',	1.41,	'Active',	'0'),
        (6,	'Swedish Krona',	'SEK',	'kr',	8.24,	'Active',	'0'),
        (7,	'Danish Krone',	'DKK',	'kr',	6.58,	'Active',	'0'),
        (8,	'Mexican Peso',	'MXN',	'$',	16.83,	'Active',	'0'),
        (9,	'Brazilian Real',	'BRL',	'R$',	3.88,	'Active',	'0'),
        (10,	'Malaysian Ringgit',	'MYR',	'RM',	4.31,	'Active',	'0'),
        (11,	'Philippine Peso',	'PHP',	'P',	46.73,	'Active',	'0'),
        (12,	'Swiss Franc',	'CHF',	'&euro;',	0.97,	'Active',	'0'),
        (13,	'India',	'INR',	'&#x20B9;',	66.24,	'Active',	'0'),
        (14,	'Argentine Peso',	'ARS',	'&#36;',	9.35,	'Active',	'0'),
        (15,	'Canadian Dollar',	'CAD',	'&#36;',	1.33,	'Active',	'0'),
        (16,	'Chinese Yuan',	'CNY',	'&#165;',	6.37,	'Active',	'0'),
        (17,	'Czech Republic Koruna',	'CZK',	'K&#269;',	23.91,	'Active',	'0'),
        (18,	'Hong Kong Dollar',	'HKD',	'&#36;',	7.75,	'Active',	'0'),
        (19,	'Hungarian Forint',	'HUF',	'Ft',	276.41,	'Active',	'0'),
        (20,	'Indonesian Rupiah',	'IDR',	'Rp',	14249.50,	'Active',	'0'),
        (21,	'Israeli New Sheqel',	'ILS',	'&#8362;',	3.86,	'Active',	'0'),
        (22,	'Japanese Yen',	'JPY',	'&#165;',	120.59,	'Active',	'0'),
        (23,	'South Korean Won',	'KRW',	'&#8361;',	1182.69,	'Active',	'0'),
        (24,	'Norwegian Krone',	'NOK',	'kr',	8.15,	'Active',	'0'),
        (25,	'New Zealand Dollar',	'NZD',	'&#36;',	1.58,	'Active',	'0'),
        (26,	'Polish Zloty',	'PLN',	'z&#322;',	3.71,	'Active',	'0'),
        (27,	'Russian Ruble',	'RUB',	'p',	67.75,	'Active',	'0'),
        (28,	'Thai Baht',	'THB',	'&#3647;',	36.03,	'Active',	'0'),
        (29,	'Turkish Lira',	'TRY',	'&#8378;',	3.05,	'Active',	'0'),
        (30,	'New Taiwan Dollar',	'TWD',	'&#36;',	32.47,	'Active',	'0'),
        (31,	'Vietnamese Dong',	'VND',	'&#8363;',	22471.00,	'Active',	'0'),
        (32,	'South African Rand',	'ZAR',	'R',	13.55,	'Active',	'0');
        
        SET NAMES utf8mb4;
        
        INSERT INTO `document_types` (`id`, `title`, `is_required`, `document_limit`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (5,	'Passport',	1,	1,	'2020-09-11 17:46:35',	'2020-09-11 17:46:35',	NULL),
        (6,	'CV',	1,	1,	'2020-10-03 13:33:27',	'2020-10-03 13:33:27',	NULL),
        (7,	'Work',	0,	0,	'2020-12-13 08:30:50',	'2020-12-13 08:35:36',	'2020-12-13 08:35:36');
        
        INSERT INTO `fee_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (2,	'Admission Fees',	'2020-06-27 04:44:22',	'2020-09-03 19:35:45',	NULL),
        (3,	'Tuition Fees',	'2020-07-08 20:42:54',	'2020-09-03 19:36:02',	NULL),
        (4,	'Application Fees',	'2020-09-03 19:03:00',	'2020-09-03 19:35:54',	NULL);
        
        INSERT INTO `intakes` (`id`, `name`, `type`, `sequence`, `group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (1,	'January',	'Month',	1,	'Jan',	NULL,	NULL,	NULL),
        (2,	'February',	'Month',	2,	'Jan',	'2020-06-27 06:05:54',	'2020-06-27 06:14:17',	NULL),
        (3,	'August',	'Month',	8,	'May',	'2020-07-03 05:25:53',	'2020-07-03 05:33:16',	NULL),
        (4,	'Spring',	'Season',	14,	NULL,	'2020-07-03 08:22:31',	'2020-07-03 08:22:31',	NULL),
        (5,	'Autumn',	'Season',	13,	NULL,	'2020-07-04 05:54:45',	'2020-07-04 05:55:04',	NULL),
        (6,	'March',	'Month',	3,	'Jan',	'2020-06-27 06:05:54',	'2020-06-27 06:14:17',	NULL),
        (7,	'April',	'Month',	4,	'Jan',	'2020-06-27 06:05:54',	'2020-06-27 06:14:17',	NULL),
        (9,	'May',	'Month',	5,	'May',	'2020-07-21 05:00:00',	'2020-07-21 05:00:00',	NULL),
        (10,	'June',	'Month',	6,	'May',	'2020-07-21 05:00:00',	'2020-07-21 05:00:00',	NULL),
        (11,	'July',	'Month',	7,	'May',	'2020-07-21 05:00:00',	'2020-07-21 05:00:00',	NULL),
        (12,	'September',	'Month',	9,	'Sep',	'2020-07-21 12:44:30',	'2020-07-21 12:44:30',	NULL),
        (13,	'October',	'Month',	10,	'Sep',	'2020-07-21 12:44:41',	'2020-07-21 12:44:41',	NULL),
        (14,	'November',	'Month',	11,	'Sep',	'2020-07-21 12:44:51',	'2020-07-21 12:44:51',	NULL),
        (15,	'December',	'Month',	12,	'Sep',	'2020-07-21 12:45:03',	'2020-07-21 12:45:03',	NULL);
        
        INSERT INTO `program_levels` (`id`, `name`, `slug`, `study_level_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (1,	'High School (11th-12th)',	'highschool',	NULL,	NULL,	NULL,	NULL),
        (2,	'UG Diploma/Certificate/Associate Degree',	'ugdiploma',	'1,2',	NULL,	NULL,	NULL),
        (3,	'UG',	'ug',	'1,2',	'2020-07-01 02:55:44',	'2020-07-08 15:10:53',	NULL),
        (4,	'PG Diploma/Cirtificate',	'pgdiploma',	'1,2,4',	NULL,	NULL,	NULL),
        (5,	'PG',	'pg',	'1,2,4',	'2020-07-01 02:55:59',	'2020-07-08 15:11:03',	NULL),
        (7,	'PhD',	'phd',	'1,2,4,6',	NULL,	NULL,	NULL),
        (8,	'UG+PG (Accelerated) Degree',	'accelerated',	'1,2',	NULL,	NULL,	NULL),
        (12,	'Foundation',	'foundation',	'1,2',	NULL,	NULL,	NULL),
        (14,	'Doctorate',	'Doctorate',	'1,2,4,6',	'2020-08-25 13:36:51',	'2020-08-25 14:16:35',	'2020-09-09 00:00:00');
        
        INSERT INTO `special_test_sub` (`id`, `test_id`, `name`, `created_at`, `updated_at`) VALUES
        (1,	1,	'reading',	NULL,	NULL),
        (2,	1,	'listening',	NULL,	NULL),
        (3,	1,	'speaking',	NULL,	NULL),
        (4,	1,	'writing ',	NULL,	NULL),
        (5,	2,	'reading',	NULL,	NULL),
        (6,	2,	'listening',	NULL,	NULL),
        (7,	2,	'speaking',	NULL,	NULL),
        (8,	2,	'writing ',	NULL,	NULL),
        (9,	3,	'reading',	NULL,	NULL),
        (10,	3,	'listening',	NULL,	NULL),
        (11,	3,	'speaking',	NULL,	NULL),
        (12,	3,	'writing ',	NULL,	NULL),
        (13,	4,	'quantitative',	NULL,	NULL),
        (14,	4,	'verbal',	NULL,	NULL),
        (15,	4,	'analytical writing',	NULL,	NULL),
        (19,	5,	'quantitative',	NULL,	NULL),
        (20,	5,	'verbal',	NULL,	NULL),
        (21,	5,	'analytical writing',	NULL,	NULL),
        (22,	5,	'integrated reasoning',	NULL,	NULL),
        (23,	6,	'reading & writing',	NULL,	NULL),
        (24,	6,	'math',	NULL,	NULL),
        (25,	6,	'essay',	NULL,	NULL);
        
        INSERT INTO `study_levels` (`id`, `name`, `sequence`, `document_limit`, `created_at`, `updated_at`, `parent_id`, `deleted_at`) VALUES
        (1,	'Grade 10',	7,	1,	'2020-09-02 01:32:17',	NULL,	0,	NULL),
        (2,	'Grade 12',	6,	1,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (3,	'UG Diploma',	5,	2,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (4,	'UG Degree',	4,	2,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (5,	'PG Diploma',	3,	2,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (6,	'PG Degree',	2,	2,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (7,	'PhD',	1,	1,	'2020-09-02 01:32:18',	NULL,	0,	NULL),
        (9,	'10th Marksheet',	0,	1,	NULL,	NULL,	1,	NULL),
        (10,	'12th Marksheet ',	0,	1,	NULL,	NULL,	2,	NULL),
        (11,	'Diploma Certificate',	0,	NULL,	NULL,	NULL,	3,	NULL),
        (12,	'Transcript / Marksheet',	0,	NULL,	NULL,	NULL,	3,	NULL),
        (13,	'Degree Certificate',	0,	NULL,	NULL,	NULL,	4,	NULL),
        (14,	'Transcript / Marksheet',	0,	NULL,	NULL,	NULL,	4,	NULL),
        (15,	'Diploma Certificate ',	0,	1,	NULL,	NULL,	5,	NULL),
        (16,	'Transcript / Marksheet',	0,	NULL,	NULL,	NULL,	5,	NULL),
        (17,	'PG Degree Certificate',	0,	NULL,	NULL,	NULL,	6,	NULL),
        (18,	'Transcript / Marksheet',	0,	NULL,	NULL,	NULL,	6,	NULL),
        (19,	'PG Degree Certificate',	0,	NULL,	NULL,	NULL,	7,	NULL),
        (20,	'Other',	8,	NULL,	NULL,	NULL,	0,	NULL),
        (21,	'Other',	8,	NULL,	NULL,	NULL,	20,	NULL);
        
        INSERT INTO `tests` (`id`, `test_name`, `parent_id`, `created_at`, `updated_at`) VALUES
        (1,	'TOEFL   ',	0,	'2020-10-01 06:04:04',	'2020-07-22 19:13:28'),
        (2,	'IELTS',	0,	'2020-07-22 08:24:25',	'2020-08-25 10:56:26'),
        (3,	'PTE',	0,	'2020-07-22 19:13:40',	'2020-07-22 19:13:40'),
        (4,	'GMAT',	0,	'2020-08-26 09:33:02',	'2020-08-26 09:33:02'),
        (5,	'CAT',	0,	'2020-08-27 19:31:27',	'2020-08-27 19:32:17'),
        (6,	'TOEFL   ',	1,	'2020-10-01 05:00:00',	'2020-07-22 19:13:28'),
        (7,	'IELTS',	2,	'2020-07-22 08:24:25',	'2020-08-25 10:56:26'),
        (8,	'PTE',	3,	'2020-07-22 19:13:40',	'2020-07-22 19:13:40'),
        (9,	'GMAT',	4,	'2020-08-26 09:33:02',	'2020-08-26 09:33:02'),
        (10,	'CAT',	5,	'2020-08-27 19:31:27',	'2020-08-27 19:32:17');
        
        -- 2021-01-21 22:11:01";
    }
}
