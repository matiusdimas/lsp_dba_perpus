<?php

interface MigrationInterface {
    public function up(PDO $db);
    public function down(PDO $db);
}
