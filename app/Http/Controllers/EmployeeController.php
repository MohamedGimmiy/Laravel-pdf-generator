<?php

namespace App\Http\Controllers;

use App;
//use Barryvdh\DomPDF\Facade\Pdf as pdf;
use PDF;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// second way
// https://github.com/spiritix/php-chrome-html2pdf


class EmployeeController extends Controller
{
    public function showEmployees()
    {
        $employee = Employee::all();
        return view('index', compact('employee'));
    }

    // Generate PDF
    public function view_pdf(){

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertHTMLtoPDF())->setPaper('a3','landscape');

        // open file in browser
        $created = $pdf->stream();
        // save file
        $pdf->save(public_path().'/myfile3.pdf');
        // download file
        $downloading = $pdf->download('myfile.pdf');


        return $created;
        /* catch( \Exception $e){
            return redirect('/orders')->with('error', $e->getMessage());
        } */
    }
    public function convertHTMLtoPDF()
    {

        $employees = Employee::all();

/*         $orders->transform(function($order, $key){
            $order->cart = unserialize($order->cart);

            return $order;
        }); */

        $output = '<!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Laravel 7 PDF Example</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link href="{{ asset("css/app.css") }}" rel="stylesheet" type="text/css" />
        </head>
        <body>
        <div class="container mt-5">
        <h2 class="text-center mb-3">Laravel HTML to PDF Example</h2>
        <div class="d-flex justify-content-end mb-4">
        <a class="btn btn-primary" href="{{ URL::to("/employee/pdf") }}">Export to PDF</a>
        </div>
        <table class="table table-bordered mb-5">
        <thead>
        <tr class="table-danger">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">DOB</th>
                            </tr>
                            </thead>
                            <tbody>';

                            foreach($employees as $employee){
                                $id = $employee->id;
                                $name = $employee->name;
                                $email = $employee->email;
                                $phone_number = $employee->phone_number;
                                $dob = $employee->dob;

                        $output .= '<tr>
                        <th scope="row">'. $id.'</th>
                        <th scope="row">'. $name.'</th>
                        <td>'. $email .'</td>
                        <td>'. $phone_number .'</td>
                        <td>'. $dob .'</td>
                    </tr>';
                }

                $output .='</tbody></table>';
                return $output;
            }



            public function createPDF() {
                set_time_limit(120);
                // retreive all records from db
                $data = Employee::all();
                // share data to view
                $pdf = PDF::loadView('index', [
                    'employee' => $data
                ]);
                return $pdf->download('latest.pdf');
              }
        }

