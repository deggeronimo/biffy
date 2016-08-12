<?php

use Illuminate\Support\Facades\DB;

abstract class AbstractSqlFileSeeder extends \Illuminate\Database\Seeder
{
    protected $filename = null;

    protected $singleInsert = false;

    public function run()
    {
        if (is_null($this->filename))
        {
            return;
        }

        $file = fopen(__DIR__ . '/sql/' . $this->filename, 'r');

        if ($file)
        {
            $baseline = ( $this->singleInsert ? '' : fgets($file) . ' ' );

            while (($line = fgets($file)) !== false)
            {
                $line = rtrim($line);

                DB::statement($baseline . rtrim($line, ',') . ( $this->singleInsert ? '' : ';' ));

                if (substr($line, -1) == ';')
                {
                    fgets($file);
                }
            }

            fclose($file);

            $this->complete();
        } else {
            echo("File {$this->filename} not found");
            die;
        }
    }

    public function complete() {}
}
