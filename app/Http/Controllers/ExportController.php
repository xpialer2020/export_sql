<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

class ExportController extends Controller
{
  protected $file_excel;
  public function __construct()
  {
    $this->file_excel = asset('example_file.xlsx');
  }

  public function export(Request $request)
  {
    //DB
    $File_type = $request->input('type');
    $File_schema_name = $request->input('schema-name');
    $File_table_name = $request->input('table-name');
    $File_column = $request->column;
    $File_column_value = $request->value;

    $File_condition = $request->input('condition');
      $File_condition_value = $request->input('condition-value');
    //มีลูกน้ำติดมา แก้ไขด้วย
    /*
    if ($File_type > 0) {
      $File_SET = "";
      $len = count($File_column);
      $File_condition = $request->input('condition');
      $File_condition_value = $request->input('condition-value');
      for ($i = 0; $i < $len ; $i++) {
        $File_SET .= "`{$File_column[ $i ]}` = '{$File_column_value[$i]}'";
        if( $i < $len){
          $File_SET .= ',';
        }
       }
    } else {
      $File_column_reuslt = "";
      $File_column_value_reuslt = "";
      foreach ($File_column as $col) {
        if ($col === end($File_column)) {
          $File_column_reuslt .= $col;
        } else {
          $File_column_reuslt .= $col . ',';
        }
      }
      foreach ($File_column_value as $col) {
        if ($col === end($File_column_value)) {
          $File_column_value_reuslt .= $col;
        } else {
          $File_column_value_reuslt .= $col . ',';
        }
      }
    }
*/
    $path = $request->file('file')->getRealPath();
    $data = Excel::load($path)->get();
    
    if ($data->count()) {
      $sql = [];
      foreach ($data as $key => $value) {
        $into = [];
        $insert_value = [];

        $update = [];
          foreach($File_column_value as $key_col => $column){
            $value_excel = $value->$column;
            if ($File_type == 0) {
              $into[] = '`'.$File_column[$key_col].'`';
              $insert_value[] = "'".$value_excel."'";
            }else{
              $update[] = '`'.$File_column[$key_col]."` = '".$value_excel."'";
            }
          }
        if ($File_type == 0) {
          $into = join( ',' , $into);
          $insert_value = join( ',' , $insert_value);
          $sql[] = "INSERT INTO `{$File_schema_name}`.`{$File_table_name}` ({$into}) VALUES ({$insert_value});\n";
        }else{
          $update = join(',' , $update);
          $sql[] = "UPDATE `{$File_schema_name}`.`{$File_table_name}` SET {$update} WHERE (`{$File_condition}` = '{$value->$File_condition_value}');\n";
        }
        
        //$key_value = $value->$File_SET_column_value;
        //$key_id = $value->$File_condition_value;
        //$sql[] = "UPDATE `{$File_schema_name}`.`{$File_table_name}` SET `{$DB_update_col_name}` = '{$key_value}' WHERE (`{$File_condition}` = '{$key_id}');";
      }
      if (!empty($set)) { }
    }
    $file_export = implode(' ', $sql);
    $filename = time();
    $headers = array(
      'Content-Type: plain/txt',
      'Cache-Control' => 'no-store, no-cache',
      'Content-Disposition' => 'attachment; filename="' . $filename . '.sql"',
    );
    return  response($file_export)->withHeaders($headers);
  }

  public function readData (Request $request)
  {
    $path = $request->file('file')->getRealPath();
    $data = Excel::load($path)->get();
    dd($data);
  }
}
