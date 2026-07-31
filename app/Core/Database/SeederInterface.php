<?php

interface SeederInterface {
    public function run(PDO $db);
}
