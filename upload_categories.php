<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script src="js/bootstrap.bundle.min.js"></script>
   
  </head>
  <body>
<h2>Import Categories Excel File into Database </h2>
    
    <div class="outer-container">
        <span id="message"></span>
        <form action="" method="post"
            id ="import_excel-form"enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <td>
                        Select Excel file
                    </td>
                    <td>
                        <input type ="file" name="import_excel"/>
                    </td>
                    <td>
                        <input type ="submi" name="import" id="import" class="btn btn-primary" value="Import"/>
                    </td>
                </tr>

            
        
        </form>
        
    </div>

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</body>
</html>
    <script>
        $(document).ready(function(){
        $('#import_excel_form').on('submit',function(event){
            event.preventDefault();
            $.ajax({
                url:"import.php",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                beforeSend:function(){
                    $('#import').attr('disabled','disabled');
                    $('#import').val('Impotring...');
                },
                success:function(data)
                {
                    $('#message').html(data);
                    $('#import_excel_form')[0].reset();
                    $('#import').attr('disabled', false);
                    $('#import').val('Import');
                }
            })
        });
    });

    </script>
 

    