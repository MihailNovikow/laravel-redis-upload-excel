<?php




namespace App\Http\Controllers;

use App\Models\Row;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Writer\Xls;

use PhpOffice\PhpSpreadsheet\IOFactory;






class ExcelController extends Controller

{

   /**

    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View

    */

   function index()

   {
$data = [];
       $data = DB::table('rows')->orderBy('date', 'DESC')->paginate(5);

       return view('welcome')->with($data);// compact('data'));

   }




   /**

    * @param Request $request

    * @return \Illuminate\Http\RedirectResponse

    * @throws \Illuminate\Validation\ValidationException

    * @throws \PhpOffice\PhpSpreadsheet\Exception

    */

   function importData(Request $request){



       $request->validate([

           'uploaded_file' => 'required|file|mimes:xls,xlsx'

       ]);
$fileModel = new Row();



       $the_file = $request->file('uploaded_file');

       try{
            $the_file->name = time().'_'.$the_file->getClientOriginalName();
            $fileName =  $the_file->name;
           // $filePath = $the_file->storeAs('uploads', $fileName, 'public');
           // $fileModel->name = time().'_'.$the_file->getClientOriginalName();
           // $fileModel->file_path = '/storage/' . $filePath;
          //  $the_file->save();

           $spreadsheet = IOFactory::load($the_file->getRealPath());

           $sheet        = $spreadsheet->getActiveSheet();

           $row_limit    = $sheet->getHighestDataRow();

           $column_limit = $sheet->getHighestDataColumn();

           $row_range    = range( 2, $row_limit );

           $column_range = range( 'C', $column_limit );

           $startcount = 2;
           $data = [];
 foreach ( $row_range as $row ) {

               $data[] = [
                   'row_id' =>$sheet->getCell( 'A' . $row )->getValue(),

                   'name' => $sheet->getCell( 'B' . $row )->getValue(),

                   'date' => $sheet->getCell( 'C' . $row )->getValue(),
               ];

               $startcount++;

           }




           DB::table('rows')->insert($data);

       } catch (Exception $e) {

           $error_code = $e->errorInfo[1];




           return back()->withErrors('There was a problem uploading the data!');

       }

       return back()->withSuccess('Great! Data has been successfully uploaded.');




   }




   /**

    * @param $customer_data

    */

   public function ExportExcel($customer_data){

       ini_set('max_execution_time', 0);

       ini_set('memory_limit', '4000M');




       try {

           $spreadSheet = new Spreadsheet();

           $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

           $spreadSheet->getActiveSheet()->fromArray($customer_data);




           $Excel_writer = new Xls($spreadSheet);

           header('Content-Type: application/vnd.ms-excel');

           header('Content-Disposition: attachment;filename="Customer_ExportedData.xls"');

           header('Cache-Control: max-age=0');

           ob_end_clean();

           $Excel_writer->save('php://output');

           exit();

       } catch (Exception $e) {

           return;

       }




   }




   /**

    *This function loads the customer data from the database then converts it

    * into an Array that will be exported to Excel

    */

   function exportData(){

       $data = DB::table('rows')->orderBy('row_id', 'DESC')->get();




       $data_array [] = array("id","name","date");

       foreach($data as $data_item)

       {

           $data_array[] = array(

               'row_id' =>$data_item->row_id,

               'name' => $data_item->name,

               'date' => $data_item->date,



           );




       }

       $this->ExportExcel($data_array);




   }

}
