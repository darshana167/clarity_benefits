<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{WithHeadingRow,ToCollection,WithMapping};
use Illuminate\Support\Facades\Validator;
use Exception,Cache,Carbon\Carbon;

class COBRADBOpenEnrollmentPlanFile implements WithHeadingRow, ToCollection, WithMapping
{
    public function map($row): array
    {
        if( in_array(gettype(@$row['plan_start_date']), ['double', 'integer']) ) {            
           $row['plan_start_date'] = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['plan_start_date']))->format('m/d/Y'); 
        }

        if( in_array(gettype(@$row['dependent_dob']), ['double', 'integer']) ) {            
           $row['dependent_dob'] = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dependent_dob']))->format('m/d/Y'); 
        }

        return $row;
    }

	public function collection(Collection $rows)
    {
        if ( count($rows) === 0 ) {
            throw new Exception('Looks like te file you\'ve uploaded do not have records or its a blank file', 400);    
        }

        $errors = [];

        $validation_rules = config('Custom.file_checker_validation_rules.'.str_replace(__NAMESPACE__.'\\', '', __CLASS__));

        foreach ($rows as $key => $row) 
        {
            if($key < count($rows))
            {
                array_push($validation_rules['dependent_gender'], ( !empty($row['dependent_ssn']) ) ? 'required' : 'nullable');
                array_push($validation_rules['dependent_dob'], ( !empty($row['dependent_ssn']) ) ? 'required' : 'nullable');
                array_push($validation_rules['dependent_relationship'], ( !empty($row['dependent_ssn']) ) ? 'required' : 'nullable');

                $validator = Validator::make( 
                    $row->toArray(),
                    $validation_rules,
                    [
                        'participant_ssn.regex' => __('lingual.validation.format_err', ['format' => 'XXX-XX-XXXX']),
                        'dependent_ssn.regex' => __('lingual.validation.format_err', ['format' => 'XXX-XX-XXXX']),
                    ]
                );

                if ($validator->fails()) 
                {
                    $errors['row'.($key + 2)] = $validator->errors()->getMessages();
                }

                unset($validator);
            }
        }

        if ( !empty($errors) )
            Cache::store('redis')->put('fileChecker', ['sheet1' => $errors]);
            
        unset($errors);
    }
}