<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpStatistical extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE statistical() 
        BEGIN
        SELECT
            SUM(IF(m = "1", total, 0)) AS "Jan",
            SUM(IF(m = "2", total, 0)) AS "Feb",
            SUM(IF(m = "3", total, 0)) AS "Mar",
            SUM(IF(m = "4", total, 0)) AS "Apr",
            SUM(IF(m = "5", total, 0)) AS "May",
            SUM(IF(m = "6", total, 0)) AS "Jun",
            SUM(IF(m = "7", total, 0)) AS "Jul",
            SUM(IF(m = "8", total, 0)) AS "Aug",
            SUM(IF(m = "9", total, 0)) AS "Sep",
            SUM(IF(m = "10", total, 0)) AS "Oct",
            SUM(IF(m = "11", total, 0)) AS "Nov",
            SUM(IF(m = "12", total, 0)) AS "Dec"
        FROM
            (
                SELECT SUM(detail__orders.price * detail__orders.unit) AS total, 
                detail__orders.idOrder,
                EXTRACT(MONTH FROM detail__orders.updated_at) AS m
                FROM orders JOIN detail__orders ON detail__orders.idOrder = orders.id 
                WHERE orders.created_at <= NOW() and orders.created_at >= Date_add(Now(),interval - 12 month)
                and (orders.idStatus = 3 OR orders.idStatus = 5)
                GROUP BY detail__orders.idOrder, m
                ) as sub;
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
