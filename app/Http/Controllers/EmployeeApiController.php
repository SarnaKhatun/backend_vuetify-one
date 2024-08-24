<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class EmployeeApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $employee;

    public function __construct()
    {
        $this->employee = new Employee();
    }

    public function index()
    {
        try {
            return response()->json($this->employee->all(), 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['error' =>$e], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:employees',
                'phone' => 'required',
                'salary' => 'required',
            ]);

            $employeeData = $request->except('image');
            $employee = $this->employee->create($employeeData);

            if ($request->hasFile('image')) {
                $allowedFileExtensions = ['jpeg', 'png', 'jpg', 'webp', 'docx', 'pdf'];
                $files = $request->file('image');

                $fileNames = [];

                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();

                    if (in_array($extension, $allowedFileExtensions)) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('employee_images', $fileName, 'public');

                        $fileNames[] = $fileName;
                    } else {
                        return response()->json(['error' => 'Invalid file type'], 422);
                    }
                }

                $employee->update(['image' => json_encode($fileNames)]);
            }

            return response()->json($employee, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $employee = $this->employee->find($id);
            if (!$employee)
            {
                return response()->json(['error' => "Employee not found"], 404);
            }
            return response()->json($employee, 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>$e], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        try {
            $employee = $this->employee->find($id);
            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $employeeData = $request->except(['image']);


            $employee->update($employeeData);


            if ($request->hasFile('image')) {
                $allowedFileExtensions = ['jpeg', 'png', 'jpg', 'webp', 'docx', 'pdf'];
                $files = $request->file('image');

                $fileNames = [];

                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();

                    if (in_array($extension, $allowedFileExtensions)) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('employee_images', $fileName, 'public');

                        $fileNames[] = $fileName;
                    } else {
                        return response()->json(['error' => 'Invalid file type'], 422);
                    }
                }


                $employee->update(['image' => json_encode($fileNames)]);
            }


            return response()->json($employee, 200);

        } catch (\Exception $e) {

            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function updates(Request $request, $id)
    {
        try {
            $employee = $this->employee->find($id);
            if (!$employee)
            {
                return response()->json(['error'=>'Employee not found'], 404);
            }
            $employeeData = $request->except(['image']);

            $employee->update($employeeData);


            if ($request->hasFile('image')) {
                $allowedFileExtensions = ['jpeg', 'png', 'jpg', 'webp', 'docx', 'pdf'];
                $files = $request->file('image');

                $fileNames = [];

                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();

                    if (in_array($extension, $allowedFileExtensions)) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('employee_images', $fileName, 'public');

                        $fileNames[] = $fileName;
                    } else {
                        return response()->json(['error' => 'Invalid file type'], 422);
                    }
                }

                $employee->update(['image' => json_encode($fileNames)]);
            }

            return response()->json($employee, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = $this->employee->find($id);
            if (!$employee)
            {
                return response()->json(['error'=>'Employee not found'], 404);
            }
            $employee->delete();
            return response()->json(['message'=>'Employee Deleted Successfully'], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>$e], 500);
        }
    }

}
