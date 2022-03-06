<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpStatisticalWeekly extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE statisticalWeekly() 
        BEGIN
        SELECT
            SUM(IF(dateW = "Sun", total, 0)) AS "Sun",
            SUM(IF(dateW = "Mon", total, 0)) AS "Mon",
            SUM(IF(dateW = "Tue", total, 0)) AS "Tue",
            SUM(IF(dateW = "Wed", total, 0)) AS "Wed",
            SUM(IF(dateW = "Thu", total, 0)) AS "Thu",
            SUM(IF(dateW = "Fri", total, 0)) AS "Fri",
            SUM(IF(dateW = "Sat", total, 0)) AS "Sat"
            FROM
            (
                SELECT
                SUM(detail__orders.price * detail__orders.unit) AS total,
                DATE_FORMAT(orders.created_at, "%a") AS dateW
                FROM
                orders
                JOIN detail__orders ON detail__orders.idOrder = orders.id
                WHERE
                (
                    orders.idStatus = 3
                    OR orders.idStatus = 5
                )
                AND orders.created_at BETWEEN SUBDATE(
                    CURRENT_DATE,
                    WEEKDAY(CURRENT_DATE)
                )
                AND DATE_ADD(
                    SUBDATE(
                    CURRENT_DATE,
                    WEEKDAY(CURRENT_DATE)
                    ),
                    INTERVAL 6 DAY
                )
                GROUP BY
                DATE_FORMAT(orders.created_at, "%a")
            ) AS sub;
        END'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS statistical');
    }
}
