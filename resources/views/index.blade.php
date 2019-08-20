<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
</head>
<body>
    <form id="app" action="{{route('export')}}" method="post" enctype="multipart/form-data">
        @csrf
        Excel File
        <input type="file" name="file" onchange="ExcelToJSON(event)">
        <!--input type="file" name="file"-->
        <button class="btn btn-success" type="submit">Submit</button>
        <hr>
        <input-form></input-form>
        <table border="1">
            <tr id="thead">
                
            </tr>
        </table>
    </form>
<script src="{{ asset('/js/app.js') }}"></script>
</body>
<script>
    var ExcelToJSON = function(ev) {
        const file = ev.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
                workbook.SheetNames.forEach(function(sheetName) {// Here is your object
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                console.log(XL_row_object);
                console.log( get_header_row(workbook.Sheets[sheetName]) )
                //var json_object = JSON.stringify(XL_row_object);
                //console.log(json_object);
            })

            console.log(data);
        };
            reader.onerror = function(ex) {
            console.log(ex);
        };
        reader.readAsBinaryString(file);
    }
    function get_header_row(sheet) {
    var headers = [];
    var range = XLSX.utils.decode_range(sheet['!ref']);
    var C, R = range.s.r; /* start in the first row */
    /* walk every column in the range */
    for(C = range.s.c; C <= range.e.c; ++C) {
        var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})] /* find the cell in the first row */

        var hdr = "UNKNOWN " + C; // <-- replace with your desired default 
        if(cell && cell.t) hdr = XLSX.utils.format_cell(cell);

        headers.push(hdr);
    }
    return headers;
}
</script>
</html>